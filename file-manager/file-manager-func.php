<?php
$db_settings = new PahroSettings();
$settings = $db_settings->db_settings;
$con = mysql_connect($settings['host'], $settings['username'], $settings['password']);
mysql_select_db($settings['database'], $con);

#Function create a new folder
function create_folder ($getwhere,$folder_name) {

	global $folder_last;
	global $folder_last_two;
	global $chars;

	$folder_name = stripslashes(str_replace("'","$$$$$",$folder_name));
	if(is_valid_name($folder_name)){	
		if(isset($folder_name) && trim($folder_name) != ''){
			
			if(@mkdir(ROOT.$folder_name)){
				if($folder_last == 'Legislation' && $folder_last_two == 'uploads'){
					@mkdir(ROOT.$folder_name.'/Legislation');
					@mkdir(ROOT.$folder_name.'/Jurisprudence');
					@mkdir(ROOT.$folder_name.'/Doctrine');
					@mkdir(ROOT.$folder_name.'/Customary Law');
				}
				$_SESSION['success_message'] = "Folder successfully created!";
				
				$loc = str_replace('uploads/','',$getwhere);			
				
				/* rcbpro */								
				$unique_name = newGUID();
				
				insert_file_to_db($folder_name, $unique_name, $getwhere, $_SESSION['curr_country']['country_code'], 'folder');
				write_to_log("New folder \"{$_POST['txt-create-folder']}\" was created in File Manager (Location : $loc)");
				/* rcbpro */				
				
				
			}
			elseif(file_exists(ROOT.trim($_POST['txt-create-folder']))){
				$_SESSION['warning_message'] = 'Folder already exist!';
			}
			else{
				$_SESSION['warning_message'] = 'Sorry, something went wrong!';
			}
			
		}
		else{
			$_SESSION['warning_message'] = 'No folder was created!';
		}
	}
	else{
		$chars_list = '';
		foreach($chars as $char){
			$chars_list .= $char.' ';
		}
		$_SESSION['warning_message'] = 'Folder name can not contain '.$chars_list;
	}
}

 //replaced with ajax uploader
function upload_file($getwhere){
	
	if($_POST['upload_file'] == 'upload_file'){
		if($_FILES['file']['error'] == 8){
			$_SESSION['warning_message'] .= "File upload stopped by extension!!!";
		}

		if($_FILES['file']['error'] == 7){
			$_SESSION['warning_message'] .= "Failed to write file to disk!!!";
		}
		if($_FILES['file']['error'] == 6){
			$_SESSION['warning_message'] .= "Missing a temporary folder!!!";
		}
		if($_FILES['file']['error'] == 4){
			$_SESSION['warning_message'] .= "No file was uploaded!!!";
		}
		if($_FILES['file']['error'] == 3){
			$_SESSION['warning_message'] .= "The uploaded file was only partially uploaded!!!";
		}
		if($_FILES['file']['error'] == 2){
			$_SESSION['warning_message'] .= "The uploaded file exceeds the MAX_FILE_SIZE!!!";
		}
		if($_FILES['file']['error'] == 1){
			$_SESSION['warning_message'] .= "The uploaded file exceeds the upload_max_filesize!!!";
		}
		
		$file_name = ($_FILES['file']['name']);
		
		//$ary_rep = array('~','`','@','#','^','&','+',';',',','%','!','=','\'','$','(',')','-');
		//$ary_rep = array('~','`','@','#','^','&','+',';',',','%');
		//$new_name = preg_replace( '/[\^\~\`\@\#\^\&\+\;\,\%\!\=\\\$\(\)\'\-\{\}\[\]]/i', '_', $file_name );
		
		if(!@$_SESSION['warning_message']){
			if(file_exists(ROOT.$file_name) and !$_POST['replace_file']){
				$_SESSION['warning_message'] .= "File exists already - Select overwrite to overwrite the file";
			}else{
				$ext = end(explode('.', $file_name));
				$unique_name = newGUID();
				$unique_name_with_ext = newGUID().'.'.$ext;
				
				if(!@move_uploaded_file($_FILES["file"]["tmp_name"], ROOT.$unique_name_with_ext)){
					$_SESSION['warning_message'] .= "Failed to upload file!!!";
				}else{
					$file_name = stripslashes($file_name);
					@$_SESSION['success_message'] .= "File successfully uploaded! - ".$file_name;
					
					$loc = str_replace('uploads/','',$getwhere);
					$action = ($_POST['replace_file']) ? 'replaced' : 'uploaded';
					
					insert_file_to_db($file_name, $unique_name_with_ext, $getwhere, $_SESSION['curr_country']['country_code']);
					write_to_log("New file \"{$file_name}\" was $action in File Manager (Location : $loc)");
				}
			}
		}
	}
}


/*function to delete file  / folder and all files within.*/
function delete_file($dirname,$filename,$getwhere, $from_folder = false){

	$filename = stripslashes($filename);
	if(is_file($dirname)){
		$real_name = stripcslashes(get_real_file_name($filename));
		if(unlink($dirname)){
			delete_file_from_db($filename);
			$_SESSION['success_message'] = 'Deleted file : '.$real_name;
			
			if($from_folder != true){//writes to log
				$loc = str_replace('uploads/','', urldecode($getwhere));
				write_to_log("File \"{$real_name}\" was deleted in File Manager (Location : $loc)");
			}
			
		}
	}
	elseif(is_dir($dirname)){
		$scan = glob(rtrim($dirname,'/').'/*');
		foreach($scan as $index=>$path){
			//echo '<br>index '.$index;
			//echo '<br>path '.$path;
			//echo 'basename '.basename($path);
			delete_file($path, basename($path), $getwhere, true);
		}
		$_SESSION['success_message'] = 'Deleted folder : '.str_replace("$$$$$","'",$filename);
		
		if(trim($filename) != ''){//writes to log
			$loc = str_replace('uploads/','',urldecode($getwhere));
			write_to_log("Folder \"{$filename}\" was deleted in File Manager (Location : $loc)");
		}
		return @rmdir($dirname);
	}
	
}


#returns files count o folder count in folder
function count_dir_elements($dir_path, $type = 'file') {
	$file_count = 0;
	$dir_count = 0;
	if ($dh = @opendir($dir_path)) {
		$i = 0;
		while ($el = readdir($dh)) {
			$path = $dir_path.'/'.$el;			
			if (is_dir($path) && $el != '.' && $el != '..') {
				$dir_count++;
			} elseif (is_file($path)) {
				$file_count++;
			}
			$i++;
		}
		closedir($dh);
	} 
	else {
		return '-';
	}
	return $type == 'file' ? $file_count : $dir_count;
}

#Function to convert filesize from bytes.
function cal_size($size){
	$i=0;
	$iec = array("B", "Kb", "Mb", "Gb", "Tb");
	while (($size/1024)>1){
		$size=$size/1024;
		$i++;
	}
	return(round($size,1)." ".$iec[$i]);
}

function entry_size($path){
	if(!file_exists($path)) return 0;
	if(is_file($path)) return filesize($path);
	$ret = 0;
	foreach(glob($path."/*") as $fn)
	$ret += entry_size($fn);
	return $ret;
}

function entry_size_in_mb($size){
	return number_format(($size)/(1020*1024), 3, '.', '');
}

function forward($url){	
	echo '
		<script type="text/javascript" language="javascript">
			location.replace("'.$url.'");
		</script>
	';
}

function write_to_log($desc){
	$log_params = array(
						"user_id" => $_SESSION['logged_user']['id'], 
						"action_desc" => $desc,
						"date_crated" => date("Y-m-d-H:i:s")
					);	
	SystemModel::keep_track_of_activity_log_in_system($log_params);
}

function is_valid_name($file_name){
	global $chars;
	$con;
	foreach($chars as $cha){
		if(strstr($file_name,$cha)){
			$con = false;
			break;			
		}else{
			$con = true;		
		}	
	}
	return $con;	
}

function has_permission($folder,$action){

	global $folder_access;
	
	if(
		($folder_access[1]['folder_text']  == $folder) && (in_array($folder_access[1]['folder_permissions'][$action],$_SESSION['logged_user']['permissions'])) || 
		($folder_access[2]['folder_text']  == $folder) && (in_array($folder_access[2]['folder_permissions'][$action],$_SESSION['logged_user']['permissions'])) || 
		($folder_access[3]['folder_text']  == $folder) && (in_array($folder_access[3]['folder_permissions'][$action],$_SESSION['logged_user']['permissions'])) || 
		($folder_access[4]['folder_text']  == $folder) && (in_array($folder_access[4]['folder_permissions'][$action],$_SESSION['logged_user']['permissions'])) || 
		($folder_access[5]['folder_text']  == $folder) && (in_array($folder_access[5]['folder_permissions'][$action],$_SESSION['logged_user']['permissions'])) || 
		($folder_access[6]['folder_text']  == $folder) && (in_array($folder_access[6]['folder_permissions'][$action],$_SESSION['logged_user']['permissions'])) || 
		($folder_access[7]['folder_text']  == $folder) && (in_array($folder_access[7]['folder_permissions'][$action],$_SESSION['logged_user']['permissions'])) || 
		($folder_access[8]['folder_text']  == $folder) && (in_array($folder_access[8]['folder_permissions'][$action],$_SESSION['logged_user']['permissions'])) || 
		($folder_access[9]['folder_text']  == $folder) && (in_array($folder_access[9]['folder_permissions'][$action],$_SESSION['logged_user']['permissions'])) ||
		($folder_access[10]['folder_text'] == $folder) && (in_array($folder_access[10]['folder_permissions'][$action],$_SESSION['logged_user']['permissions']))||
		($folder_access[11]['folder_text'] == $folder) && (in_array($folder_access[11]['folder_permissions'][$action],$_SESSION['logged_user']['permissions'])) ||
		($folder_access[12]['folder_text'] == $folder) && (in_array($folder_access[12]['folder_permissions'][$action],$_SESSION['logged_user']['permissions']))				
	){
		return true;
	}else{		
		return false;
	}
}

function insert_file_to_db($real_name, $unique_name, $path, $owned_country_id, $file_type=""){
	
	global $con;
	$file_type = ($file_type != "") ?  'folder' : 'file';
	$real_name = CommonFunctions::mysql_preperation($real_name);
	$path =  CommonFunctions::mysql_preperation($path);			
	$time = time();
	$result = mysql_query("INSERT INTO pahro__file_manager (type, owned_country_id,unique_name, real_name, path, date) VALUES ('".$file_type."', {$owned_country_id}, '$unique_name', '$real_name','$path', '$time')",$con);
}

function delete_file_from_db($filename){

	global $con;
	$filename = CommonFunctions::mysql_preperation($filename);
	$result = mysql_query("DELETE FROM pahro__file_manager WHERE unique_name = '$filename'",$con);
}

function get_real_file_name($filename){
	global $con;
	$filename = CommonFunctions::mysql_preperation($filename);
	$result = mysql_query("SELECT real_name FROM pahro__file_manager WHERE unique_name = '$filename'",$con);
	$row = mysql_fetch_object($result);
	return $row->real_name;
}

function get_all_files($owned_country_id){
	$params = array('id', 'unique_name','real_name', 'owned_country_id');
	return AppModel::grab_db_function_class()->result_to_array_for_few_fields_for_file_manager(AppModel::grab_db_function_class()->execute_query('SELECT id, unique_name, real_name, owned_country_id FROM pahro__file_manager WHERE owned_country_id = '.$owned_country_id.' or owned_country_id = 0 order by id'), $params);
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

/* This function will check the unique file whether belongs its country id */
function check_unique_name_belongs_to_its_country($unique_file_name){

	$owned_country_id = AppModel::grab_db_function_class()->return_single_result_from_mysql_resource(AppModel::grab_db_function_class()->execute_query('SELECT owned_country_id FROM pahro__file_manager WHERE unique_name = "'.$unique_file_name.'"'), 0);	
	return ($owned_country_id == $_SESSION['curr_country']['country_code'])	? true : false;
}
/* End of the function */

/* This function will check the unique name's owned country id equals to zero / this is for the previous files which has been uploaded to the system */
function check_unique_name_eligible_to_display(){

	$owned_country_id = AppModel::grab_db_function_class()->return_single_result_from_mysql_resource(AppModel::grab_db_function_class()->execute_query('SELECT owned_country_id FROM pahro__file_manager WHERE unique_name = "'.$unique_file_name.'"'), 0);		
	return ($owned_country_id == 0)	? true : false;
}
/* End of the function */

/* This function will check the read folder name from the windows is equal to the real name in the database */
function check_non_root_folder_name_validity($folder_real_name){

	$folder_real_name_in_db = AppModel::grab_db_function_class()->return_single_result_from_mysql_resource(AppModel::grab_db_function_class()->execute_query('SELECT real_name FROM pahro__file_manager WHERE real_name = "'.$folder_real_name.'"'), 0);	
	if ($folder_real_name_in_db == $folder_real_name){
		$folder_unique_name_in_db = AppModel::grab_db_function_class()->return_single_result_from_mysql_resource(AppModel::grab_db_function_class()->execute_query('SELECT unique_name FROM pahro__file_manager WHERE real_name = "'.$folder_real_name.'" AND type = "folder"'), 0);			
	}else{
		$folder_unique_name_in_db = $folder_real_name;
	}
	return $folder_unique_name_in_db;	
}
/* End of the function */

/* This function will check the unique file is a sub folder or not */
function check_subfolder_or_not($unique_file_name){

	$type = AppModel::grab_db_function_class()->return_single_result_from_mysql_resource(AppModel::grab_db_function_class()->execute_query('SELECT type FROM pahro__file_manager WHERE unique_name = "'.$unique_file_name.'" or real_name = "'.$unique_file_name.'"'), 0);	
	return ($type == "folder") ? true : false;	
}
/* End of the function */

?>