<?php

class SystemModel{
	
	/* This function will check the username exist in the database */
	function check_username_exist($username){
	
		global $connection;
		return (AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query("SELECT username FROM pahro__user WHERE status = 1 AND username = '".CommonFunctions::mysql_preperation($username)."'")) != "") ? true : false;
	}
	/* End of the function */
	
	/* This fucntion will check the password correct for the given username */
	function check_password_correct($user_login_params){

		global $connection;
		$user_pass = CommonFunctions::mysql_preperation(trim($user_login_params['password']));
		$password_in_db = AppModel::grab_db_function_class()->return_single_result_from_mysql_resource(AppModel::grab_db_function_class()->execute_query("SELECT username FROM pahro__user WHERE username = '".
							CommonFunctions::mysql_preperation(trim($user_login_params['username']))."' AND (password = md5('".$user_pass."') OR (password = OLD_PASSWORD('".$user_pass."')))"), 0);
		return ($password_in_db != '') ? true : false;
	}
	/* End fof the fucntion */
	
	/* This fucntion will grab the full details for the logged in user */
	function grab_full_details_for_the_loggedin_user($username){
	
		global $connection;
		$sql = "SELECT pahro__user.id, pahro__user.username, pahro__user.first_name, pahro__user.last_name, pahro__user.email, pahro__user.last_login, pahro__user.user_type_id 
				FROM pahro__user
				WHERE pahro__user.username = '{$username}'";
		$params = array('id', 'username', 'first_name', 'last_name', 'email', 'last_login', 'user_type_id');		
		return AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $params);							
	}
	/* End of the fucntion */

	/* This function will update the user table last login field with current logged time */
	function update_last_login($user_id){
	
		global $connection;
		AppModel::grab_db_function_class()->execute_query("UPDATE pahro__user SET last_login  = '".date("Y-m-d-H:i:s")."' WHERE id = ".$user_id);							
	} 
	/* End of the fucntion */
	
	/* This function will insert log details every time against the user action */
	function keep_track_of_activity_log_in_system($logParmas){
	
		global $connection;
		$sql = "INSERT INTO pahro__log
							(user_id, action_type_desc, date_time) 
							VALUES(".
									$logParmas['user_id'].",'".
									CommonFunctions::mysql_preperation(trim($logParmas['action_desc']))."','".
									$logParmas['date_crated'].
							"')"; 
		AppModel::grab_db_function_class()->execute_query($sql);		
	}
	/* End of the fucntion */
	
	/* This function will retrieve all log data from the db and display */
	function retrieve_all_logs($params, $curr_page_no = NULL, $sortBy = "", $sortMethod = ""){

		global $connection;
		$sort = "";
		$limit = "";		
	
		$display_items = 100;					
		if ($curr_page_no != NULL){
			if ($curr_page_no == 1){
				$start_no_sql = 0;
				$end_no_sql = $display_items;
			}else{							
				$start_no_sql = ($curr_page_no - 1) * $display_items;
			 $end_no_sql = $display_items;		
			}
		}else{
			 $start_no_sql = 0;
			 $end_no_sql = $display_items;		
		}
		if ($sortBy != ""){
			if ($sortMethod == ""){
				$sortMethod = "asc";
			}
			$sort = " ORDER BY {$sortBy} {$sortMethod}";	
		}else{
			$sort = " ORDER BY pahro__log.id DESC";				
		}
		$limit = " Limit {$start_no_sql}, {$end_no_sql}";				
		$sql = "SELECT pahro__log.id, pahro__user.username, pahro__log.action_type_desc, pahro__log.date_time 
				FROM pahro__log
				LEFT JOIN pahro__user ON pahro__user.id = pahro__log.user_id {$sort}{$limit}";	
		return AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $params);							
	}
	/* End of the function */
	
	/* This function will retrieve the count for all pahro users in to display */
	function retrieve_all_logs_count(){
	
		global $connection;
		$sql = "SELECT pahro__log.id, pahro__user.username, pahro__log.action_type_desc, pahro__log.date_time 
				FROM pahro__log
				LEFT JOIN pahro__user ON pahro__user.id = pahro__log.user_id";	
		return AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query($sql));				
	}
	/* End of the fucntion */		
	
	/* This function will retrieve the total number of cases in the system */
	function retrieve_tot_count_of_cases(){
	
		global $connection;
		AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query("SELECT count(id) FROM case__main"));			
	}
	/* End of the function */
	
	/* This function will retrieve the number of all clients in the system */
	function retrieve_tot_count_of_clients(){
			
		global $connection;
		AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query("SELECT count(client_id) FROM client__main"));			
	} 
	/* End of the fucntiion */
	
	/* This function will retrieve the number of all clients in the system */
	function retrieve_tot_count_of_counter_parties(){
		
		global $connection;
		AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query("SELECT count(client_id) FROM counter_party__main"));			
	} 
	/* End of the fucntiion */
	
	/* This function will retrieve count of all cases, clients and cps in the system and the count of cases, clients, cps adeed by this user if he is having permissions for each options */
	function retrieve_count_of_each_system_functional_resources($logged_user_permissions, $logged_id){
	
		global $connection;
		// this is for the cases
		if ((in_array(1, $logged_user_permissions)) && (in_array(2, $logged_user_permissions))){
			// Grab the no of total cases in the system
			$no_of_cases = AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query("SELECT id FROM case__main"));												
			// Grab the no of cases that responsible by this logged user
			$no_of_cases_added = AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query("SELECT id FROM case__main WHERE created_by = ".$logged_id));										
			// Grab the no of cases that responsible by this logged user
			$no_of_cases_responsible = AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query("SELECT id FROM case__main WHERE staff_responsible = ".$logged_id));										
			// Grab the no of active cases added by this logged user			
			$no_of_cases_active = AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query("SELECT id FROM case__main WHERE status = 'Active' AND staff_responsible = ".$logged_id));										
			// Grab the no of closed cases added by this logged user			
			$no_of_cases_closed = AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query("SELECT id FROM case__main WHERE status = 'Closed' AND staff_responsible = ".$logged_id));										
			// Grab the no of inactive cases added by this logged user			
			$no_of_cases_inactive = AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query("SELECT id FROM case__main WHERE status = 'Inactive' AND staff_responsible = ".$logged_id));										
			// Putting them alltogether
			$system_resources_count_arr = array(
													"no_of_cases" => $no_of_cases, $no_of_cases_added => "no_of_cases_added", 
													"no_of_cases_responsible" => $no_of_cases_responsible,
													"no_of_cases_active" => $no_of_cases_active, "no_of_cases_closed" => $no_of_cases_closed,
													"no_of_cases_inactive" => $no_of_cases_inactive
												  );					
		}elseif (in_array(1, $logged_user_permissions)){
			$no_of_cases = AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query("SELECT id FROM case__main"));										
			$system_resources_count_arr = array("no_of_cases" => $no_of_cases);		
		}
		
		// this is for the clients
		if ((in_array(9, $logged_user_permissions)) || (in_array(10, $logged_user_permissions))){
			// Grab the no of total clients in the system
			$no_of_clients = AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query("SELECT client_id FROM client__main"));												
			// Putting them alltogether
			$system_resources_count_arr = array_merge($system_resources_count_arr, array("no_of_clients" => $no_of_clients));					
		}

		// this is for the counter parties
		if ((in_array(13, $logged_user_permissions)) || (in_array(14, $logged_user_permissions))){
			// Grab the no of total clients in the system
			$no_of_cps = AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query("SELECT counter_party_id FROM counter_party__main"));												
			// Putting them alltogether
			$system_resources_count_arr = array_merge($system_resources_count_arr, array("no_of_cps" => $no_of_cps));					
		}

		// this is for the staff memebers
		if ((in_array(18, $logged_user_permissions)) || (in_array(19, $logged_user_permissions))){
			// Grab the no of total clients in the system
			$no_of_staff_memebers = AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query("SELECT id FROM pahro__user WHERE user_type_id = 1"));												
			// Putting them alltogether
			$system_resources_count_arr = array_merge($system_resources_count_arr, array("no_of_staff_memebers" => $no_of_staff_memebers));					
		}

		// this is for the volunteers
		if ((in_array(18, $logged_user_permissions)) || (in_array(19, $logged_user_permissions))){
			// Grab the no of total clients in the system
			$no_of_volunteers = AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query("SELECT id FROM pahro__user WHERE user_type_id = 2"));												
			// Putting them alltogether
			$system_resources_count_arr = array_merge($system_resources_count_arr, array("no_of_volunteers" => $no_of_volunteers));					
		}

		return $system_resources_count_arr;		
	}	
	/* End of the fucntion */
	
	/* This function will return the recently added cases as a simple display by this logged user */
	function load_recently_added_updated_cases($logged_user_permissions, $logged_id){
	
		global $connection;
		$params = array("reference_number", "case_name", "case_cat_name", "status");
		// This is the recently added cases limit up to 2
		$sql = "SELECT case__main.reference_number, case__main.case_name, case__category.case_cat_name, case__main.status 
				FROM case__main
				LEFT JOIN case__category ON case__category.case_cat_id = case__main.case_cat_id 
				ORDER BY created_date DESC LIMIT 0,2";				
		$recently_added_cases = AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $params);										
		// This is for the recently added cases by the logged user 
		$sql = "SELECT case__main.reference_number, case__main.case_name, case__category.case_cat_name, case__main.status 
				FROM case__main
				LEFT JOIN case__category ON case__category.case_cat_id = case__main.case_cat_id 
				WHERE created_BY = {$logged_id}
				ORDER BY created_date DESC LIMIT 0,2";				
		$recently_added_cases_by_logged_user = AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $params);										
		// This is the recently added cases limit up to 2
		$sql = "SELECT case__main.reference_number, case__main.case_name, case__category.case_cat_name, case__main.status 
				FROM case__main
				LEFT JOIN case__category ON case__category.case_cat_id = case__main.case_cat_id 
				ORDER BY edited_date DESC LIMIT 0,2";				
		$recently_edited_cases = AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $params);										
		// This is for the recently added cases by the logged user 
		$sql = "SELECT case__main.reference_number, case__main.case_name, case__category.case_cat_name, case__main.status 
				FROM case__main
				LEFT JOIN case__category ON case__category.case_cat_id = case__main.case_cat_id 
				WHERE edited_by = {$logged_id}
				ORDER BY edited_date DESC LIMIT 0,2";				
		$recently_edited_cases_by_logged_user = AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $params);										
		return array(
					"recently_added_cases" => $recently_added_cases, "recently_added_cases_by_logged_user" => $recently_added_cases_by_logged_user,
					"recently_edited_cases" => $recently_edited_cases, "recently_edited_cases_by_logged_user" => $recently_edited_cases_by_logged_user
					); 
	} 
	/* End of the fucntion */
}
?>