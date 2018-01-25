<?php
if(isset($_POST['msid'])){
	session_id($_POST['msid']);
	if(!session_start()){
		session_start();
	}
}				

if (!empty($_FILES)) {
	
	if($_POST['type']=='case'){
		
		$file_path = $_SERVER['DOCUMENT_ROOT'];
		
		$tempFile = $_FILES['Filedata']['tmp_name'];
		$case_name = $_FILES['Filedata']['name'];
		$unique_case = 'f'.rand(1000,9999).'_'.$case_name;
		
		$targetPath = $file_path . $_REQUEST['folder'] . '/';
		$targetFile =  str_replace('//','/',$targetPath) . $unique_case;
		
		if(move_uploaded_file($tempFile,$targetFile)){
			//gets these right after uploaded to display
			$_SESSION['case']['id'] = 'c'.rand(1,999999);
			$_SESSION['case']['name'] = $unique_case;
			$_SESSION['case']['http_path_case']   = 'http://'.$_SERVER['HTTP_HOST'].'/user-uploads/temp/'.$unique_case;			
			$_SESSION['case']['file_path_case'] = $_SERVER['DOCUMENT_ROOT'].'/user-uploads/temp/'.$unique_case;	
			//
			
			
			//use to db
			$ary_case = array(
								'id'=>$_SESSION['case']['id'],
								'name'=>$unique_case,
								'http_path_case'=>'http://'.$_SERVER['HTTP_HOST'].'/user-uploads/temp/'.$unique_case,
								'file_path_case'=>$_SERVER['DOCUMENT_ROOT'].'/user-uploads/temp/'.$unique_case
							 );
			
			$_SESSION['case_main']['template_name'][$_SESSION['case']['id']] = $ary_case;
		
			echo "1";
		}
	}
	
	
	if($_POST['type']=='client'){
		
		$file_path = $_SERVER['DOCUMENT_ROOT'];
		
		$tempFile = $_FILES['Filedata']['tmp_name'];
		$client_name = $_FILES['Filedata']['name'];
		$unique_client = 'f'.rand(1000,9999).'_'.$client_name;
		
		$targetPath = $file_path . $_REQUEST['folder'] . '/';
		$targetFile =  str_replace('//','/',$targetPath) . $unique_client;
		
		if(move_uploaded_file($tempFile,$targetFile)){
			//gets these right after uploaded to display
			$_SESSION['client']['id'] = 'c'.rand(1,999999);
			$_SESSION['client']['name'] = $unique_client;
			$_SESSION['client']['http_path_case']   = 'http://'.$_SERVER['HTTP_HOST'].'/user-uploads/temp/'.$unique_client;
			$_SESSION['client']['file_path_case'] = $_SERVER['DOCUMENT_ROOT'].'/user-uploads/temp/'.$unique_client;
			//
			
			
			//use to db
			$ary_client = array(
								'id'=>$_SESSION['client']['id'],
								'name'=>$unique_client,
								'http_path_client'=>'http://'.$_SERVER['HTTP_HOST'].'/user-uploads/temp/'.$unique_client,
								'file_path_client'=>$_SERVER['DOCUMENT_ROOT'].'/user-uploads/temp/'.$unique_client
							 );
			
			$_SESSION['client_main']['template_name'][$_SESSION['client']['id']] = $ary_client;
		
			echo "1";
		}
	}
	
	
	if($_POST['type']=='counter-party'){
		
		$file_path = $_SERVER['DOCUMENT_ROOT'];
		
		$tempFile = $_FILES['Filedata']['tmp_name'];
		$counter_party_name = $_FILES['Filedata']['name'];
		$unique_counter_party = 'f'.rand(1000,9999).'_'.$counter_party_name;
		
		$targetPath = $file_path . $_REQUEST['folder'] . '/';
		$targetFile =  str_replace('//','/',$targetPath) . $unique_counter_party;
		
		if(move_uploaded_file($tempFile,$targetFile)){
			//gets these right after uploaded to display
			$_SESSION['counter_party']['id'] = 'c'.rand(1,999999);
			$_SESSION['counter_party']['name'] = $unique_counter_party;
			$_SESSION['counter_party']['http_path_counter_party']   = 'http://'.$_SERVER['HTTP_HOST'].'/user-uploads/temp/'.$unique_counter_party;
			$_SESSION['counter_party']['file_path_counter_party'] = $_SERVER['DOCUMENT_ROOT'].'/user-uploads/temp/'.$unique_counter_party;
			//
			
			
			//use to db
			$ary_counter_party = array(
								'id'=>$_SESSION['counter_party']['id'],
								'name'=>$unique_counter_party,
								'http_path_counter_party'=>'http://'.$_SERVER['HTTP_HOST'].'/user-uploads/temp/'.$unique_counter_party,
								'file_path_counter_party'=>$_SERVER['DOCUMENT_ROOT'].'/user-uploads/temp/'.$unique_counter_party
							 );
			
			$_SESSION['counter_party_main']['template_name'][$_SESSION['counter_party']['id']] = $ary_counter_party;
		
			echo "1";
		}
	}
	
	//file manager mulitiple uploader
	if($_POST['type'] == 'file-fm'){
		$file_path = $_SERVER['DOCUMENT_ROOT'];
		
		$tempFile = $_FILES['Filedata']['tmp_name'];
		$fm_file_name = $_FILES['Filedata']['name'];
		$where = $_REQUEST['folder'];
		
		$ext = end(explode('.', $fm_file_name));
		$fm_unique_file_name = newGUID().'.'.$ext;
		//$unique_name_with_ext = newGUID().'.'.$ext;

		$targetPath = $file_path . '/file-manager/' . $where . '/';
		$targetFile = str_replace('//','/',$targetPath) . $fm_unique_file_name;
		
		if(move_uploaded_file($tempFile,$targetFile)){
			
			/////////////////
			$host = 'localhost'; $user = 'tagos'; $pwd = '175r1LaNkA'; $db = 'pahro';
			$con = mysql_connect($host,$user,$pwd);
			mysql_select_db($db,$con);
			
			$fm_file_name = mysql_real_escape_string($fm_file_name);
			$fm_unique_file_name = mysql_real_escape_string($fm_unique_file_name);
			$path = mysql_real_escape_string($where);
			$time = time();

			mysql_query("INSERT INTO pahro__file_manager (type, owned_country_id, unique_name, real_name, path, date) VALUES ('file', ".$_SESSION['curr_country']['country_code'].", '$fm_unique_file_name', '$fm_file_name','$path', '$time')",$con);

			$uid = $_SESSION['logged_user']['id'];
			$f_path = str_replace('/uploads/','',$path);
			$desc = "New file \"{$fm_file_name}\" was uploaded in File Manager (Location : $f_path)";
			
			$date = date("Y-m-d-H:i:s");
			
			mysql_query("INSERT INTO pahro__log (user_id , action_type_desc, date_time) VALUES ('$uid','$desc', '$date')",$con);
			$_SESSION['er'] = mysql_error();
			//write_to_log("New file \"{$fm_file_name}\" was $action in File Manager (Location : $loc)");

			
			
			//////////////////

			echo "1";
		}
	}
	
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

?>
