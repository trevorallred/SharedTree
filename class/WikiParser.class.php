<?
require_once( 'Sanitizer.php' );

class WikiParser {
	/**
	 * Constructor
	 *
	 * @access public
	 */
	function Parser() {
		$this->clearState();
	}

	/**
	 * Convert wikitext to HTML
	 * Do not call this function recursively.
	 *
	 * @param string $text Text we want to parse
	 * @return string HTML text output
	 */
	public function parse( $text ) {
		/**
		 * First pass--just handle <nowiki> sections, pass the rest off
		 * to internalParse() which does all the real work.
		 */
		$text = $this->strip( $text, $x );

		$text = $this->internalParse( $text );

		$text = $this->unstrip( $text, $this->mStripState );

		# Clean up special characters, only run once, next-to-last before doBlockLevels
		$fixtags = array(
			# french spaces, last one Guillemet-left
			# only if there is something before the space
			'/(.) (?=\\?|:|;|!|\\302\\273)/' => '\\1&nbsp;\\2',
			# french spaces, Guillemet-right
			'/(\\302\\253) /' => '\\1&nbsp;',
			'/<center *>(.*)<\\/center *>/i' => '<div class="center">\\1</div>',
		);
		$text = preg_replace( array_keys($fixtags), array_values($fixtags), $text );

		# only once and last
		$text = $this->doBlockLevels( $text, $linestart );

		//$this->replaceLinkHolders( $text );

		# the position of the parserConvert() call should not be changed. it
		# assumes that the links are all replaced and the only thing left
		# is the <nowiki> mark.
		# Side-effects: this calls $this->mOutput->setTitleText()
		//$text = $wgContLang->parserConvert( $text, $this );

		//$text = $this->unstripNoWiki( $text, $this->mStripState );

		$text = Sanitizer::normalizeCharReferences( $text );

		if (($wgUseTidy and $this->mOptions->mTidy) or $wgAlwaysUseTidy) {
			$text = WikiParser::tidy($text);
		} else {
			# attempt to sanitize at least some nesting problems
			# (bug #2702 and quite a few others)
			$tidyregs = array(	
				# ''Something [http://www.cool.com cool''] --> 
				# <i>Something</i><a href="http://www.cool.com"..><i>cool></i></a>
				'/(<([bi])>)(<([bi])>)?([^<]*)(<\/?a[^<]*>)([^<]*)(<\/\\4>)?(<\/\\2>)/' =>
				'\\1\\3\\5\\8\\9\\6\\1\\3\\7\\8\\9',
				# fix up an anchor inside another anchor, only
				# at least for a single single nested link (bug 3695)
				'/(<a[^>]+>)([^<]*)(<a[^>]+>[^<]*)<\/a>(.*)<\/a>/' =>
				'\\1\\2</a>\\3</a>\\1\\4</a>',
				# fix div inside inline elements- doBlockLevels won't wrap a line which
				# contains a div, so fix it up here; replace
				# div with escaped text
				'/(<([aib]) [^>]+>)([^<]*)(<div([^>]*)>)(.*)(<\/div>)([^<]*)(<\/\\2>)/' =>
				'\\1\\3&lt;div\\5&gt;\\6&lt;/div&gt;\\8\\9',
				# remove empty italic or bold tag pairs, some
				# introduced by rules above
				'/<([bi])><\/\\1>/' => '' 
			);

			$text = preg_replace( 
				array_keys( $tidyregs ),
				array_values( $tidyregs ),
				$text );
		}

		//$this->mOutput->setText( $text );
		//wfProfileOut( $fname );

		return $text;
	}

	/**
	 * Helper function for parse() that transforms wiki markup into
	 * HTML. Only called for $mOutputType == OT_HTML.
	 */
	private function internalParse( $text ) {
		$args = array();
		$isMain = true;
		$fname = 'WikiParser::internalParse';

		# Remove <noinclude> tags and <includeonly> sections
		$text = strtr( $text, array( '<onlyinclude>' => '' , '</onlyinclude>' => '' ) );
		$text = strtr( $text, array( '<noinclude>' => '', '</noinclude>' => '') );
		$text = preg_replace( '/<includeonly>.*?<\/includeonly>/s', '', $text );

		// FIX ME!!!!!!!!
		// In order to get a href to show up I removed this line. This is bad!!
		//
		//$text = Sanitizer::removeHTMLtags( $text, array( &$this, 'attributeStripCallback' ) );

		$text = $this->replaceVariables( $text, $args );

		$text = preg_replace( '/(^|\n)-----*/', '\\1<hr />', $text );

		$text = $this->doHeadings( $text );
		$text = $this->doAllQuotes( $text );
		//$text = $this->replaceInternalLinks( $text );
		//$text = $this->replaceExternalLinks( $text );

		# replaceInternalLinks may sometimes leave behind
		# absolute URLs, which have to be masked to hide them from replaceExternalLinks
		$text = str_replace($this->mUniqPrefix."NOPARSE", "", $text);

		//$text = $this->doMagicLinks( $text );
		$text = $this->doTableStuff( $text );
		//$text = $this->formatHeadings( $text, $isMain );

		wfProfileOut( $fname );
		return $text;
	}

	/**
	 * Strips and renders nowiki, pre, math, hiero
	 * If $render is set, performs necessary rendering operations on plugins
	 * Returns the text, and fills an array with data needed in unstrip()
	 * If the $state is already a valid strip state, it adds to the state
	 *
	 * @param bool $stripcomments when set, HTML comments <!-- like this -->
	 *  will be stripped in addition to other tags. This is important
	 *  for section editing, where these comments cause confusion when
	 *  counting the sections in the wikisource
	 *
	 * @access private
	 */
	function strip( $text, &$state, $stripcomments = false ) {
		$render = ($this->mOutputType == OT_HTML);
		$html_content = array();
		$nowiki_content = array();
		$math_content = array();
		$pre_content = array();
		$comment_content = array();
		$ext_content = array();
		$ext_tags = array();
		$ext_params = array();
		$gallery_content = array();

		# Replace any instances of the placeholders
		$uniq_prefix = $this->mUniqPrefix;
		#$text = str_replace( $uniq_prefix, wfHtmlEscapeFirst( $uniq_prefix ), $text );

		# html
		global $wgRawHtml;
		if( $wgRawHtml ) {
			$text = WikiParser::extractTags('html', $text, $html_content, $uniq_prefix);
			foreach( $html_content as $marker => $content ) {
				if ($render ) {
					# Raw and unchecked for validity.
					$html_content[$marker] = $content;
				} else {
					$html_content[$marker] = '<html>'.$content.'</html>';
				}
			}
		}

		# nowiki
		$text = WikiParser::extractTags('nowiki', $text, $nowiki_content, $uniq_prefix);
		foreach( $nowiki_content as $marker => $content ) {
			if( $render ){
				$nowiki_content[$marker] = wfEscapeHTMLTagsOnly( $content );
			} else {
				$nowiki_content[$marker] = '<nowiki>'.$content.'</nowiki>';
			}
		}

		# pre
		$text = WikiParser::extractTags('pre', $text, $pre_content, $uniq_prefix);
		foreach( $pre_content as $marker => $content ){
			if( $render ){
				$pre_content[$marker] = '<pre>' . wfEscapeHTMLTagsOnly( $content ) . '</pre>';
			} else {
				$pre_content[$marker] = '<pre>'.$content.'</pre>';
			}
		}

		# gallery
		$text = WikiParser::extractTags('gallery', $text, $gallery_content, $uniq_prefix);
		foreach( $gallery_content as $marker => $content ) {
			require_once( 'ImageGallery.php' );
			if ( $render ) {
				$gallery_content[$marker] = $this->renderImageGallery( $content );
			} else {
				$gallery_content[$marker] = '<gallery>'.$content.'</gallery>';
			}
		}

		# Comments
		$text = WikiParser::extractTags(STRIP_COMMENTS, $text, $comment_content, $uniq_prefix);
		foreach( $comment_content as $marker => $content ){
			$comment_content[$marker] = '<!--'.$content.'-->';
		}

		$tempstate = array( 'comment' => $comment_content );
		$text = $this->unstrip( $text, $tempstate );
		$comment_content = array();

		# Merge state with the pre-existing state, if there is one
		if ( $state ) {
			$state['html'] = $state['html'] + $html_content;
			$state['nowiki'] = $state['nowiki'] + $nowiki_content;
			$state['math'] = $state['math'] + $math_content;
			$state['pre'] = $state['pre'] + $pre_content;
			$state['gallery'] = $state['gallery'] + $gallery_content;
			$state['comment'] = $state['comment'] + $comment_content;

			foreach( $ext_content as $tag => $array ) {
				if ( array_key_exists( $tag, $state ) ) {
					$state[$tag] = $state[$tag] + $array;
				}
			}
		} else {
			$state = array(
			  'html' => $html_content,
			  'nowiki' => $nowiki_content,
			  'math' => $math_content,
			  'pre' => $pre_content,
			  'gallery' => $gallery_content,
			  'comment' => $comment_content,
			) + $ext_content;
		}
		return $text;
	}

	/**
	 * restores pre, math, and hiero removed by strip()
	 *
	 * always call unstripNoWiki() after this one
	 * @access private
	 */
	function unstrip( $text, &$state ) {
		if ( !is_array( $state ) ) {
			return $text;
		}

		# Must expand in reverse order, otherwise nested tags will be corrupted
		foreach( array_reverse( $state, true ) as $tag => $contentDict ) {
			if( $tag != 'nowiki' && $tag != 'html' ) {
				foreach( array_reverse( $contentDict, true ) as $uniq => $content ) {
					$text = str_replace( $uniq, $content, $text );
				}
			}
		}

		return $text;
	}

	/**
	 * Replaces all occurrences of <$tag>content</$tag> in the text
	 * with a random marker and returns the new text. the output parameter
	 * $content will be an associative array filled with data on the form
	 * $unique_marker => content.
	 *
	 * If $content is already set, the additional entries will be appended
	 * If $tag is set to STRIP_COMMENTS, the function will extract
	 * <!-- HTML comments -->
	 *
	 * @access private
	 * @static
	 */
	function extractTagsAndParams($tag, $text, &$content, &$tags, &$params, $uniq_prefix = ''){
		$rnd = $uniq_prefix . '-' . $tag; // . WikiParser::getRandomString();
		if ( !$content ) {
			$content = array( );
		}
		$n = 1;
		$stripped = '';

		if ( !$tags ) {
			$tags = array( );
		}

		if ( !$params ) {
			$params = array( );
		}

		if( $tag == STRIP_COMMENTS ) {
			$start = '/<!--()/';
			$end   = '/-->/';
		} else {
			$start = "/<$tag(\\s+[^>]*|\\s*\/?)>/i";
			$end   = "/<\\/$tag\\s*>/i";
		}

		while ( '' != $text ) {
			$p = preg_split( $start, $text, 2, PREG_SPLIT_DELIM_CAPTURE );
			$stripped .= $p[0];
			if( count( $p ) < 3 ) {
				break;
			}
			$attributes = $p[1];
			$inside     = $p[2];

			// If $attributes ends with '/', we have an empty element tag, <tag />
			if( $tag != STRIP_COMMENTS && substr( $attributes, -1 ) == '/' ) {
				$attributes = substr( $attributes, 0, -1);
				$empty = '/';
			} else {
				$empty = '';
			}

			$marker = $rnd . sprintf('%08X', $n++);
			$stripped .= $marker;

			$tags[$marker] = "<$tag$attributes$empty>";
			$params[$marker] = Sanitizer::decodeTagAttributes( $attributes );

			if ( $empty === '/' ) {
				// Empty element tag, <tag />
				$content[$marker] = null;
				$text = $inside;
			} else {
				$q = preg_split( $end, $inside, 2 );
				$content[$marker] = $q[0];
				if( count( $q ) < 2 ) {
					# No end tag -- let it run out to the end of the text.
					break;
				} else {
					$text = $q[1];
				}
			}
		}
		return $stripped;
	}

	/**
	 * Wrapper function for extractTagsAndParams
	 * for cases where $tags and $params isn't needed
	 * i.e. where tags will never have params, like <nowiki>
	 *
	 * @access private
	 * @static
	 */
	function extractTags( $tag, $text, &$content, $uniq_prefix = '' ) {
		$dummy_tags = array();
		$dummy_params = array();

		return WikiParser::extractTagsAndParams( $tag, $text, $content,
			$dummy_tags, $dummy_params, $uniq_prefix );
	}

	/**
	 * Replace magic variables, templates, and template arguments
	 * with the appropriate text. Templates are substituted recursively,
	 * taking care to avoid infinite loops.
	 *
	 * Note that the substitution depends on value of $mOutputType:
	 *  OT_WIKI: only {{subst:}} templates
	 *  OT_MSG: only magic variables
	 *  OT_HTML: all templates and magic variables
	 *
	 * @param string $tex The text to transform
	 * @param array $args Key-value pairs representing template parameters to substitute
	 * @param bool $argsOnly Only do argument (triple-brace) expansion, not double-brace expansion
	 * @access private
	 */
	function replaceVariables( $text, $args = array(), $argsOnly = false ) {
		# Prevent too big inclusions
		if( strlen( $text ) > MAX_INCLUDE_SIZE ) {
			return $text;
		}

		$fname = 'Parser::replaceVariables';
		wfProfileIn( $fname );

		# This function is called recursively. To keep track of arguments we need a stack:
//		array_push( $this->mArgStack, $args );

		$braceCallbacks = array();
		if ( !$argsOnly ) {
			$braceCallbacks[2] = array( &$this, 'braceSubstitution' );
		}
		if ( $this->mOutputType == OT_HTML || $this->mOutputType == OT_WIKI ) {
			$braceCallbacks[3] = array( &$this, 'argSubstitution' );
		}
		$callbacks = array();
		$callbacks['{'] = array('end' => '}', 'cb' => $braceCallbacks);
		$callbacks['['] = array('end' => ']', 'cb' => array(2=>null));
		//$text = $this->replace_callback ($text, $callbacks);

//		array_pop( $this->mArgStack );

		return $text;
	}

	/**
	 * Make lists from lines starting with ':', '*', '#', etc.
	 *
	 * @access private
	 * @return string the lists rendered as HTML
	 */
	function doBlockLevels( $text, $linestart ) {
		$fname = 'WikiParser::doBlockLevels';
		wfProfileIn( $fname );

		# Parsing through the text line by line.  The main thing
		# happening here is handling of block-level elements p, pre,
		# and making lists from lines starting with * # : etc.
		#
		$textLines = explode( "\n", $text );

		$lastPrefix = $output = '';
		$this->mDTopen = $inBlockElem = false;
		$prefixLength = 0;
		$paragraphStack = false;

		if ( !$linestart ) {
			$output .= array_shift( $textLines );
		}
		foreach ( $textLines as $oLine ) {
			$lastPrefixLength = strlen( $lastPrefix );
			$preCloseMatch = preg_match('/<\\/pre/i', $oLine );
			$preOpenMatch = preg_match('/<pre/i', $oLine );
			if ( !$this->mInPre ) {
				# Multiple prefixes may abut each other for nested lists.
				$prefixLength = strspn( $oLine, '*#:;' );
				$pref = substr( $oLine, 0, $prefixLength );

				# eh?
				$pref2 = str_replace( ';', ':', $pref );
				$t = substr( $oLine, $prefixLength );
				$this->mInPre = !empty($preOpenMatch);
			} else {
				# Don't interpret any other prefixes in preformatted text
				$prefixLength = 0;
				$pref = $pref2 = '';
				$t = $oLine;
			}

			# List generation
			if( $prefixLength && 0 == strcmp( $lastPrefix, $pref2 ) ) {
				# Same as the last item, so no need to deal with nesting or opening stuff
				$output .= $this->nextItem( substr( $pref, -1 ) );
				$paragraphStack = false;

				if ( substr( $pref, -1 ) == ';') {
					# The one nasty exception: definition lists work like this:
					# ; title : definition text
					# So we check for : in the remainder text to split up the
					# title and definition, without b0rking links.
					$term = $t2 = '';
					if ($this->findColonNoLinks($t, $term, $t2) !== false) {
						$t = $t2;
						$output .= $term . $this->nextItem( ':' );
					}
				}
			} elseif( $prefixLength || $lastPrefixLength ) {
				# Either open or close a level...
				$commonPrefixLength = $this->getCommon( $pref, $lastPrefix );
				$paragraphStack = false;

				while( $commonPrefixLength < $lastPrefixLength ) {
					$output .= $this->closeList( $lastPrefix{$lastPrefixLength-1} );
					--$lastPrefixLength;
				}
				if ( $prefixLength <= $commonPrefixLength && $commonPrefixLength > 0 ) {
					$output .= $this->nextItem( $pref{$commonPrefixLength-1} );
				}
				while ( $prefixLength > $commonPrefixLength ) {
					$char = substr( $pref, $commonPrefixLength, 1 );
					$output .= $this->openList( $char );

					if ( ';' == $char ) {
						# FIXME: This is dupe of code above
						if ($this->findColonNoLinks($t, $term, $t2) !== false) {
							$t = $t2;
							$output .= $term . $this->nextItem( ':' );
						}
					}
					++$commonPrefixLength;
				}
				$lastPrefix = $pref2;
			}
			if( 0 == $prefixLength ) {
				wfProfileIn( "$fname-paragraph" );
				# No prefix (not in list)--go to paragraph mode
				// XXX: use a stack for nestable elements like span, table and div
				$openmatch = preg_match('/(<table|<blockquote|<h1|<h2|<h3|<h4|<h5|<h6|<pre|<tr|<p|<ul|<li|<\\/tr|<\\/td|<\\/th)/iS', $t );
				$closematch = preg_match(
					'/(<\\/table|<\\/blockquote|<\\/h1|<\\/h2|<\\/h3|<\\/h4|<\\/h5|<\\/h6|'.
					'<td|<th|<div|<\\/div|<hr|<\\/pre|<\\/p|'.$this->mUniqPrefix.'-pre|<\\/li|<\\/ul)/iS', $t );
				if ( $openmatch or $closematch ) {
					$paragraphStack = false;
					$output .= $this->closeParagraph();
					if ( $preOpenMatch and !$preCloseMatch ) {
						$this->mInPre = true;
					}
					if ( $closematch ) {
						$inBlockElem = false;
					} else {
						$inBlockElem = true;
					}
				} else if ( !$inBlockElem && !$this->mInPre ) {
					if ( ' ' == $t{0} and ( $this->mLastSection == 'pre' or trim($t) != '' ) ) {
						// pre
						if ($this->mLastSection != 'pre') {
							$paragraphStack = false;
							$output .= $this->closeParagraph().'<pre>';
							$this->mLastSection = 'pre';
						}
						$t = substr( $t, 1 );
					} else {
						// paragraph
						if ( '' == trim($t) ) {
							if ( $paragraphStack ) {
								$output .= $paragraphStack.'<br />';
								$paragraphStack = false;
								$this->mLastSection = 'p';
							} else {
								if ($this->mLastSection != 'p' ) {
									$output .= $this->closeParagraph();
									$this->mLastSection = '';
									$paragraphStack = '<p>';
								} else {
									$paragraphStack = '</p><p>';
								}
							}
						} else {
							if ( $paragraphStack ) {
								$output .= $paragraphStack;
								$paragraphStack = false;
								$this->mLastSection = 'p';
							} else if ($this->mLastSection != 'p') {
								$output .= $this->closeParagraph().'<p>';
								$this->mLastSection = 'p';
							}
						}
					}
				}
				wfProfileOut( "$fname-paragraph" );
			}
			// somewhere above we forget to get out of pre block (bug 785)
			if($preCloseMatch && $this->mInPre) {
				$this->mInPre = false;
			}
			if ($paragraphStack === false) {
				$output .= $t."\n";
			}
		}
		while ( $prefixLength ) {
			$output .= $this->closeList( $pref2{$prefixLength-1} );
			--$prefixLength;
		}
		if ( '' != $this->mLastSection ) {
			$output .= '</' . $this->mLastSection . '>';
			$this->mLastSection = '';
		}

		wfProfileOut( $fname );
		return $output;
	}

	/**#@+
	 * Used by doBlockLevels()
	 * @access private
	 */
	/* private */ function closeParagraph() {
		$result = '';
		if ( '' != $this->mLastSection ) {
			$result = '</' . $this->mLastSection  . ">\n";
		}
		$this->mInPre = false;
		$this->mLastSection = '';
		return $result;
	}
	# getCommon() returns the length of the longest common substring
	# of both arguments, starting at the beginning of both.
	#
	/* private */ function getCommon( $st1, $st2 ) {
		$fl = strlen( $st1 );
		$shorter = strlen( $st2 );
		if ( $fl < $shorter ) { $shorter = $fl; }

		for ( $i = 0; $i < $shorter; ++$i ) {
			if ( $st1{$i} != $st2{$i} ) { break; }
		}
		return $i;
	}
	# These next three functions open, continue, and close the list
	# element appropriate to the prefix character passed into them.
	#
	/* private */ function openList( $char ) {
		$result = $this->closeParagraph();

		if ( '*' == $char ) { $result .= '<ul><li>'; }
		else if ( '#' == $char ) { $result .= '<ol><li>'; }
		else if ( ':' == $char ) { $result .= '<dl><dd>'; }
		else if ( ';' == $char ) {
			$result .= '<dl><dt>';
			$this->mDTopen = true;
		}
		else { $result = '<!-- ERR 1 -->'; }

		return $result;
	}

	/* private */ function nextItem( $char ) {
		if ( '*' == $char || '#' == $char ) { return '</li><li>'; }
		else if ( ':' == $char || ';' == $char ) {
			$close = '</dd>';
			if ( $this->mDTopen ) { $close = '</dt>'; }
			if ( ';' == $char ) {
				$this->mDTopen = true;
				return $close . '<dt>';
			} else {
				$this->mDTopen = false;
				return $close . '<dd>';
			}
		}
		return '<!-- ERR 2 -->';
	}

	/* private */ function closeList( $char ) {
		if ( '*' == $char ) { $text = '</li></ul>'; }
		else if ( '#' == $char ) { $text = '</li></ol>'; }
		else if ( ':' == $char ) {
			if ( $this->mDTopen ) {
				$this->mDTopen = false;
				$text = '</dt></dl>';
			} else {
				$text = '</dd></dl>';
			}
		}
		else {	return '<!-- ERR 3 -->'; }
		return $text."\n";
	}

	/**
	 * parse the wiki syntax used to render tables
	 *
	 * @access private
	 */
	function doTableStuff ( $t ) {
		$fname = 'Parser::doTableStuff';
		wfProfileIn( $fname );

		$t = explode ( "\n" , $t ) ;
		$td = array () ; # Is currently a td tag open?
		$ltd = array () ; # Was it TD or TH?
		$tr = array () ; # Is currently a tr tag open?
		$ltr = array () ; # tr attributes
		$has_opened_tr = array(); # Did this table open a <tr> element?
		$indent_level = 0; # indent level of the table
		foreach ( $t AS $k => $x )
		{
			$x = trim ( $x ) ;
			$fc = substr ( $x , 0 , 1 ) ;
			if ( preg_match( '/^(:*)\{\|(.*)$/', $x, $matches ) ) {
				$indent_level = strlen( $matches[1] );

				$attributes = $this->unstripForHTML( $matches[2] );

				$t[$k] = str_repeat( '<dl><dd>', $indent_level ) .
					'<table' . Sanitizer::fixTagAttributes ( $attributes, 'table' ) . '>' ;
				array_push ( $td , false ) ;
				array_push ( $ltd , '' ) ;
				array_push ( $tr , false ) ;
				array_push ( $ltr , '' ) ;
				array_push ( $has_opened_tr, false );
			}
			else if ( count ( $td ) == 0 ) { } # Don't do any of the following
			else if ( '|}' == substr ( $x , 0 , 2 ) ) {
				$z = "</table>" . substr ( $x , 2);
				$l = array_pop ( $ltd ) ;
				if ( !array_pop ( $has_opened_tr ) ) $z = "<tr><td></td></tr>" . $z ;
				if ( array_pop ( $tr ) ) $z = '</tr>' . $z ;
				if ( array_pop ( $td ) ) $z = '</'.$l.'>' . $z ;
				array_pop ( $ltr ) ;
				$t[$k] = $z . str_repeat( '</dd></dl>', $indent_level );
			}
			else if ( '|-' == substr ( $x , 0 , 2 ) ) { # Allows for |---------------
				$x = substr ( $x , 1 ) ;
				while ( $x != '' && substr ( $x , 0 , 1 ) == '-' ) $x = substr ( $x , 1 ) ;
				$z = '' ;
				$l = array_pop ( $ltd ) ;
				array_pop ( $has_opened_tr );
				array_push ( $has_opened_tr , true ) ;
				if ( array_pop ( $tr ) ) $z = '</tr>' . $z ;
				if ( array_pop ( $td ) ) $z = '</'.$l.'>' . $z ;
				array_pop ( $ltr ) ;
				$t[$k] = $z ;
				array_push ( $tr , false ) ;
				array_push ( $td , false ) ;
				array_push ( $ltd , '' ) ;
				$attributes = $this->unstripForHTML( $x );
				array_push ( $ltr , Sanitizer::fixTagAttributes ( $attributes, 'tr' ) ) ;
			}
			else if ( '|' == $fc || '!' == $fc || '|+' == substr ( $x , 0 , 2 ) ) { # Caption
				# $x is a table row
				if ( '|+' == substr ( $x , 0 , 2 ) ) {
					$fc = '+' ;
					$x = substr ( $x , 1 ) ;
				}
				$after = substr ( $x , 1 ) ;
				if ( $fc == '!' ) $after = str_replace ( '!!' , '||' , $after ) ;
				
				// Split up multiple cells on the same line.
				// FIXME: This can result in improper nesting of tags processed
				// by earlier parser steps, but should avoid splitting up eg
				// attribute values containing literal "||".
				$after = wfExplodeMarkup( '||', $after );
				
				$t[$k] = '' ;

				# Loop through each table cell
				foreach ( $after AS $theline )
				{
					$z = '' ;
					if ( $fc != '+' )
					{
						$tra = array_pop ( $ltr ) ;
						if ( !array_pop ( $tr ) ) $z = '<tr'.$tra.">\n" ;
						array_push ( $tr , true ) ;
						array_push ( $ltr , '' ) ;
						array_pop ( $has_opened_tr );
						array_push ( $has_opened_tr , true ) ;
					}

					$l = array_pop ( $ltd ) ;
					if ( array_pop ( $td ) ) $z = '</'.$l.'>' . $z ;
					if ( $fc == '|' ) $l = 'td' ;
					else if ( $fc == '!' ) $l = 'th' ;
					else if ( $fc == '+' ) $l = 'caption' ;
					else $l = '' ;
					array_push ( $ltd , $l ) ;

					# Cell parameters
					$y = explode ( '|' , $theline , 2 ) ;
					# Note that a '|' inside an invalid link should not
					# be mistaken as delimiting cell parameters
					if ( strpos( $y[0], '[[' ) !== false ) {
						$y = array ($theline);
					}
					if ( count ( $y ) == 1 )
						$y = "{$z}<{$l}>{$y[0]}" ;
					else {
						$attributes = $this->unstripForHTML( $y[0] );
						$y = "{$z}<{$l}".Sanitizer::fixTagAttributes($attributes, $l).">{$y[1]}" ;
					}
					$t[$k] .= $y ;
					array_push ( $td , true ) ;
				}
			}
		}

		# Closing open td, tr && table
		while ( count ( $td ) > 0 )
		{
			$l = array_pop ( $ltd ) ;
			if ( array_pop ( $td ) ) $t[] = '</td>' ;
			if ( array_pop ( $tr ) ) $t[] = '</tr>' ;
			if ( !array_pop ( $has_opened_tr ) ) $t[] = "<tr><td></td></tr>" ;
			$t[] = '</table>' ;
		}

		$t = implode ( "\n" , $t ) ;
		# special case: don't return empty table
		if($t == "<table>\n<tr><td></td></tr>\n</table>")
			$t = '';
		wfProfileOut( $fname );
		return $t ;
	}

	/**
	 * This function accomplishes several tasks:
	 * 1) Auto-number headings if that option is enabled
	 * 2) Add an [edit] link to sections for logged in users who have enabled the option
	 * 3) Add a Table of contents on the top for users who have enabled the option
	 * 4) Auto-anchor headings
	 *
	 * It loops through all headlines, collects the necessary data, then splits up the
	 * string and re-inserts the newly formatted headlines.
	 *
	 * @param string $text
	 * @param boolean $isMain
	 * @access private
	 */
	function formatHeadings( $text, $isMain=true ) {
		global $wgMaxTocLevel, $wgContLang;

		$doNumberHeadings = true;
		$doShowToc = true;
		$forceTocHere = false;
//		if( !$this->mTitle->userCanEdit() ) {
//			$showEditLink = 0;
//		} else {
//			$showEditLink = $this->mOptions->getEditSection();
//		}

		# Inhibit editsection links if requested in the page
		$esw =& MagicWord::get( MAG_NOEDITSECTION );
		if( $esw->matchAndRemove( $text ) ) {
			$showEditLink = 0;
		}
		# if the string __NOTOC__ (not case-sensitive) occurs in the HTML,
		# do not add TOC
		$mw =& MagicWord::get( MAG_NOTOC );
		if( $mw->matchAndRemove( $text ) ) {
			$doShowToc = false;
		}

		# Get all headlines for numbering them and adding funky stuff like [edit]
		# links - this is for later, but we need the number of headlines right now
		$numMatches = preg_match_all( '/<H([1-6])(.*?'.'>)(.*?)<\/H[1-6] *>/i', $text, $matches );

		# if there are fewer than 4 headlines in the article, do not show TOC
		if( $numMatches < 4 ) {
			$doShowToc = false;
		}

		# if the string __TOC__ (not case-sensitive) occurs in the HTML,
		# override above conditions and always show TOC at that place

		$mw =& MagicWord::get( MAG_TOC );
		if($mw->match( $text ) ) {
			$doShowToc = true;
			$forceTocHere = true;
		} else {
			# if the string __FORCETOC__ (not case-sensitive) occurs in the HTML,
			# override above conditions and always show TOC above first header
			$mw =& MagicWord::get( MAG_FORCETOC );
			if ($mw->matchAndRemove( $text ) ) {
				$doShowToc = true;
			}
		}

		# Never ever show TOC if no headers
		if( $numMatches < 1 ) {
			$doShowToc = false;
		}

		# We need this to perform operations on the HTML
		$sk =& $this->mOptions->getSkin();

		# headline counter
		$headlineCount = 0;
		$sectionCount = 0; # headlineCount excluding template sections

		# Ugh .. the TOC should have neat indentation levels which can be
		# passed to the skin functions. These are determined here
		$toc = '';
		$full = '';
		$head = array();
		$sublevelCount = array();
		$levelCount = array();
		$toclevel = 0;
		$level = 0;
		$prevlevel = 0;
		$toclevel = 0;
		$prevtoclevel = 0;

		foreach( $matches[3] as $headline ) {
			$istemplate = 0;
			$templatetitle = '';
			$templatesection = 0;
			$numbering = '';

			if (preg_match("/<!--MWTEMPLATESECTION=([^&]+)&([^_]+)-->/", $headline, $mat)) {
				$istemplate = 1;
				$templatetitle = base64_decode($mat[1]);
				$templatesection = 1 + (int)base64_decode($mat[2]);
				$headline = preg_replace("/<!--MWTEMPLATESECTION=([^&]+)&([^_]+)-->/", "", $headline);
			}

			if( $toclevel ) {
				$prevlevel = $level;
				$prevtoclevel = $toclevel;
			}
			$level = $matches[1][$headlineCount];

			if( $doNumberHeadings || $doShowToc ) {

				if ( $level > $prevlevel ) {
					# Increase TOC level
					$toclevel++;
					$sublevelCount[$toclevel] = 0;
					$toc .= $sk->tocIndent();
				}
				elseif ( $level < $prevlevel && $toclevel > 1 ) {
					# Decrease TOC level, find level to jump to

					if ( $toclevel == 2 && $level <= $levelCount[1] ) {
						# Can only go down to level 1
						$toclevel = 1;
					} else {
						for ($i = $toclevel; $i > 0; $i--) {
							if ( $levelCount[$i] == $level ) {
								# Found last matching level
								$toclevel = $i;
								break;
							}
							elseif ( $levelCount[$i] < $level ) {
								# Found first matching level below current level
								$toclevel = $i + 1;
								break;
							}
						}
					}

					$toc .= $sk->tocUnindent( $prevtoclevel - $toclevel );
				}
				else {
					# No change in level, end TOC line
					$toc .= $sk->tocLineEnd();
				}

				$levelCount[$toclevel] = $level;

				# count number of headlines for each level
				@$sublevelCount[$toclevel]++;
				$dot = 0;
				for( $i = 1; $i <= $toclevel; $i++ ) {
					if( !empty( $sublevelCount[$i] ) ) {
						if( $dot ) {
							$numbering .= '.';
						}
						$numbering .= $wgContLang->formatNum( $sublevelCount[$i] );
						$dot = 1;
					}
				}
			}

			# The canonized header is a version of the header text safe to use for links
			# Avoid insertion of weird stuff like <math> by expanding the relevant sections
			$canonized_headline = $this->unstrip( $headline, $this->mStripState );
			$canonized_headline = $this->unstripNoWiki( $canonized_headline, $this->mStripState );

			# Remove link placeholders by the link text.
			#     <!--LINK number-->
			# turns into
			#     link text with suffix
			$canonized_headline = preg_replace( '/<!--LINK ([0-9]*)-->/e',
							    "\$this->mLinkHolders['texts'][\$1]",
							    $canonized_headline );
			$canonized_headline = preg_replace( '/<!--IWLINK ([0-9]*)-->/e',
							    "\$this->mInterwikiLinkHolders['texts'][\$1]",
							    $canonized_headline );

			# strip out HTML
			$canonized_headline = preg_replace( '/<.*?' . '>/','',$canonized_headline );
			$tocline = trim( $canonized_headline );
			# Save headline for section edit hint before it's escaped
			$headline_hint = trim( $canonized_headline );
			$canonized_headline = Sanitizer::escapeId( $tocline );
			$refers[$headlineCount] = $canonized_headline;

			# count how many in assoc. array so we can track dupes in anchors
			@$refers[$canonized_headline]++;
			$refcount[$headlineCount]=$refers[$canonized_headline];

			# Don't number the heading if it is the only one (looks silly)
			if( $doNumberHeadings && count( $matches[3] ) > 1) {
				# the two are different if the line contains a link
				$headline=$numbering . ' ' . $headline;
			}

			# Create the anchor for linking from the TOC to the section
			$anchor = $canonized_headline;
			if($refcount[$headlineCount] > 1 ) {
				$anchor .= '_' . $refcount[$headlineCount];
			}
			if( $doShowToc && ( !isset($wgMaxTocLevel) || $toclevel<$wgMaxTocLevel ) ) {
				$toc .= $sk->tocLine($anchor, $tocline, $numbering, $toclevel);
			}
			if( $showEditLink && ( !$istemplate || $templatetitle !== "" ) ) {
				if ( empty( $head[$headlineCount] ) ) {
					$head[$headlineCount] = '';
				}
				if( $istemplate )
					$head[$headlineCount] .= $sk->editSectionLinkForOther($templatetitle, $templatesection);
				else
					$head[$headlineCount] .= $sk->editSectionLink($this->mTitle, $sectionCount+1, $headline_hint);
			}

			# give headline the correct <h#> tag
			@$head[$headlineCount] .= "<a name=\"$anchor\"></a><h".$level.$matches[2][$headlineCount] .$headline.'</h'.$level.'>';

			$headlineCount++;
			if( !$istemplate )
				$sectionCount++;
		}

		if( $doShowToc ) {
			$toc .= $sk->tocUnindent( $toclevel - 1 );
			$toc = $sk->tocList( $toc );
		}

		# split up and insert constructed headlines

		$blocks = preg_split( '/<H[1-6].*?' . '>.*?<\/H[1-6]>/i', $text );
		$i = 0;

		foreach( $blocks as $block ) {
			if( $showEditLink && $headlineCount > 0 && $i == 0 && $block != "\n" ) {
				# This is the [edit] link that appears for the top block of text when
				# section editing is enabled

				# Disabled because it broke block formatting
				# For example, a bullet point in the top line
				# $full .= $sk->editSectionLink(0);
			}
			$full .= $block;
			if( $doShowToc && !$i && $isMain && !$forceTocHere) {
			# Top anchor now in skin
				$full = $full.$toc;
			}

			if( !empty( $head[$i] ) ) {
				$full .= $head[$i];
			}
			$i++;
		}
		if($forceTocHere) {
			$mw =& MagicWord::get( MAG_TOC );
			return $mw->replace( $toc, $full );
		} else {
			return $full;
		}
	}

	/**
	 * Replace special strings like "ISBN xxx" and "RFC xxx" with
	 * magic external links.
	 *
	 * @access private
	 */
	function &doMagicLinks( &$text ) {
		$text = $this->magicISBN( $text );
		$text = $this->magicRFC( $text, 'RFC ', 'rfcurl' );
		$text = $this->magicRFC( $text, 'PMID ', 'pubmedurl' );
		return $text;
	}

	/**
	 * Parse headers and return html
	 *
	 * @access private
	 */
	function doHeadings( $text ) {
		$fname = 'Parser::doHeadings';
		wfProfileIn( $fname );
		for ( $i = 6; $i >= 1; --$i ) {
			$h = str_repeat( '=', $i );
			$text = preg_replace( "/^{$h}(.+){$h}(\\s|$)/m",
			  "<h{$i}>\\1</h{$i}>\\2", $text );
		}
		wfProfileOut( $fname );
		return $text;
	}

	/**
	 * Replace single quotes with HTML markup
	 * @access private
	 * @return string the altered text
	 */
	function doAllQuotes( $text ) {
		$fname = 'Parser::doAllQuotes';
		wfProfileIn( $fname );
		$outtext = '';
		$lines = explode( "\n", $text );
		foreach ( $lines as $line ) {
			$outtext .= $this->doQuotes ( $line ) . "\n";
		}
		$outtext = substr($outtext, 0,-1);
		wfProfileOut( $fname );
		return $outtext;
	}

	/**
	 * Helper function for doAllQuotes()
	 * @access private
	 */
	function doQuotes( $text ) {
		$arr = preg_split( "/(''+)/", $text, -1, PREG_SPLIT_DELIM_CAPTURE );
		if ( count( $arr ) == 1 )
			return $text;
		else
		{
			# First, do some preliminary work. This may shift some apostrophes from
			# being mark-up to being text. It also counts the number of occurrences
			# of bold and italics mark-ups.
			$i = 0;
			$numbold = 0;
			$numitalics = 0;
			foreach ( $arr as $r )
			{
				if ( ( $i % 2 ) == 1 )
				{
					# If there are ever four apostrophes, assume the first is supposed to
					# be text, and the remaining three constitute mark-up for bold text.
					if ( strlen( $arr[$i] ) == 4 )
					{
						$arr[$i-1] .= "'";
						$arr[$i] = "'''";
					}
					# If there are more than 5 apostrophes in a row, assume they're all
					# text except for the last 5.
					else if ( strlen( $arr[$i] ) > 5 )
					{
						$arr[$i-1] .= str_repeat( "'", strlen( $arr[$i] ) - 5 );
						$arr[$i] = "'''''";
					}
					# Count the number of occurrences of bold and italics mark-ups.
					# We are not counting sequences of five apostrophes.
					if ( strlen( $arr[$i] ) == 2 ) $numitalics++;  else
					if ( strlen( $arr[$i] ) == 3 ) $numbold++;     else
					if ( strlen( $arr[$i] ) == 5 ) { $numitalics++; $numbold++; }
				}
				$i++;
			}

			# If there is an odd number of both bold and italics, it is likely
			# that one of the bold ones was meant to be an apostrophe followed
			# by italics. Which one we cannot know for certain, but it is more
			# likely to be one that has a single-letter word before it.
			if ( ( $numbold % 2 == 1 ) && ( $numitalics % 2 == 1 ) )
			{
				$i = 0;
				$firstsingleletterword = -1;
				$firstmultiletterword = -1;
				$firstspace = -1;
				foreach ( $arr as $r )
				{
					if ( ( $i % 2 == 1 ) and ( strlen( $r ) == 3 ) )
					{
						$x1 = substr ($arr[$i-1], -1);
						$x2 = substr ($arr[$i-1], -2, 1);
						if ($x1 == ' ') {
							if ($firstspace == -1) $firstspace = $i;
						} else if ($x2 == ' ') {
							if ($firstsingleletterword == -1) $firstsingleletterword = $i;
						} else {
							if ($firstmultiletterword == -1) $firstmultiletterword = $i;
						}
					}
					$i++;
				}

				# If there is a single-letter word, use it!
				if ($firstsingleletterword > -1)
				{
					$arr [ $firstsingleletterword ] = "''";
					$arr [ $firstsingleletterword-1 ] .= "'";
				}
				# If not, but there's a multi-letter word, use that one.
				else if ($firstmultiletterword > -1)
				{
					$arr [ $firstmultiletterword ] = "''";
					$arr [ $firstmultiletterword-1 ] .= "'";
				}
				# ... otherwise use the first one that has neither.
				# (notice that it is possible for all three to be -1 if, for example,
				# there is only one pentuple-apostrophe in the line)
				else if ($firstspace > -1)
				{
					$arr [ $firstspace ] = "''";
					$arr [ $firstspace-1 ] .= "'";
				}
			}

			# Now let's actually convert our apostrophic mush to HTML!
			$output = '';
			$buffer = '';
			$state = '';
			$i = 0;
			foreach ($arr as $r)
			{
				if (($i % 2) == 0)
				{
					if ($state == 'both')
						$buffer .= $r;
					else
						$output .= $r;
				}
				else
				{
					if (strlen ($r) == 2)
					{
						if ($state == 'i')
						{ $output .= '</i>'; $state = ''; }
						else if ($state == 'bi')
						{ $output .= '</i>'; $state = 'b'; }
						else if ($state == 'ib')
						{ $output .= '</b></i><b>'; $state = 'b'; }
						else if ($state == 'both')
						{ $output .= '<b><i>'.$buffer.'</i>'; $state = 'b'; }
						else # $state can be 'b' or ''
						{ $output .= '<i>'; $state .= 'i'; }
					}
					else if (strlen ($r) == 3)
					{
						if ($state == 'b')
						{ $output .= '</b>'; $state = ''; }
						else if ($state == 'bi')
						{ $output .= '</i></b><i>'; $state = 'i'; }
						else if ($state == 'ib')
						{ $output .= '</b>'; $state = 'i'; }
						else if ($state == 'both')
						{ $output .= '<i><b>'.$buffer.'</b>'; $state = 'i'; }
						else # $state can be 'i' or ''
						{ $output .= '<b>'; $state .= 'b'; }
					}
					else if (strlen ($r) == 5)
					{
						if ($state == 'b')
						{ $output .= '</b><i>'; $state = 'i'; }
						else if ($state == 'i')
						{ $output .= '</i><b>'; $state = 'b'; }
						else if ($state == 'bi')
						{ $output .= '</i></b>'; $state = ''; }
						else if ($state == 'ib')
						{ $output .= '</b></i>'; $state = ''; }
						else if ($state == 'both')
						{ $output .= '<i><b>'.$buffer.'</b></i>'; $state = ''; }
						else # ($state == '')
						{ $buffer = ''; $state = 'both'; }
					}
				}
				$i++;
			}
			# Now close all remaining tags.  Notice that the order is important.
			if ($state == 'b' || $state == 'ib')
				$output .= '</b>';
			if ($state == 'i' || $state == 'bi' || $state == 'ib')
				$output .= '</i>';
			if ($state == 'bi')
				$output .= '</b>';
			if ($state == 'both')
				$output .= '<b><i>'.$buffer.'</i></b>';
			return $output;
		}
	}


}

function wfProfileIn() {
	
}
function wfProfileOut() {
	
}
?>