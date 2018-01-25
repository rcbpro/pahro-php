<?php

class AppController{

	/** Redirect  to the address
	 *  @param String address
	 *  returun redirect
	*/
	static function redirect_to($address){ header('Location:'.$address); }

	/** Redirect  to the address after given
	 *  @param String address
	 *  returun redirect
	*/
	static function wait_then_redirect_to($time, $address){ header("Refresh: {$time}; {$address}"); }
	
	/** Check for user session availability : if not found redirect to new
	 *  return bool or redirect
	 */
	 static function check_authentication(){

		return (isset($_SESSION['logged_user']['id'])) ? ((AppController::is_session_uid_same_with_db_id($_SESSION['logged_user']['id'])) ? true : false) : false;
	 }
	
	/** This function will process the correct model */	 
 	function process_the_correct_model(){}	
	
	/* This function will check for the url with query string which is provided by the user is valid */
	static function url_gate_keeper($qStrings, $controller){

		$allowed_qString_keys = array();
		$allowed_qString_values = array();
		$invalidAccess = false;
		
		switch($controller){
			case "case":
				$allowed_qString_keys = array("ref_no", "page", "mode", "sname", "drop_id", "sort", "by", "notes_page", "opt", "note_id", "case_id");			
				$location = "http://".$_SERVER['HTTP_HOST']."/case/view/";
				$allowed_qString_values = array(
												"by" => array("asc", "desc"),
												"sort" => array("own_country", "ref_no", "case", "case_cat", "status", "op_date", "up_date", "res_staff", "cre_date", "cre_by", "add_by", "add_date", "mod_date", "mod_by", "note", "note_cat"),
												"opt" => array("sorting", "edit", "view", "drop")
											   );
			break;

			case "case-category":
				$allowed_qString_keys = array("cat_id", "page", "cat_name", "drop_id", "sort", "by", "opt");			
				$location = "http://".$_SERVER['HTTP_HOST']."/case-category/view/";
				$allowed_qString_values = array(
												"by" => array("asc", "desc"),
												"sort" => array("case_cat", "case_cat_desc"),
												"opt" => array("sorting", "edit", "view", "drop")
											   );
			break;
			
			case "client":
				$allowed_qString_keys = array("client_id", "mode", "fname", "sname", "f_name", "l_name", "drop_id", "sort", "by", "opt", "page", "note_id", "notes_page");			
				$location = "http://".$_SERVER['HTTP_HOST']."/client/view/";
				$allowed_qString_values = array(
												"by" => array("asc", "desc"),
												"sort" => array("own_country", "f_name", "l_name", "mar_stat", "res", "email", "l_phone", "coun", "email", "emp_add", "note", "add_by", "add_date", "mod_date", "mod_by"),
												"opt" => array("view", "edit", "sorting", "drop")
											   );
			break;
		
			case "counter-party":
				$allowed_qString_keys = array("cp_id", "mode", "fname", "sname", "f_name", "l_name", "drop_id", "sort", "by", "opt", "page", "note_id", "notes_page");			
				$location = "http://".$_SERVER['HTTP_HOST']."/counter-party/view/";
				$allowed_qString_values = array(
												"by" => array("asc", "desc"),
												"sort" => array("own_country", "f_name", "l_name", "comp", "res", "pos_add", "email", "l_phone", "mob", "email", "note", "add_by", "add_date", "mod_date", "mod_by"),
												"opt" => array("view", "edit", "sorting", "drop")
											   );
			break;		

			case "pahro":
				$allowed_qString_keys = array("file", "do", "type", "where", "pahro_id", "pahro_ids", "page", "fname", "sname", "drop_id", "sort", "by", "u_type", "u_name", "f_name", "l_name", "email", "created_at", "last_login", "action", "note_id", "mode", "opt", "notes_page", "vol_id", "p");			
				$location = "http://".$_SERVER['HTTP_HOST']."/pahro/view/";
				$allowed_qString_values = array(
												"by" => array("asc", "desc"),
												"sort" => array("f_name", "l_name", "u_name", "created_at", "last_login", "email", "u_type", "desc", "mod_date", "con", "note", "add_by", "add_date", "add_by", "mod_by"),
												"action" => array("reset"),
												"opt" => array("drop", "edit", "view", "sorting"),
												"mode" => array("main", "notes", "permissions"),
												"p" => array("1")												
											   );
			break;

			case "system":
				$allowed_qString_keys = array("page", "sort", "by", "fname", "sname");			
				$location = "http://".$_SERVER['HTTP_HOST']."/system/activity-log/";
				$allowed_qString_values = array(
												"by" => array("asc", "desc"),
												"sort" => array("u_name", "act_desc", "time", "past")
											   );
			break;					
		}
		foreach($qStrings as $key => $value){
			if (!in_array($key, $allowed_qString_keys)){
				$invalidAccess = true;
				break;							
			}else{ 
				foreach($allowed_qString_values as $keyInternal => $valueInternal){
					if ($keyInternal == $key){
						if (!in_array($value, $valueInternal)){
							$invalidAccess = true;
							break;							
						} 
					}
				}
			}	
		}
	}
	/* End of the fucntion */	
	
	/* This function will check the session user id still exist in the database */
	function is_session_uid_same_with_db_id($session_u_id){
		
		global $connection;
		return (AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query("SELECT id FROM pahro__user WHERE id = {$session_u_id} AND status = 1")) == 0) ? false : true;		
	}
	/* End of the fucntion */		
}
?>