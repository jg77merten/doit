<?php

// ideally one day this can do more than one image... 
// they would be stacked up to crop all at once in 
// Impromptu.. thus returning an array

define("UPLOAD_PATH", $_SERVER['DOCUMENT_ROOT']); // CHANGE THIS FOR YOUR NEEDS!

foreach($_POST['imgcrop'] as $k => $v) {

	$targetPath = UPLOAD_PATH . $v['folder'] . '/';
	$targetFile =  str_replace('//','/',$targetPath) .$v['name'];
	$targetFile2 =  str_replace('//','/',$targetPath) .'thumb_'.$v['name'];
	$src_img=imagecreatefromjpeg($targetFile);
	$dst_img=ImageCreateTrueColor($v['w'], $v['h']);
	
	

	imagecopyresampled($dst_img,$src_img,0,0,$v['x'], $v['y'], $v['w'], $v['h'], $v['w'], $v['h']);
	imagejpeg($dst_img,$targetFile2,100);

//		print_r($targetFile);
//	print_r($src_img);
//	print_r($dst_img);
//	exit();
	
	imagedestroy($dst_img); 
	imagedestroy($src_img);
	
	
	//generate thumb or whatever else you like...
}

echo "1";
