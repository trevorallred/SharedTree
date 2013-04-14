{include file="fb_header.tpl"}

<form method="get" id="search_form">
<input type="hidden" name="template" value="fb_search" />
<input type="hidden" name="range" value="15" />
<input type="hidden" name="adbc" value="1" />
<label>Family name:</label>
	<input type="text" name="family_name" size=20 /><br />
<label>Given name:</label>
	<input type="text" name="given_name" size=20 /><br />
<label>Approximate birth year:</label>
	<input type="text" name="birth_year" size=4 /><br />
<input type="submit" name="search" value="Search" clickrewriteurl='http://www.sharedtree.com/search.php?template=fb_search' clickrewriteform='search_form' clickrewriteid='search_results' clicktoshow='spinner' clicktohide='search_results2'/>
</form>
<div id="search_results">
<img src="http://www.sharedtree.com/img/spinner_orange.gif" id="spinner" style="display:none;"/>
</div>

{include file="fb_footer.tpl"}
