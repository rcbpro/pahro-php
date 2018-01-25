<?php

function delete_file($dirname,$filename,$from_folder){
	if(is_file($dirname)){
		$_SESSION['success_message'] = 'Deleted file : '.$filename;
		if($from_folder != true){//writes to log
			$loc = str_replace('uploads/','',$_GET['where']);
			write_to_log("File \"{$filename}\" was deleted in File Manager (Location : $loc)");
		}
		return @unlink($dirname);
	}
	elseif(is_dir($dirname)){
		$scan = glob(rtrim($dirname,'/').'/*');
		foreach($scan as $index=>$path){
			delete_file($path,"",true);
		}
		$_SESSION['success_message'] = 'Deleted folder : '.$filename;
		
		/*
		if(trim($filename) != ''){//writes to log
			$loc = str_replace('uploads/','',$_GET['where']);
			write_to_log("Folder \"{$filename}\" was deleted in File Manager (Location : $loc)");
		}
		*/
		//return @rmdir($dirname);
//	}
}





function newGUID() {
		
	$rawid = strtoupper ( md5 ( uniqid ( rand (), true ) ) );
	$workid = $rawid;
	
	// hopefully conform to the spec, mark this as a �random� type
	// lets handle the version byte as a number
	$byte = hexdec ( substr ( $workid, 12, 2 ) );
	$byte = $byte & hexdec ( '0f' );
	$byte = $byte | hexdec ( '40' );
	$workid = substr_replace ( $workid, strtoupper ( dechex ( $byte ) ), 12, 2 );
	
	$byte = hexdec ( substr ( $workid, 16, 2 ) );
	$byte = $byte & hexdec ( '3f' );
	$byte = $byte | hexdec ( '80' );
	$workid = substr_replace ( $workid, strtoupper ( dechex ( $byte ) ), 16, 2 );
	
	// build a human readable version
	$rid = substr ( $rawid, 0, 8 ) . '-' . substr ( $rawid, 8, 4 ) . '-' . substr ( $rawid, 12, 4 ) . '-' . substr ( $rawid, 16, 4 ) . '-' . substr ( $rawid, 20, 12 );
	
	// build a human readable version
	$wid = substr ( $workid, 0, 8 ) . '-' . substr ( $workid, 8, 4 ) . '-' . substr ( $workid, 12, 4 ) . '-' . substr ( $workid, 16, 4 ) . '-' . substr ( $workid, 20, 12 );
	
	return strtolower($rid);
}



echo newGUID();










//delete_file($_SERVER['DOCUMENT_ROOT'].'/file-manager/uploads/');
//rename('user-uploads','user-uploads');

?>
