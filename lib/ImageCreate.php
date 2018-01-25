<?php
	
class ImageCreate{

	function make_thumb($img_name,$filename,$old_w,$old_h,$new_w,$new_h){
		
		if($old_w < $new_w){$new_w = $old_w;}
		if($old_h < $new_h){$new_h = $old_h;}
	
		$ext=$this->getExtension($img_name);
		if(!strcmp("jpg",$ext) || !strcmp("jpeg",$ext))
		$src_img=imagecreatefromjpeg($img_name);
		
		if(!strcmp("png",$ext))
		$src_img=imagecreatefrompng($img_name);
		
		
		
		if(!strcmp("gif",$ext))
		$src_img=imagecreatefromgif($img_name);		
		
		
		$old_x=imageSX($src_img);
		$old_y=imageSY($src_img);
		
		@$ratio1=$old_x/$new_w;
		$ratio2=$old_y/$new_h;
		if($ratio1>$ratio2) {
			$thumb_w=$new_w;
			$thumb_h=$old_y/$ratio1;
		}else {
			$thumb_h=$new_h;
			$thumb_w=$old_x/$ratio2;
		}
		
		
		$dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);
		
		
		imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
		
		
		if(!strcmp("png",$ext)){
			imagepng($dst_img,$filename);
		}
		if(!strcmp("jpg",$ext)){
			imagejpeg($dst_img,$filename);
		}
		if(!strcmp("jpeg",$ext)){
			imagejpeg($dst_img,$filename);
		}
		if(!strcmp("gif",$ext)){
			imagegif($dst_img,$filename);
		}
	
		$ee="";
		
		
			//destroys source and destination images.
			return $ee;
			//imagedestroy($dst_img);
			//imagedestroy($src_img);
	}
	
	// This function reads the extension of the file.
	// It is used to determine if the file is an image by checking the extension.
	function getExtension($str) {
		$i = strrpos($str,".");
		if (!$i) { return ""; }
		$l = strlen($str) - $i;
		$ext = substr($str,$i+1,$l);
		return strtolower($ext);
	}	
	
	
	function rotate($img_name,$rotated_image,$direction){
		
		// The file you are rotating
		$image = $img_name;
		$extParts=explode(".",$image);
		$ext=end($extParts);
		$filename=$rotated_image;
		//How many degrees you wish to rotate
		$degrees = 0;
		if($direction=='R'){
			$degrees = 90;
		}
		if($direction=='L'){
			$degrees = -90;
		}
		
		if($ext=="jpg" || $ext== "jpeg" || $ext=="JPG" || $ext== "JPEG"){
			$source = imagecreatefromjpeg($image) ;
			$rotate = imagerotate($source, $degrees, 0) ;
			imagejpeg($rotate,$filename) ;
		}
		
		
		if($ext=="GIF" || $ext== "gif"){
			$source = imagecreatefromgif($image) ;
			$rotate = imagerotate($source, $degrees, 0) ;
			imagegif($rotate,$filename) ;
			
		}
		
		if($ext=="png" || $ext== "PNG"){
			$source = imagecreatefrompng($image) ;
			$rotate = imagerotate($source, $degrees, 0) ;
			imagepng($rotate,$filename) ;
			
		}
		return $rotate;	
		
	}
	
	
	
}
	
?>