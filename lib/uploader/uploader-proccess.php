<?php

	require $_SERVER['DOCUMENT_ROOT']."/define.inc";
	$db_settings = new PahroSettings();

	$settings = $db_settings->db_settings;
	$con = mysql_connect($settings['host'], $settings['username'], $settings['password']);
	mysql_select_db($settings['database'], $con);
		
	if(!session_start()){
		session_start();
	}
	
	
	//------------------------------------------------------- CASE TEMPLATES
	//ajax, get case
	if($_POST['get-case'] == true){		
		$remove = '&nbsp;<span id="'.$_SESSION['case']['id'].'" class="remove-case-current" onclick="remove_case(this.id)"> [remove] </span>';
		$file_name = $_SESSION['case']['name'];
		$ary = explode('_', $file_name, 2);
		$file_name_real = $ary[1];
		
		$file_url = '<a title="Click to download" href="http://'.$_SERVER['HTTP_HOST'].'/pahro/download/?file='.$file_name.'&type=user-uploads&where=temp/">'.$file_name_real.'</a>';
		echo '<div id="d'.$_SESSION['case']['id'].'" class="uploaded-case">'.$file_url.$remove.'</div>';
	}
	//end
	
	//ajax, removing case
	if($_POST['remove-case'] == true){
		$id = $_POST['id'];
		if(unlink($_SESSION['case_main']['template_name'][$id]['file_path_case'])){
			unset($_SESSION['case_main']['template_name'][$id]);
			echo 1;
		}
		else{
			echo 0;
		}
	}
	//end
	
	//ajax, removing case attachment from db
	if($_POST['remove-case-edit'] == true){
		$file_id = $_POST['fid'];
		$case_id = $_POST['cid'];
		$result = mysql_query("SELECT name FROM case__templates WHERE id=$file_id",$con);
		$row = mysql_fetch_object($result);
		$file_name = $row->name;
		$file_path = $_SERVER['DOCUMENT_ROOT'].'/user-uploads/cases/'.$case_id.'/'.$file_name;

		unlink($file_path);
		$sql = "DELETE FROM case__templates WHERE id = $file_id ";
		mysql_query($sql);
	}

	//--------END CASE TEMPLATES
	
	
	
	
	//------------------------------------------------------- CLIENT ATTACHMENTS
	//ajax, get client attachment
	if($_POST['get-client'] == true){		
		$remove = '&nbsp;<span id="'.$_SESSION['client']['id'].'" class="remove-client-current" onclick="remove_client(this.id)"> [remove] </span>';
		$file_name = $_SESSION['client']['name'];
		$ary = explode('_', $file_name, 2);
		$file_name_real = $ary[1];
		
		$file_url = '<a title="Click to download" href="http://'.$_SERVER['HTTP_HOST'].'/pahro/download/?file='.$file_name.'&type=user-uploads&where=temp/">'.$file_name_real.'</a>';
		echo '<div id="d'.$_SESSION['client']['id'].'" class="uploaded-client">'.$file_url.$remove.'</div>';
	}
	//end
	
	//ajax, removing client attachment
	if($_POST['remove-client'] == true){
		$id = $_POST['id'];
		if(unlink($_SESSION['client_main']['template_name'][$id]['file_path_client'])){
			unset($_SESSION['client_main']['template_name'][$id]);
			echo 1;
		}
		else{
			echo 0;
		}
		echo $_SESSION['client_main']['template_name'][$id]['file_path_case'];
	}
	//end
	
	//ajax, removing client attachment from db
	if($_POST['remove-client-edit'] == true){
		$file_id = $_POST['fid'];
		$client_id = $_POST['cid'];
		$result = mysql_query("SELECT name FROM client__attachments WHERE id=$file_id",$con);
		$row = mysql_fetch_object($result);
		$file_name = $row->name;
		echo $file_path = $_SERVER['DOCUMENT_ROOT'].'/user-uploads/clients/'.$client_id.'/'.$file_name;

		unlink($file_path);
		$sql = "DELETE FROM client__attachments WHERE id = $file_id ";
		mysql_query($sql);
	}

	//--------END CLIENT
	
	
	
	
	//------------------------------------------------------- COUNTER PARTY ATTACHMENTS
	//ajax, get client attachment
	if($_POST['get-counter-party'] == true){		
		$remove = '&nbsp;<span id="'.$_SESSION['counter_party']['id'].'" class="remove-counter-party-current" onclick="remove_counter_party(this.id)"> [remove] </span>';
		$file_name = $_SESSION['counter_party']['name'];
		$ary = explode('_', $file_name, 2);
		$file_name_real = $ary[1];
		
		$file_url = '<a title="Click to download" href="http://'.$_SERVER['HTTP_HOST'].'/pahro/download/?file='.$file_name.'&type=user-uploads&where=temp/">'.$file_name_real.'</a>';
		echo '<div id="d'.$_SESSION['counter_party']['id'].'" class="uploaded-counter-party">'.$file_url.$remove.'</div>';
	}
	//end
	
	//ajax, removing counter-party attachment
	if($_POST['remove-counter-party'] == true){
		$id = $_POST['id'];
		if(unlink($_SESSION['counter_party_main']['template_name'][$id]['file_path_counter_party'])){
			unset($_SESSION['counter_party_main']['template_name'][$id]);
			echo 1;
		}
		else{
			echo 0;
		}
		echo $_SESSION['counter_party_main']['template_name'][$id]['file_path_counter_party'];
	}
	//end
	
	//ajax, removing counter-party attachment from db
	if($_POST['remove-counter-party-edit'] == true){
		$file_id = $_POST['fid'];
		$counter_party_id = $_POST['cid'];
		$result = mysql_query("SELECT name FROM counter_party__attachments WHERE id=$file_id",$con);
		$row = mysql_fetch_object($result);
		$file_name = $row->name;
		echo $file_path = $_SERVER['DOCUMENT_ROOT'].'/user-uploads/counter-parties/'.$counter_party_id.'/'.$file_name;

		unlink($file_path);
		$sql = "DELETE FROM counter_party__attachments WHERE id = $file_id ";
		mysql_query($sql);
	}

	//--------END COUNTER PARTY
	
	
	
?>
