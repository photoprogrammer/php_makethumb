<?php


function GD2_make_all($thumbnail_width,$thumbnail_height,$src_name,$dst_name, $crop_flag, $is_mask) { 
	list($width_orig, $height_orig) = @getimagesize($src_name); 
	$ratio_orig = $width_orig/$height_orig;
	$ratio_thumbnail = $thumbnail_width/$thumbnail_height;

	if ($width_orig>$thumbnail_width || $height_orig> $thumbnail_height) { 
		if(!$crop_flag){
			if ($ratio_orig>$ratio_thumbnail) { 
							$new_height=ceil(($height_orig*$thumbnail_width)/$width_orig); 
							$new_width=$thumbnail_width; 
			} else { 
							$new_width=ceil(($width_orig*$thumbnail_height)/$height_orig); 
							$new_height=$thumbnail_height; 
			} 
		} else {
			if ($thumbnail_width/$thumbnail_height > $ratio_orig) {
				$new_height = $thumbnail_width/$ratio_orig;
				$new_width = $thumbnail_width;
			} else {
				$new_width = $thumbnail_height*$ratio_orig;
				$new_height = $thumbnail_height;
			}
		}
	} else { 
		$new_height=$height_orig; 
		$new_width=$width_orig; 
	} 

	
	if ($sx>$max_x || $sy>$max_y) { 
			if ($sx<$sy) { 
							$thumb_y=ceil(($sy*$max_x)/$sx); 
							$thumb_x=$max_x; 
			} else { 
							$thumb_x=ceil(($sx*$max_y)/$sy); 
							$thumb_y=$max_y; 
			} 
	} else { 
			$thumb_y=$sy; 
			$thumb_x=$sx; 
	} 
	
	
	
	$x_mid = $new_width/2;  //horizontal middle
	$y_mid = $new_height/2; //vertical middle
	
	$_dq_tempFile=basename($src_name);                               
	$_dq_tempFile = $dst_name;
	
	$ext = pathinfo($src_name);
	switch (strtolower($ext['extension'])){
	case "gif":
		$src_img=ImageCreateFromgif($src_name);
		break;
	case "jpg":
		$src_img=ImageCreateFromjpeg($src_name); 
		break;
	case "png":
		$src_img=ImageCreateFrompng($src_name);
		break;
	}
	
	if($crop_flag){
		$process = imagecreatetruecolor(round($new_width), round($new_height)); 
		imagecopyresampled($process, $src_img, 0, 0, 0, 0, $new_width, $new_height, $width_orig, $height_orig);
		$dst_img=ImageCreateTrueColor($thumbnail_width, $thumbnail_height); 
		ImageCopyResampled($dst_img,$process, 0, 0, ($x_mid-($thumbnail_width/2)), ($y_mid-($thumbnail_height/2)), $thumbnail_width, $thumbnail_height, $thumbnail_width, $thumbnail_height);
	} else {
		$dst_img = imagecreatetruecolor($new_width, $new_height);
		ImageCopyResampled($dst_img,$src_img,0,0,0,0,$new_width,$new_height,$width_orig,$height_orig); 
	}
	if($is_mask){
		$mask = imagecreatefrompng("list_mask.png");
		imagecopyresampled($dst_img,$mask,0,0,0,0,$new_width,$new_height,$new_width,$new_height); 
	}
	
	switch (strtolower($ext['extension'])){
	case "gif":
		Imagegif($dst_img,$_dq_tempFile,100); 
		break;
	case "jpg":
		Imagejpeg($dst_img,$_dq_tempFile,100);  
		break;
	case "png":
		Imagepng($dst_img,$_dq_tempFile,9); 
		break;
	}
	ImageDestroy($dst_img); 
	ImageDestroy($src_img); 
} 

?>
