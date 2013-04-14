<?
/**
 * @access public
 */

class Image
{
	/**
	 * Select this User from the database
	 */
	static public function show($id, $size="")
	{
		global $db, $user;
		$id = (int)$id;
		if ($id > 0) {
			$fieldname = "image_med";
			if ($size == "thumb") $fieldname = "image_thumb";
			if ($size == "full") $fieldname = "image_full";

			$sql = "SELECT i.image_type, i.$fieldname as image_data, p.created_by, p.public_flag, l.trace
					FROM tree_image i
					JOIN tree_person p ON i.person_id = p.person_id AND ".actualClause("p")."
					LEFT JOIN app_user_line_person l ON l.person_id = p.person_id AND l.user_id = '$user->id'
					WHERE image_id = '$id'";
			//echo $sql;
			$row = $db->select( $sql );

			$data = $row[0];
			$view = false;
			if ($data["created_by"] == $user->id) $view = true;
			if ($data["trace"] > "") $view = true;
			if ($data["public_flag"] == 1) $view = true;
			if ($view && $data["image_data"] > "") {
				header("Content-type: ".$data["image_type"]);
				echo $data["image_data"];
				return true;
			}
		}
		# Something went wrong and we have no image to display
		# Create a new one and show that
		header("Content-type: image/png");
		$im = imagecreate(100, 100);
		$lightgrey = imagecolorallocate($im, 240, 240, 240);
		$linecolor = imagecolorallocate($im, 50, 0, 0);
		imagefill($im, 0, 0, $lightgrey);
		imageline($im, 0, 0, 99, 99, $linecolor);
		imageline($im, 0, 0, 0, 99, $linecolor);
		imageline($im, 0, 0, 99, 0, $linecolor);
		imageline($im, 99, 0, 0, 99, $linecolor);
		imageline($im, 0, 99, 99, 99, $linecolor);
		imageline($im, 99, 0, 99, 99, $linecolor);
		imagepng($im);
		imagedestroy($im);
		return false;
	}


	/**
	 * Display a photo (by person_id)
	 */
	static public function showByPerson($person_id, $size="thumb", $return_array=false)
	{
		global $db, $user;
		$person_id = (int)$person_id;
		if ($person_id > 0) {
			$fieldname = "image_thumb";
			if ($size == "med") $fieldname = "image_med";
			if ($size == "full") $fieldname = "image_full";

			$sql = "SELECT i.image_type, i.$fieldname as image_data, i.image_order, p.created_by, p.public_flag, l.trace
					FROM tree_image i
					JOIN tree_person p ON i.person_id = p.person_id AND ".actualClause("p")."
					LEFT JOIN app_user_line_person l ON l.person_id = p.person_id AND l.user_id = '$user->id'
					WHERE p.person_id = '$person_id' AND i.$fieldname > '' ORDER BY i.image_order DESC";
			//echo $sql;
			//die();
			$row = $db->select( $sql );
			if (isset($row[0])) {
				$data = $row[0];
				if (isset($row[1]) && $row[1] == 2) $data = $row[1];
			//print_pre($row);
			//die();
	
				$view = false;
				if ($data["created_by"] == $user->id) $view = true;
				if ($data["trace"] > "") $view = true;
				if ($data["public_flag"] == 1) $view = true;
				if ($view && $data["image_data"] > "") {
					if ($return_array) {
						$return["type"] = $data["image_type"];
						$return["image"] = $data["image_data"];
						return $return;
					}
					header("Content-type: ".$data["image_type"]);
					echo $data["image_data"];
					return true;
				}
			}
		}
		# Something went wrong and we have no image to display
		# Create a new one and show that
		if ($return_array) {
			return false;
		}
		header("Content-type: image/png");
		$im = imagecreate(100, 100);
		$lightgrey = imagecolorallocate($im, 240, 240, 240);
		$linecolor = imagecolorallocate($im, 50, 0, 0);
		imagefill($im, 0, 0, $lightgrey);
		imageline($im, 0, 0, 99, 99, $linecolor);
		imageline($im, 0, 0, 0, 99, $linecolor);
		imageline($im, 0, 0, 99, 0, $linecolor);
		imageline($im, 99, 0, 0, 99, $linecolor);
		imageline($im, 0, 99, 99, 99, $linecolor);
		imageline($im, 99, 0, 99, 99, $linecolor);
		imagepng($im);
		imagedestroy($im);
		return false;
	}


	/**
	 * Get image info
	 */
	static public function info($id, $include_images=false)
	{
		global $db, $user;
		$id = (int)$id;
		if (empty($id)) return false;
		if ($include_images) $img = " i.image_full, i.image_med, i.image_thumb,";
		else $img = "";

		$sql = "SELECT i.image_id, i.image_order, i.image_type, i.age_taken, i.image_name, i.description, i.event_id, 
					p.person_id, p.given_name, p.family_name, p.created_by, p.public_flag, l.trace, 
					e.event_date, gc.prompt, $img
					i.update_date, i.updated_by, u.given_name as ugiven_name, u.family_name as ufamily_name
				FROM tree_image i
				LEFT JOIN tree_person p ON i.person_id = p.person_id AND ".actualClause("p")."
				LEFT JOIN tree_event e ON i.event_id = e.event_id AND ".actualClause("e")."
				LEFT JOIN ref_gedcom_codes gc ON e.event_type = gc.gedcom_code AND gc.table_type = 'P'
				LEFT JOIN app_user_line_person l ON l.person_id = p.person_id AND l.user_id = '$user->id'
				LEFT JOIN app_user u ON i.updated_by = u.user_id
				WHERE image_id = '$id'";
		//echo $sql;
		$row = $db->select( $sql );
		//print_pre($row);
		if (!$row) return false;

		$data = $row[0];
		$view = false;
		if ($data["created_by"] == $user->id) $view = true;
		if ($data["trace"] > "") $view = true;
		if ($data["public_flag"] == 1) $view = true;
		if (!$view) return false;
		return $data;
	}

	/**
	 * Select this User from the database
	 */
	static public function delete($id)
	{
		global $db;
		$id = (int)$id;
		$data = Image::info($id);
		if (!is_array($data)) errorMessage("can't delete what you don't have access to");

		$sql = "DELETE FROM tree_image WHERE image_id = '$id'";
		//echo $sql;
		$db->sql_query( $sql );
		redirect("person/".$data["person_id"]);
	}

	/**
	 * Save an image
	 */
	static public function save($data)
	{
		global $db, $user;
		$image_id = (int)$data['image_id'];

		$fields = "update_date = Now(), updated_by = '$user->id', update_process = 'Image.class.php'";

		# process the image first
		$tmp_full = $_FILES['userfile']['tmp_name'];
		$valid_image = false;
		if(is_uploaded_file($tmp_full)) {
			//$mime_type = mime_content_type($tmp_full);
			$mime_type = $_FILES['userfile']['type'];
			if (empty($mime_type))
				$mime_type = returnMIMEType($_FILES['userfile']['name']);
			//echo $mime_type;
			//die();
			if ($mime_type == "application/pdf") {
				#This file is a PDF
				$fsize = filesize($tmp_full);
				if ($fsize > 200000) return false;
				$image_full = addslashes(file_get_contents($tmp_full));
				$fields .= ", image_type='$mime_type', image_size='$fsize', image_full='$image_full' ";
				$valid_image = true;
			} else {
				# Process all images
				$size = getimagesize($tmp_full);
				if ($size[2] == 6) {
					# Convert a bitmap into a JPEG
					$res = ImageCreateFromBMP($tmp_full);
					$result = imagejpeg($res, $tmp_full);
					if (!$result) return false;
					$size = getimagesize($tmp_full);
				}
				//if ($user->id==1) print_pre($size);
				if ($size[2] > 0 && $size[2] < 4) {
					# it must be a JPG, GIF or PNG
					$fsize = filesize($tmp_full);
	
					# resize the regular and thumbnail from the full
					$tmp_thumb = resize_image($tmp_full, 100, 100, 100);
					$image_thumb = addslashes(file_get_contents($tmp_thumb));

					$tmp_med = resize_image($tmp_full, 600, 400);
					$image_med = addslashes(file_get_contents($tmp_med));

					$tmp_full = resize_image($tmp_full, 1200, 1200);
					$image_full = addslashes(file_get_contents($tmp_full));

					# Delete the files from the temp dir now
					@unlink($tmp_full);
					@unlink($tmp_med);
					@unlink($tmp_thumb);

					# create sql statement
					$fields .= ", image_type='".$size["mime"]."', image_size='$fsize', image_full='$image_full', image_med='$image_med', image_thumb='$image_thumb' ";
					//echo "Thumb: $image_thumb";
					$valid_image = true;
				} else {
					return false;
				}
			}
		}

		if ($data["person_id"] > 0) 
			$fields .= ", person_id = '".(int)$data["person_id"]."'";
		if ($data["event_id"] > 0) 
			$fields .= ", event_id = '".(int)$data["event_id"]."'";
		if ($data["age_taken"] > 0) 
			$fields .= ", age_taken = '".(int)$data["age_taken"]."'";
		if ($data["description"]) 
			$fields .= ", description = '".fixTick($data["description"])."'";
		if ($data["image_order"] > 0) 
			$fields .= ", image_order = '".(int)$data["image_order"]."'";

		if ($image_id > 0) {
			# Update
			$sql = "UPDATE tree_image SET $fields WHERE image_id = '$image_id'";
			$db->sql_query($sql);
			return $image_id;
		}

		# you must have a valid image before inserting
		if (!$valid_image) return false;
		# Insert
		$sql = "INSERT INTO tree_image SET creation_date = Now(), created_by = $user->id, $fields ";

		$db->sql_query($sql);
		return $db->sql_nextid();
	}
	/**
	 * Save thumbnail values 
	 */
	static public function saveThumb($id, $x, $y, $w)
	{
		$thumbsize = 100;

		global $db;
		$data = Image::info($id, true);
		if (!is_array($data)) return false;

		//echo strlen($data[0]["image_full"]);

		$tmpImg = imagecreatefromstring($data["image_full"]);
		$x1 = imagesX($tmpImg);
		$y1 = imagesY($tmpImg);
		
		$tmpImg = imagecreatefromstring($data["image_med"]);
		$orig = $tmpImg;
		$x2 = imagesX($tmpImg);
		$y2 = imagesY($tmpImg);
		unset($tmpImg);
		$f = $x2 / $w;

		$thumb = imagecreatetruecolor($thumbsize, $thumbsize);
		$status = imagecopyresized($thumb, $orig, 0, 0, $x*$f, $y*$f, $thumbsize, $thumbsize, $thumbsize*$f, $thumbsize*$f);

		ob_start();
		switch($data["image_type"]) {
			case "image/gif":
				imagegif($thumb);
				break;
			case "image/png":
				imagepng($thumb, null, 100);
				break;
			default:
				imagejpeg($thumb, null, 100);
		}
		$thumb_sql = ob_get_contents();
		ob_end_clean();

		$sql = "UPDATE tree_image SET image_thumb = '".addslashes($thumb_sql)."' WHERE image_id = $id";
		$db->sql_query($sql);
	}
}

function ImageCreateFromBMP($filename)
{
	//Ouverture du fichier en mode binaire
	if (! $f1 = fopen($filename,"rb")) return FALSE;


	//1 : 
	$FILE = unpack("vfile_type/Vfile_size/Vreserved/Vbitmap_offset", fread($f1,14));
	if ($FILE['file_type'] != 19778) return FALSE;
	
	//2 : 
	$BMP = unpack('Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel'.
		 '/Vcompression/Vsize_bitmap/Vhoriz_resolution'.
		 '/Vvert_resolution/Vcolors_used/Vcolors_important', fread($f1,40));
	$BMP['colors'] = pow(2,$BMP['bits_per_pixel']);
	if ($BMP['size_bitmap'] == 0) $BMP['size_bitmap'] = $FILE['file_size'] - $FILE['bitmap_offset'];
	$BMP['bytes_per_pixel'] = $BMP['bits_per_pixel']/8;
	$BMP['bytes_per_pixel2'] = ceil($BMP['bytes_per_pixel']);
	$BMP['decal'] = ($BMP['width']*$BMP['bytes_per_pixel']/4);
	$BMP['decal'] -= floor($BMP['width']*$BMP['bytes_per_pixel']/4);
	$BMP['decal'] = 4-(4*$BMP['decal']);
	if ($BMP['decal'] == 4) $BMP['decal'] = 0;
	
	//3 :
	$PALETTE = array();
	if ($BMP['colors'] < 16777216)
	{
		$PALETTE = unpack('V'.$BMP['colors'], fread($f1,$BMP['colors']*4));
	}
	
	//4 : 
	$IMG = fread($f1,$BMP['size_bitmap']);

	if (strlen($IMG) < $BMP['size_bitmap']) {
		echo "Failed to read the entire bitmap";
		return false;
	}
	$VIDE = chr(0);
	
	$res = imagecreatetruecolor($BMP['width'],$BMP['height']);
	$P = 0;
	$Y = $BMP['height']-1;
	while ($Y >= 0)
	{
		$X=0;
		while ($X < $BMP['width'])
		{
			if ($BMP['bits_per_pixel'] == 24)
				$COLOR = unpack("V",substr($IMG,$P,3).$VIDE);
			elseif ($BMP['bits_per_pixel'] == 16)
			{  
				$COLOR = unpack("n",substr($IMG,$P,2));
				$COLOR[1] = $PALETTE[$COLOR[1]+1];
			}
			elseif ($BMP['bits_per_pixel'] == 8)
			{  
				$COLOR = unpack("n",$VIDE.substr($IMG,$P,1));
				$COLOR[1] = $PALETTE[$COLOR[1]+1];
			}
			elseif ($BMP['bits_per_pixel'] == 4)
			{
				$COLOR = unpack("n",$VIDE.substr($IMG,floor($P),1));
				if (($P*2)%2 == 0) $COLOR[1] = ($COLOR[1] >> 4) ; else $COLOR[1] = ($COLOR[1] & 0x0F);
				$COLOR[1] = $PALETTE[$COLOR[1]+1];
			}
			elseif ($BMP['bits_per_pixel'] == 1)
			{
				$COLOR = unpack("n",$VIDE.substr($IMG,floor($P),1));
				if     (($P*8)%8 == 0) $COLOR[1] =  $COLOR[1]        >>7;
				elseif (($P*8)%8 == 1) $COLOR[1] = ($COLOR[1] & 0x40)>>6;
				elseif (($P*8)%8 == 2) $COLOR[1] = ($COLOR[1] & 0x20)>>5;
				elseif (($P*8)%8 == 3) $COLOR[1] = ($COLOR[1] & 0x10)>>4;
				elseif (($P*8)%8 == 4) $COLOR[1] = ($COLOR[1] & 0x8)>>3;
				elseif (($P*8)%8 == 5) $COLOR[1] = ($COLOR[1] & 0x4)>>2;
				elseif (($P*8)%8 == 6) $COLOR[1] = ($COLOR[1] & 0x2)>>1;
				elseif (($P*8)%8 == 7) $COLOR[1] = ($COLOR[1] & 0x1);
				$COLOR[1] = $PALETTE[$COLOR[1]+1];
			}
			else
				return FALSE;
			imagesetpixel($res,$X,$Y,$COLOR[1]);
			$X++;
			$P += $BMP['bytes_per_pixel'];
		}
		$Y--;
		$P+=$BMP['decal'];
	}
	
	//Fermeture du fichier
	fclose($f1);
	
	return $res;
}

function resize_image($filename, $width, $height, $quality=75) {
	$newfile = "/tmp/". md5("md5", $filename.$width.$height.$quality).".img";

	// Get new dimensions
	list($width_orig, $height_orig, $type) = getimagesize($filename);

	# if the new photo size is smaller, then just return the original
	if ($width_orig < $width && $height_orig < $height) return $filename;

	$ratio_orig =$width_orig / $height_orig;

	if ($width/$height > $ratio_orig) {
	   $width = round($height*$ratio_orig);
	} else {
	   $height = round($width/$ratio_orig);
	}

	//echo "type: $type w: $width h: $height wo: $width_orig ho:$height_orig <br>";
	//die();

	// Resample
	$image_new = imagecreatetruecolor($width, $height);
	#1 = GIF, 2 = JPG, 3 = PNG
	if ($type == 1)	{
		$image_old = imagecreatefromjpeg($filename);
		imagecopyresampled($image_new, $image_old, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
		imagegif($image_new, $newfile);
	}
	if ($type == 2)	{
		$image_old = imagecreatefromjpeg($filename);
		imagecopyresampled($image_new, $image_old, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
		imagejpeg($image_new, $newfile, $quality);
		//header('Content-type: image/jpeg');
		//imagejpeg($image, null, $quality);
		//die();
	}
	if ($type == 3)	{
		$image_old = imagecreatefromjpeg($filename);
		imagecopyresampled($image_new, $image_old, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
		imagepng($image_new, $newfile);
	}
	if ($image_new == null) errorMessage("function resize_image: cannot read file type $type from $filename");

	imagedestroy($image_old);
	imagedestroy($image_new);
	return $newfile;
}

?>
