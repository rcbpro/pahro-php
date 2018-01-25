<?php

class PahroUserModel{

	/* This function will insert a new user to the system */
	function insert_new_pahro_user($user_param_array, $tagos_id=""){
	
		global $connection;
		if ($tagos_id != ""){
			$sql = "INSERT INTO pahro__user(user_type_id, username ,password, first_name, last_name, tagos_id, email, created_at, last_login) 
								VALUES(".
										$user_param_array['user_type_id'].",'".
										CommonFunctions::mysql_preperation($user_param_array['username'])."','".
										$user_param_array['password']."','".
										CommonFunctions::mysql_preperation(trim($user_param_array['first_name']))."','".
										CommonFunctions::mysql_preperation(trim($user_param_array['last_name']))."',".
										$tagos_id.",'".										
										CommonFunctions::mysql_preperation(trim($user_param_array['email']))."','".
										$user_param_array['created_at']."','".$user_param_array['last_login'].
									"')"; 
		}else{
			$sql = "INSERT INTO pahro__user(user_type_id, username ,password, first_name, last_name, email, created_at, last_login) 
								VALUES(".
										$user_param_array['user_type_id'].",'".
										CommonFunctions::mysql_preperation($user_param_array['username'])."','".
										$user_param_array['password']."','".
										CommonFunctions::mysql_preperation(trim($user_param_array['first_name']))."','".
										CommonFunctions::mysql_preperation(trim($user_param_array['last_name']))."','".
										CommonFunctions::mysql_preperation(trim($user_param_array['email']))."','".
										$user_param_array['created_at']."','".$user_param_array['last_login'].
									"')"; 
		}							
		AppModel::grab_db_function_class()->execute_query($sql);		
		return AppModel::grab_db_function_class()->return_last_inserted_id();		
	}
	/* End of the function */
	
	/* This function will insert a new user to the system */
	function insert_new_pahro_user_countires($u_id, $country_param_array){
	
		global $connection;
		for($i=0; $i<count($country_param_array); $i++){
			$sql = "INSERT INTO pahro__users_countries
								(user_id, country_id) 
								VALUES(".
										$u_id.",".
										$country_param_array[$i].
									  ")"; 
			AppModel::grab_db_function_class()->execute_query($sql);		
		}	
	}
	/* End of the function */
	
	/* This function will retrieve all pahro users in to display */
	function display_all_pahro_users($params, $curr_page_no = NULL, $sortBy = "", $sortMethod = "", $user_country_params, $filtering, $countries, $curr_country){
	
		global $connection;
		$sort = "";
		$limit = "";		
	
		$display_items = NO_OF_RECORDS_PER_PAGE_DEFAULT;					
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
			$sort = " ORDER BY pahro__user.id DESC";				
		}
		$limit = " Limit {$start_no_sql}, {$end_no_sql}";				
		if (!$filtering){
			$sql = "SELECT pahro__user.id, pahro__user.status, pahro__user_types.user_type, pahro__user.username, pahro__user.first_name, pahro__user.last_name, 
					pahro__user.email, pahro__user.created_at, pahro__user.last_login
					FROM pahro__user 
					LEFT JOIN pahro__users_countries ON pahro__users_countries.user_id = pahro__user.id
					LEFT JOIN pahro__user_types ON pahro__user_types.user_type_id = pahro__user.user_type_id 
					WHERE pahro__users_countries.country_id = {$curr_country}
					{$sort}{$limit}";
			$full_results = AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $params);				
		}else{
			$sql = "SELECT pahro__user.id, pahro__user.status, pahro__user_types.user_type, pahro__user.username, pahro__user.first_name, pahro__user.last_name, 
					pahro__user.email, pahro__user.created_at, pahro__user.last_login
					FROM pahro__user 
					LEFT JOIN pahro__user_types ON pahro__user_types.user_type_id = pahro__user.user_type_id 
					LEFT JOIN pahro__users_countries ON pahro__users_countries.user_id = pahro__user.id
					WHERE pahro__users_countries.country_id = {$curr_country} AND pahro__user_types.user_type_id = 2 AND (";					
				for($i=0; $i<count($countries); $i++){
					$sql .= "(pahro__users_countries.country_id =" .$countries[$i].")";
					if ($i != count($countries)-1){
						$sql .= " OR ";
					}else{
						$sql .= " )";
					}
				}	
				$sql .= " {$sort}{$limit}";
				$full_results = AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $params);
		}		
		return $full_results;		
		
	}
	/* End of the fucntion */
	
	/* This function will retrieve the count for all pahro users in to display */
	function display_count_on_all_pahros($user_country_params, $params, $filtering, $countries, $curr_country){
	
		global $connection;
		if (!$filtering){
			$sql = "SELECT pahro__user.id, pahro__user_types.user_type, pahro__user.username, pahro__user.first_name, pahro__user.last_name, 
					pahro__user.email, pahro__user.created_at, pahro__user.last_login
					FROM pahro__user 
					LEFT JOIN pahro__users_countries ON pahro__users_countries.user_id = pahro__user.id					
					LEFT JOIN pahro__user_types ON pahro__user_types.user_type_id = pahro__user.user_type_id 
					WHERE pahro__users_countries.country_id = {$curr_country}										
					{$sort}{$limit}";
					
			$full_results = AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $params);				
		}else{
			$sql = "SELECT pahro__user.id, pahro__user_types.user_type, pahro__user.username, pahro__user.first_name, pahro__user.last_name, 
					pahro__user.email, pahro__user.created_at, pahro__user.last_login
					FROM pahro__user 
					LEFT JOIN pahro__user_types ON pahro__user_types.user_type_id = pahro__user.user_type_id 
					LEFT JOIN pahro__users_countries ON pahro__users_countries.user_id = pahro__user.id
					WHERE pahro__users_countries.country_id = {$curr_country} AND pahro__user_types.user_type_id = 2 AND (";							
				for($i=0; $i<count($countries); $i++){
					$sql .= "(pahro__users_countries.country_id =" .$countries[$i].")";
					if ($i != count($countries)-1){
						$sql .= " OR ";
					}else{
						$sql .= " )";
					}
				}	
				$full_results = AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $params);
		}		
		return count($full_results);				
	}
	/* End of the fucntion */		

	/* This function will grab the user related country id and country name for a given user */
	function grab_user_owned_country($user_id){
	
		global $connection;
		$sql = "SELECT pahro__country.country_name, pahro__users_countries.country_id FROM pahro__country LEFT JOIN pahro__users_countries";
	}
	/* End of the function */

	/* This function will check whether the given user is being responsible for any case as a staff memeber */
	function is_responsible_for_case($staff_id){

		global $connection;
		return (AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query("SELECT staff_responsible FROM case__main WHERE staff_responsible = {$staff_id}")) != 0) ? true : false;																
	}
	/* End of the fucntion */			

	/* This function will retreive one of the responsble case id for a staff */
	function get_the_responsible_for_case_numbers($staff_id){

		global $connection;
		return (AppModel::grab_db_function_class()->return_single_result_from_mysql_resource(AppModel::grab_db_function_class()->execute_query("SELECT reference_number FROM case__main WHERE staff_responsible = {$staff_id}"), 0));																
	}
	/* End of the function */

	/* This function will retreive one of the responsble case id for a staff */
	function get_the_currently_working_case_numbers($vol_id){

		global $connection;
		return (AppModel::grab_db_function_class()->return_single_result_from_mysql_resource(AppModel::grab_db_function_class()->execute_query("SELECT case_id FROM case__volunteers WHERE vol_id = {$vol_id}"), 0));																
	}
	/* End of the function */
	
	/* This function will check whether the given user is being responsible for any case as a staff memeber */
	function is_being_assgined_to_any_case($vols_id){

		global $connection;
		return (AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query("SELECT vol_id FROM case__volunteers WHERE vol_id = {$vols_id}")) != 0) ? true : false;																
	}
	/* End of the fucntion */			

	/* This function will check whether the given pahro book id from the get value is exist in database */
	function check_pahro_id_exist($id){
	
		global $connection;
		return (AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query("SELECT id FROM pahro__user WHERE id = {$id}")) != "") ? true : false;																
	}
	/* End of the function */

	/* This function will retrieve full details per each address book contact */
	function retrieve_full_details_per_each_pahro_user($user_id){
	
		global $connection;
		$sql = "SELECT pahro__user.id, pahro__user.user_type_id, pahro__user_types.user_type, pahro__user.username, pahro__user.first_name, pahro__user.last_name, pahro__user.email
				FROM pahro__user
				LEFT JOIN pahro__user_types ON pahro__user_types.user_type_id = pahro__user.user_type_id				
				WHERE pahro__user.id = ".$user_id;		
		$params = array('id', 'user_type', 'user_type_id', 'username', 'first_name', 'last_name', 'email');		
		return AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $params);							
	}
	/* End of the function */	

	/* This function will retrieve full details per each address book contact */
	function retrieve_user_country_names($user_id, $country_param){
	
		global $connection;
		$sql = "SELECT pahro__country.country_id, pahro__country.country_name 
				FROM pahro__users_countries 
				LEFT JOIN pahro__country ON pahro__country.country_id = pahro__users_countries.country_id 
				WHERE user_id = {$user_id}";
		return AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $country_param);							
	}
	/* End of the function */	

	/* This function will retrieve full details per each address book contact */
	function retrieve_user_countries($user_id){
	
		global $connection;
		return AppModel::grab_db_function_class()->result_to_single_array_of_data(AppModel::grab_db_function_class()->execute_query("SELECT country_id FROM pahro__users_countries WHERE user_id = ".$user_id . " ORDER BY country_id"), "country_id");							
	}
	/* End of the function */	

	/* This function will retrieve full details per each address book contact */
	function retrieve_user_countries_by_their_names($user_id){
	
		global $connection;
		$sql = "SELECT country_name FROM pahro__country LEFT JOIN pahro__users_countries ON pahro__users_countries.country_id = pahro__country.country_id WHERE user_id = ".$user_id . " ORDER BY pahro__country.country_id";		
		return AppModel::grab_db_function_class()->result_to_single_array_of_data(AppModel::grab_db_function_class()->execute_query($sql), "country_name");							
	}
	/* End of the function */	
	
	/* This function will check the username entered by the user is exist in the db */
	function check_username_exist_in_db($username){
	
		global $connection;
		return (AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query("SELECT username FROM pahro__user WHERE username = '{$username}'")) == 0) ? false : true;
	}
	/* End of the function */
	
	/* This function will check the user's previous password is correct when he tries to edit his profile */
	function check_previous_password_correct($username, $password){
	
		global $connection;
		$cur_password = AppModel::grab_db_function_class()->return_single_result_from_mysql_resource(AppModel::grab_db_function_class()->execute_query("SELECT password FROM pahro__user WHERE username = '{$username}'"), 0);	
		return ($cur_password == $password)	? true : false;
	}
	/* End of the function */
	
	/* This function will update the user's details */
	function update_user_details($u_id, $user_details_params){

		global $connection;
		if ($user_details_params['password'] != ""){
			$sql = "UPDATE pahro__user 
								SET 
									first_name = '" . CommonFunctions::mysql_preperation(trim($user_details_params['first_name'])) ."', 
									last_name = '" . CommonFunctions::mysql_preperation(trim($user_details_params['last_name'])) ."', 																						
									password = '" . CommonFunctions::mysql_preperation(trim($user_details_params['password'])) ."',  
									email = '" . CommonFunctions::mysql_preperation(trim($user_details_params['email'])) ."' 
								WHERE id = " . $u_id;		
		}else{
			$sql = "UPDATE pahro__user 
								SET 
									first_name = '". CommonFunctions::mysql_preperation(trim($user_details_params['first_name'])) ."', 
									last_name = '". CommonFunctions::mysql_preperation(trim($user_details_params['last_name'])) ."', 																						
									email = '". CommonFunctions::mysql_preperation(trim($user_details_params['email'])) ."' 
								WHERE id = ".$u_id;		
		}		
		// country_id = " . $user_details_params['country_id'] . ",
		AppModel::grab_db_function_class()->execute_query($sql);							
	}
	/* End of the function */
	
	/* This function will delete the user record from the users table */
	function delete_user_details_from_the_table($u_id){
	
		global $connection;
		if ($u_id != ""){ 
			$sql = "DELETE FROM pahro__user WHERE id = ".$u_id;
			AppModel::grab_db_function_class()->execute_query($sql);								
		}	
	} 
	/* End of the function */
	
	/*deactivates the user*/
	function set_user_status($uid,$status){
		global $connection;
		if ($uid != ""){ 
			$sql = "UPDATE pahro__user SET status = {$status} WHERE id = ".$uid;
			AppModel::grab_db_function_class()->execute_query($sql);								
		}
	}
	/*end*/	
	
	/* This function will load all neccessary details for the single view */
	function grab_full_details_for_the_single_view_in_pahro($pahro_id){
	
		global $connection;
		$sql = "SELECT 
					pahro__user.first_name, pahro__user.last_name, pahro__user.username, pahro__user.email, 
					pahro__user.created_at, pahro__user.last_login,
					pahro__user_types.user_type
				FROM pahro__user
				LEFT JOIN pahro__user_types ON pahro__user_types.user_type_id = pahro__user.user_type_id 
				WHERE pahro__user.id = ".$pahro_id;
		$params = array('first_name', 'last_name', 'username', 'email', 'created_at', 'last_login', 'user_type');		
		return AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $params);							
	}
	/* End of the fucntion */	
	
	/* This function will insert log details every time against the user action */
	function keep_track_of_activity_log_in_pahro($logParmas){
	
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
	
	/* This function will load all user types */
	function retrieve_all_user_types(){
	
		global $connection;
		$sql = "SELECT * FROM pahro__user_types ORDER BY user_type_id ASC";
		$params = array('user_type_id', 'user_type');		
		return AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $params);							
	}
	/* End of the function */

	/* This function will load all user permissions */
	function retrieve_all_user_permissions_for_staff(){
	
		global $connection;
		$sql = "SELECT * FROM pahro__user_permission WHERE status = 1 AND (special_mermissions = 'Y' OR special_mermissions = 'N') ORDER BY order_id ASC";
		$params = array('permission_id', 'permission_name', 'permission_description');		
		return AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $params);							
	}
	/* End of the function */

	/* This function will load all user permissions */
	function retrieve_all_user_permissions_for_vols(){
	
		global $connection;
		$sql = "SELECT * FROM pahro__user_permission WHERE status = 1 AND special_mermissions = 'N' ORDER BY order_id ASC";
		$params = array('permission_id', 'permission_name', 'permission_description');		
		return AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $params);							
	}
	/* End of the function */
	
	/* This function will allocate the newly added user with the user groups what he has select */
	function insert_user_permissions_to_user($new_pahro_id, $user_permissions_param){
	
		global $connection;
		for($i=0; $i<count($user_permissions_param); $i++){
			$sql = "INSERT INTO pahro__user_permission_rel
								(user_id, permission_id) 
								VALUES(".
										$new_pahro_id.",".
										$user_permissions_param[$i]['permission_id'].
									  ")"; 
			AppModel::grab_db_function_class()->execute_query($sql);		
		}	
	}
	/* End of the fucntion */

	/* This function will allocate the premissions for each volunteers that used to import */
	function insert_volunteers_permissions_in_import_process($new_pahro_id, $user_permissions_param){
	
		global $connection;
		foreach($user_permissions_param as $each_permission){
			$sql = "INSERT INTO pahro__user_permission_rel
								(user_id, permission_id) 
								VALUES(".
										$new_pahro_id.",".
										$each_permission.
									  ")"; 
			AppModel::grab_db_function_class()->execute_query($sql);		
		}	
	}
	/* End of the fucntion */
	
	/* This function will retrieve all user gruops which owned by a specific user */
	function grab_owned_user_permissions($pahro_id){
	
		global $connection;
		$sql = "SELECT permission_id FROM pahro__user_permission_rel WHERE user_id = {$pahro_id}";
		return AppModel::grab_db_function_class()->result_to_single_array_of_data(AppModel::grab_db_function_class()->execute_query($sql), "permission_id");							
	}
	/* End of the function  */
	
	/* This function will clear the user previous user group details */
	function clear_user_previous_permission_details($pahro_id){
	
		global $connection;
		AppModel::grab_db_function_class()->execute_query("DELETE FROM pahro__user_permission_rel WHERE user_id = {$pahro_id}");							
	}
	/* End of the function */

	/* This function will clear the user previous user group details */
	function clear_user_countries_owned($pahro_id){
	
		global $connection;
		AppModel::grab_db_function_class()->execute_query("DELETE FROM pahro__users_countries WHERE user_id = {$pahro_id}");							
	}
	/* End of the function */
	
	/* This function will retrieve all user gruops which owned by a specific user */
	function grab_owned_user_permission_names($pahro_id){
	
		global $connection;
		$sql = "SELECT DISTINCT(pahro__user_permission.permission_description) 
				FROM pahro__user_permission 
				LEFT JOIN pahro__user_permission_rel ON pahro__user_permission_rel.permission_id = pahro__user_permission.permission_id
				WHERE pahro__user_permission_rel.user_id = {$pahro_id}";
		return AppModel::grab_db_function_class()->result_to_single_array_of_data(AppModel::grab_db_function_class()->execute_query($sql), "permission_description");							
	}
	/* End of the function  */
	
	/* This function will update the user's details */
	function reset_password($u_id, $pass){

		global $connection;
		$sql = "UPDATE pahro__user 
							SET 
								password = '".md5($pass)."'  
							WHERE id = ".$u_id;		
		AppModel::grab_db_function_class()->execute_query($sql);							
	}
	/* End of the function */
	
	/* This function will print staff permissions before post back in add mode */
	function display_staff_permissions_before_postback_in_add_mode($user_permissions_for_staff, $staff_perm_groups){
	
		$permissions_html = "";
		foreach($staff_perm_groups as $group){
		
			$permissions_html .= "<fieldset>                                                            
                                    <legend><span class='defaultFont'>{$group}</span></legend>";

			for($i=0; $i<count($user_permissions_for_staff); $i++){
				$descriptions = explode(" ", $user_permissions_for_staff[$i]['permission_description']);
				if (count($descriptions) == 3){
					$title = $descriptions[0]." ".$descriptions[1];
					$desc = $descriptions[2];				
				}elseif (count($descriptions) == 4){
					$title = $descriptions[0]." ".$descriptions[1]." ".$descriptions[2];
					$desc = $descriptions[3];				
				}else{
					$title = $descriptions[0];
					$desc = $descriptions[1];
				}					
				if ($title === $group){
					$permissions_html .= "<label class='defaultFont'><input id='chk_per_staff_{$user_permissions_for_staff[$i]['permission_id']}' 
															type='checkbox' checked='checked' name='pahro_user_staff_permission_reqired[]'
															value={$user_permissions_for_staff[$i]['permission_id']} />&nbsp;{$desc}</label>&nbsp;";                                            
				}												
			}
			$permissions_html .= "</fieldset>";
		}		
		return $permissions_html;
	}
	/* End of the function */
	
	/* This function will print vols permissions before post back in add mode */
	function display_vols_permissions_before_postback_in_add_mode($user_permissions_for_vols, $vols_perm_groups){
	
		$permissions_html = "";
		foreach($vols_perm_groups as $group){
		
			$permissions_html .= "<fieldset>                                                            
                                    <legend><span class='defaultFont'>{$group}</span></legend>";

			for($i=0; $i<count($user_permissions_for_vols); $i++){
				$descriptions = explode(" ", $user_permissions_for_vols[$i]['permission_description']);
				if (count($descriptions) == 3){
					$title = $descriptions[0]." ".$descriptions[1];
					$desc = $descriptions[2];				
				}elseif (count($descriptions) == 4){
					$title = $descriptions[0]." ".$descriptions[1]." ".$descriptions[2];
					$desc = $descriptions[3];				
				}else{
					$title = $descriptions[0];
					$desc = $descriptions[1];
				}					
				if ($title == $group){
					$permissions_html .= "<label class='defaultFont'><input id='chk_per_vol_{$user_permissions_for_vols[$i]['permission_id']}' 
															type='checkbox' checked='checked' name='pahro_user_vols_permission_reqired[]'
															value={$user_permissions_for_vols[$i]['permission_id']} />&nbsp;{$desc}</label>&nbsp;";                                            
				}												
			}
									
			$permissions_html .= "</fieldset>";
			
		}		
		return $permissions_html;
	}
	/* End of the function */
	
	/* This function will print staff permissions after post back in add mode */
	function display_staff_permissions_after_postback_in_add_mode($user_permissions_for_staff, $pahro_user_staff_permission_reqired, $staff_perm_groups){
	
		$permissions_html = "";
		foreach($staff_perm_groups as $group){
		
			$permissions_html .= "<fieldset>                                                            
                                    <legend><span class='defaultFont'>{$group}</span></legend>";

			for($i=0; $i<count($user_permissions_for_staff); $i++){
				$descriptions = explode(" ", $user_permissions_for_staff[$i]['permission_description']);
				if (count($descriptions) == 3){
					$title = $descriptions[0]." ".$descriptions[1];
					$desc = $descriptions[2];				
				}elseif (count($descriptions) == 4){
					$title = $descriptions[0]." ".$descriptions[1]." ".$descriptions[2];
					$desc = $descriptions[3];				
				}else{
					$title = $descriptions[0];
					$desc = $descriptions[1];
				}					
				if ($title == $group){
					$permissions_html .= "<label class='defaultFont'><input id='chk_per_staff_{$user_permissions_for_staff[$i]['permission_id']}' 
															type='checkbox' name='pahro_user_staff_permission_reqired[]'";
										if (@in_array($user_permissions_for_staff[$i]['permission_id'], $pahro_user_staff_permission_reqired)){
											$permissions_html .= "checked='checked'";
										}
										$permissions_html .= "value={$user_permissions_for_staff[$i]['permission_id']} />&nbsp;{$desc}</label>&nbsp;";                                            
				}												
			}
			$permissions_html .= "</fieldset>";
		}	
		return $permissions_html;
	}
	/* End of the function */
	
	/* This function will print vols permissions before post back in add mode */
	function display_vols_permissions_after_postback_in_add_mode($user_permissions_for_vols, $pahro_user_vols_permission_reqired, $vols_perm_groups){
	
		$permissions_html = "";
		foreach($vols_perm_groups as $group){
		
			$permissions_html .= "<fieldset>                                                            
                                    <legend><span class='defaultFont'>{$group}</span></legend>";

			for($i=0; $i<count($user_permissions_for_vols); $i++){
				$descriptions = explode(" ", $user_permissions_for_vols[$i]['permission_description']);
				if (count($descriptions) == 3){
					$title = $descriptions[0]." ".$descriptions[1];
					$desc = $descriptions[2];
				}elseif (count($descriptions) == 4){
					$title = $descriptions[0]." ".$descriptions[1]." ".$descriptions[2];
					$desc = $descriptions[3];				
				}else{
					$title = $descriptions[0];
					$desc = $descriptions[1];
				}					
				if ($title == $group){
					$permissions_html .= "<label class='defaultFont'><input id='chk_per_vol_{$user_permissions_for_vols[$i]['permission_id']}' 
															type='checkbox' name='pahro_user_vols_permission_reqired[]'";
										if (@in_array($user_permissions_for_vols[$i]['permission_id'], $pahro_user_vols_permission_reqired)){
											$permissions_html .= "checked='checked'";
										}
										$permissions_html .= "value={$user_permissions_for_vols[$i]['permission_id']} />&nbsp;{$desc}</label>&nbsp;";                                            
				}												
			}
			$permissions_html .= "</fieldset>";
			
		}		
		return $permissions_html;
	}
	/* End of the function */
	
	/* This function will print staff permissions after post back in add mode */
	function display_staff_permissions_before_postback_in_edit_mode($user_permissions_for_staff, $owned_staff_permissions, $staff_perm_groups){
	
		$permissions_html = "";
		foreach($staff_perm_groups as $group){

			$permissions_html .= "<fieldset>                                                            
                                    <legend><span class='defaultFont'>{$group}</span></legend>";

			for($i=0; $i<count($user_permissions_for_staff); $i++){
				$descriptions = explode(" ", $user_permissions_for_staff[$i]['permission_description']);
				if (count($descriptions) == 3){
					$title = $descriptions[0]." ".$descriptions[1];
					$desc = $descriptions[2];				
				}elseif (count($descriptions) == 4){
					$title = $descriptions[0]." ".$descriptions[1]." ".$descriptions[2];
					$desc = $descriptions[3];				
				}else{
					$title = $descriptions[0];
					$desc = $descriptions[1];
				}					
				if ($title == $group){
					$permissions_html .= "<label class='defaultFont'><input id='chk_per_staff_{$user_permissions_for_staff[$i]['permission_id']}' 
															type='checkbox' name='pahro_user_staff_permission_reqired[]'";
										if (@in_array($user_permissions_for_staff[$i]['permission_id'], $owned_staff_permissions)){
											$permissions_html .= "checked='checked'";
										}
										$permissions_html .= "value={$user_permissions_for_staff[$i]['permission_id']} />&nbsp;{$desc}</label>&nbsp;";                                            
				}												
			}
			$permissions_html .= "</fieldset>";
		}	
		return $permissions_html;
	}
	/* End of the function */	
	
	/* This function will print vols permissions before post back in add mode */
	function display_vols_permissions_before_postback_in_edit_mode($user_permissions_for_vols, $owned_staff_permissions, $vols_perm_groups){
	
		$permissions_html = "";
		foreach($vols_perm_groups as $group){
		
			$permissions_html .= "<fieldset>                                                            
                                    <legend><span class='defaultFont'>{$group}</span></legend>";

			for($i=0; $i<count($user_permissions_for_vols); $i++){
				$descriptions = explode(" ", $user_permissions_for_vols[$i]['permission_description']);
				if (count($descriptions) == 3){
					$title = $descriptions[0]." ".$descriptions[1];
					$desc = $descriptions[2];	
				}elseif (count($descriptions) == 4){
					$title = $descriptions[0]." ".$descriptions[1]." ".$descriptions[2];
					$desc = $descriptions[3];				
				}else{
					$title = $descriptions[0];
					$desc = $descriptions[1];
				}					
				if ($title == $group){
					$permissions_html .= "<label class='defaultFont'><input id='chk_per_vol_{$user_permissions_for_vols[$i]['permission_id']}' 
															type='checkbox' name='pahro_user_vols_permission_reqired[]'";
										if (@in_array($user_permissions_for_vols[$i]['permission_id'], $owned_staff_permissions)){
											$permissions_html .= "checked='checked'";
										}
										$permissions_html .= "value={$user_permissions_for_vols[$i]['permission_id']} />&nbsp;{$desc}</label>&nbsp;";                                            
				}												
			}
			$permissions_html .= "</fieldset>";
			
		}		
		return $permissions_html;
	}
	/* End of the function */	
	
	/* This function will print staff permissions after post back in add mode */
	function display_staff_permissions_after_postback_in_edit_mode($user_permissions_for_staff, $pahro_user_staff_permission_reqired, $staff_perm_groups){
	
		$permissions_html = "";
		foreach($staff_perm_groups as $group){
		
			$permissions_html .= "<fieldset>                                                            
                                    <legend><span class='defaultFont'>{$group}</span></legend>";

			for($i=0; $i<count($user_permissions_for_staff); $i++){
				$descriptions = explode(" ", $user_permissions_for_staff[$i]['permission_description']);
				if (count($descriptions) == 3){
					$title = $descriptions[0]." ".$descriptions[1];
					$desc = $descriptions[2];				
				}elseif (count($descriptions) == 4){
					$title = $descriptions[0]." ".$descriptions[1]." ".$descriptions[2];
					$desc = $descriptions[3];				
				}else{
					$title = $descriptions[0];
					$desc = $descriptions[1];
				}					
				if ($title == $group){
					$permissions_html .= "<label class='defaultFont'><input id='chk_per_staff_{$user_permissions_for_staff[$i]['permission_id']}' 
															type='checkbox' name='pahro_user_staff_permission_reqired[]'";
										if (@in_array($user_permissions_for_staff[$i]['permission_id'], $pahro_user_staff_permission_reqired)){
											$permissions_html .= "checked='checked'";
										}
										$permissions_html .= "value={$user_permissions_for_staff[$i]['permission_id']} />&nbsp;{$desc}</label>&nbsp;";                                            
				}												
			}
			$permissions_html .= "</fieldset>";
		}	
		return $permissions_html;
	}
	/* End of the function */	
	
	/* This function will print vols permissions before post back in add mode */
	function display_vols_permissions_after_postback_in_edit_mode($user_permissions_for_vols, $pahro_user_vols_permission_reqired, $vols_perm_groups){
	
		$permissions_html = "";
		foreach($vols_perm_groups as $group){
		
			$permissions_html .= "<fieldset>                                                            
                                    <legend><span class='defaultFont'>{$group}</span></legend>";

			for($i=0; $i<count($user_permissions_for_vols); $i++){
				$descriptions = explode(" ", $user_permissions_for_vols[$i]['permission_description']);
				if (count($descriptions) == 3){
					$title = $descriptions[0]." ".$descriptions[1];
					$desc = $descriptions[2];
				}elseif (count($descriptions) == 4){
					$title = $descriptions[0]." ".$descriptions[1]." ".$descriptions[2];
					$desc = $descriptions[3];				
				}else{
					$title = $descriptions[0];
					$desc = $descriptions[1];
				}					
				if ($title == $group){
					$permissions_html .= "<label class='defaultFont'><input id='chk_per_vol_{$user_permissions_for_vols[$i]['permission_id']}' 
															type='checkbox' name='pahro_user_vols_permission_reqired[]'";
										if (@in_array($user_permissions_for_vols[$i]['permission_id'], $pahro_user_vols_permission_reqired)){
											$permissions_html .= "checked='checked'";
										}
										$permissions_html .= "value={$user_permissions_for_vols[$i]['permission_id']} />&nbsp;{$desc}</label>&nbsp;";                                            
				}												
			}
			$permissions_html .= "</fieldset>";
			
		}		
		return $permissions_html;
	}
	/* End of the function */	
	
	/* This function will insert a new note to the datbase */
	function insert_new_note($notes_params){
	
		global $connection;
		$sql = "INSERT INTO pahro__notes
							(note_owner_id, note_owner_type, note, added_by, added_date) 
							VALUES(".
									$notes_params['note_owner_id'].",'".
									$notes_params['note_owner_type']."','".
									CommonFunctions::mysql_preperation(trim($notes_params['note_text']))."',".
									$notes_params['added_by'].",'".
									$notes_params['added_date'].
							"')"; 
		AppModel::grab_db_function_class()->execute_query($sql);		
		return AppModel::grab_db_function_class()->return_last_inserted_id();
	}
	/* End of the function */	
	
	/* This function will insert notes categories related to the last inserted note */
	function insert_notes_categories($notes_categories_params){

		global $connection;
		for($i=0; $i<count($notes_categories_params['notes_categories']); $i++){
			$sql = "INSERT INTO pahro__notes_category_rel(note_id, note_cat_id, note_owner_section) 
								VALUES(".$notes_categories_params['note_id'].", ".$notes_categories_params['notes_categories'][$i].",'".$notes_categories_params['note_owner_section']."')";
			AppModel::grab_db_function_class()->execute_query($sql);										
		}		
	}
	/* End of the function */
	
	/* This function will retrieve all notes with other details regarding to the user */
	function retrieve_all_notes_owned_by_this_client($note_owner_id, $param_array, $note_owner_type, $curr_page_no = NULL, $sortBy = "", $sortMethod = "", $filtering=false, $notes_cats=""){

		global $connection;
		$sort = "";
		$limit = "";	
		$display_items = NO_OF_RECORDS_PER_PAGE_DEFAULT;					
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
			$sort = " ORDER BY pahro__notes.note_id DESC";				
		}
		$limit = " Limit {$start_no_sql}, {$end_no_sql}";					
		if (!$filtering){
			$sql = "SELECT pahro__notes.note_id, pahro__user.username, 
						   pahro__notes.note_owner_type, pahro__notes.note, pahro__notes.date_modified, pahro__notes.added_date, 
						   pahro__notes.modified_by 
					FROM pahro__notes
					LEFT JOIN pahro__user ON pahro__user.id = pahro__notes.added_by
					WHERE pahro__notes.note_owner_id = '{$note_owner_id}' AND pahro__notes.note_owner_type = 'STAFF' {$sort}{$limit}";
			$all_notes_in_edit_mode = AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $param_array);														
		}else{				
			$sql = "SELECT DISTINCT pahro__notes.*, pahro__user.username  
					FROM pahro__notes_category_rel 
					LEFT JOIN pahro__notes ON pahro__notes.note_id = pahro__notes_category_rel.note_id 
					LEFT JOIN pahro__user ON pahro__user.id = pahro__notes.added_by						
					WHERE pahro__notes.note_owner_id = '{$note_owner_id}' AND pahro__notes_category_rel.note_owner_section = 'STAFF' AND (";					
				for($i=0; $i<count($notes_cats); $i++){
					$sql .= "(pahro__notes_category_rel.note_cat_id =" .$notes_cats[$i].")";
					if ($i != count($notes_cats)-1){
						$sql .= " OR  ";
					}else{
						$sql .= " )";
					}
				}	
				$all_notes_in_edit_mode = AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $param_array);
		}		
		for($i=0; $i<count($all_notes_in_edit_mode); $i++){
			$all_notes_in_edit_mode[$i]['note_cats'] = $this->list_all_categories_for_a_given_note($all_notes_in_edit_mode[$i]['note_id']);
		}
		return $all_notes_in_edit_mode;
	}
	/* End of the fucntion */	

	/* This function will retrieve all notes count regarding to the user */
	function retrieve_all_notes_count_owned_by_this_client($note_owner_id, $param_array, $note_owner_type, $filtering=false, $notes_cats=""){
	
		global $connection;
		if (!$filtering){
		$sql = "SELECT pahro__notes.note_id, pahro__notes.note, pahro__user.username,
					   pahro__notes.added_date, pahro__notes.modified_by, pahro__notes.date_modified 
				FROM pahro__notes
				LEFT JOIN pahro__user ON pahro__user.id = pahro__notes.added_by
				WHERE pahro__notes.note_owner_id = ".$note_owner_id." AND pahro__notes.note_owner_type = 'STAFF' ORDER BY note_id DESC";	
		}else{								
			$sql = "SELECT DISTINCT pahro__notes.*, pahro__user.username  
					FROM pahro__notes_category_rel 
					LEFT JOIN pahro__notes ON pahro__notes.note_id = pahro__notes_category_rel.note_id 
					LEFT JOIN pahro__user ON pahro__user.id = pahro__notes.added_by						
					WHERE pahro__notes.note_owner_id = ".$note_owner_id." AND note_owner_section = 'STAFF' AND (";					
				for($i=0; $i<count($notes_cats); $i++){
					$sql .= "(pahro__notes_category_rel.note_cat_id =" .$notes_cats[$i].")";
					if ($i != count($notes_cats)-1){
						$sql .= " OR  ";
					}else{
						$sql .= " )";
					}
				}	
		}
		return AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query($sql));						
	}
	/* End of the fucntion */	
	
	/* This function will list all categories for a note which it owned */
	function list_all_categories_for_a_given_note($note_id){
	
		global $connection;
		$sql = "SELECT pahro__notes_category.note_cat_name, pahro__notes_category.note_cat_id  
				FROM pahro__notes_category_rel 
				LEFT JOIN pahro__notes_category ON pahro__notes_category.note_cat_id = pahro__notes_category_rel.note_cat_id 
				WHERE pahro__notes_category_rel.note_id = ".$note_id . " AND note_owner_section = 'STAFF'";
		$notes_category_param = array('note_cat_name', 'note_cat_id');
		return AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $notes_category_param);				
	}
	/* End of the function */

	/* This function will delete the pfac general details for a given pfac_id */
	function delete_selected_note($note_id, $pahro_id){
	
		global $connection;
		$sql = "DELETE FROM pahro__notes
						WHERE pahro__notes.note_id = {$note_id} AND pahro__notes.note_owner_id = {$pahro_id}";
		AppModel::grab_db_function_class()->execute_query($sql);						
	}
	/* End of the function */		
	
	/* This function will insert a new note to the datbase */
	function update_the_exsiting_note($notes_params, $note_id, $note_owner_type){
	
		global $connection;
		$sql = "UPDATE pahro__notes 
						SET 				
							note = '". CommonFunctions::mysql_preperation(trim($notes_params['note_text'])) . "', 
							date_modified = '". $notes_params['date_modified'] . "', 
							modified_by = '". $notes_params['modified_by'] ."' 
						 WHERE note_id = {$note_id} AND note_owner_id = {$notes_params['note_owner_id']} AND note_owner_type = '{$note_owner_type}'";
		AppModel::grab_db_function_class()->execute_query($sql);		
	}
	/* End of the function */	
	
	/* This function will retrieve note category name for the its cat id */
	function retrieve_full_details_of_selected_note($note_id, $param_array){
	
		global $connection;
		$sql = "SELECT pahro__notes.note_id, pahro__notes.note, pahro__notes.added_date, pahro__user.username, pahro__notes.modified_by, pahro__notes.date_modified
				FROM pahro__notes 
				LEFT JOIN pahro__user ON pahro__user.id = pahro__notes.added_by
				WHERE pahro__notes.note_id = {$note_id}";	
		$notes_details = AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $param_array);									
		$notes_categories = array("notes_categories" => $this->list_all_categories_for_a_given_note($notes_details[0]['note_id']));
		return (array_merge($notes_details[0], $notes_categories));
	}
	/* End of the function */	
	
	/* This function will remove prevous notes categories relations for a given note id */
	function remove_previous_notes_categories_relation($note_id){
	
		global $connection;
		return AppModel::grab_db_function_class()->execute_query("DELETE FROM pahro__notes_category_rel WHERE note_id = ".$note_id . " AND note_owner_section = 'STAFF'");						
	}
	/* End of the fucntion */
	
	/* This function will check whethr the client note id exist in the database before any further action */
	function check_note_id_exist($note_id){
	
		global $connection;
		return (AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query("SELECT note_id FROM pahro__notes WHERE note_id = {$note_id}")) != "") ? true : false;																
	}
	/* End of the fucntion */
	
	/* This function will check the given note id is owned by the given client */
	function check_note_id_owned_by_the_correct_client($pahro_id, $note_id){	

		global $connection;
		$pahro_id_in_db = AppModel::grab_db_function_class()->return_single_result_from_mysql_resource(AppModel::grab_db_function_class()->execute_query("SELECT note_owner_id FROM pahro__notes WHERE note_id = {$note_id}"), 0);																
		return ($pahro_id == $pahro_id_in_db) ? true : false;
	}		
	/* End of the function */	
	
	/* This function will retreive all volunteers currently involved with a given case */
	function rerieve_assigned_cases($volunteer_id){
	
		global $connection;
		$sql = "SELECT case__main.reference_number, case__main.case_name, case__main.status 	
				FROM case__main
				LEFT JOIN case__volunteers ON case__volunteers.case_id = case__main.reference_number
				LEFT JOIN pahro__user ON pahro__user.id = case__volunteers.case_id
				WHERE case__volunteers.vol_id = {$volunteer_id}";
				$params = array("reference_number", "case_name", "status");
		return (AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $params));
	}
	/* End of the function */
	
	/* This function will retreive all volunteers currently involved with a given case */
	function rerieve_responsible_cases_for_staff($staff_id){
	
		global $connection;
		$sql = "SELECT reference_number
				FROM case__main
				WHERE staff_responsible = {$staff_id}";
		return (AppModel::grab_db_function_class()->result_to_single_array_of_data(AppModel::grab_db_function_class()->execute_query($sql), "reference_number"));
	}
	/* End of the function */

	/* This function will retreive all countries listed in the users adding section */
	function retrieve_all_countires_for_users($param_array){
	
		global $connection;
		$sql = "SELECT pahro__country.country_id, pahro__country.country_name
				FROM pahro__country";	
		return AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $param_array);									
	}
	/* End of the function */	
	
	/* This fucntion will grab the username as note modified person from the users table */
	function grab_the_username_related_note_as_modifield_person($modified_by){
	
		global $connection;
		$sql = "SELECT username 
				FROM pahro__user 
				LEFT JOIN pahro__notes ON pahro__notes.modified_by = pahro__user.id 
				WHERE pahro__notes.modified_by = {$modified_by}";
		return (AppModel::grab_db_function_class()->return_single_result_from_mysql_resource(AppModel::grab_db_function_class()->execute_query($sql), 0));																
	}  
	/* End of the function */
	
	/* This function will retrieve the case owned volunteers from the case volunteer table */
	function is_currently_owned_in_country($user_id, $counry_set_id){
	
		global $connection;
		$countries_db = AppModel::grab_db_function_class()->result_to_single_array_of_data(AppModel::grab_db_function_class()->execute_query("SELECT country_id FROM pahro__users_countries WHERE user_id = ".$user_id), "country_id");		
		return (in_array($counry_set_id, $countries_db)) ? true : false;
	}
	/* End of the function */
	
	/* This fucntion will search for volunteers who have involved with the Human Right Project */
	function search_for_volunteers_by_given_parameters($tagos_search, $params, $curr_page_no = NULL){

		global $connection;
		$limit = "";		
		$where = "";
		$sql = "";		
		
		$display_items = NO_OF_RECORDS_PER_PAGE_DEFAULT;					
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
		$limit = " Limit {$start_no_sql}, {$end_no_sql}";
		
		$result_array = array();				
		$sql = "SELECT volunteers.id, volunteers.firstname, volunteers.surname, volunteers.othername, volunteers.emergencyname, programs.program AS program1, programs2.program AS program2, contact__main.email 
				FROM volunteers 
				LEFT JOIN contact__main ON contact__main.id = volunteers.id				
				LEFT JOIN arrange_volunteer ON arrange_volunteer.vol_id = volunteers.id
				LEFT JOIN programs ON programs.id = arrange_volunteer.project
				LEFT JOIN programs2 ON programs2.id = arrange_volunteer.project2 "; 

		if (
			(array_key_exists("vola_project", $tagos_search)) && 
			(array_key_exists("vola_first_name", $tagos_search)) &&
			(array_key_exists("vola_last_name", $tagos_search))
		   ){
			$where .= " WHERE arrange_volunteer.project = ".$tagos_search['vola_project']." OR arrange_volunteer.project2 = ".$tagos_search['vola_project'].
						" AND volunteers.firstname LIKE '%".strtolower(trim($tagos_search['vola_first_name']))."%' ".
						" AND volunteers.surname LIKE '%".strtolower(trim($tagos_search['vola_last_name']))."%' ";
			
		}elseif 
			(
				(array_key_exists("vola_project", $tagos_search)) || 
				(array_key_exists("vola_first_name", $tagos_search)) ||
				(array_key_exists("vola_last_name", $tagos_search))
			)	
		{
			$where .= " WHERE arrange_volunteer.project = ".$tagos_search['vola_project']." OR arrange_volunteer.project2 = ".$tagos_search['vola_project'].
						" OR volunteers.firstname LIKE '%".strtolower(trim($tagos_search['vola_first_name']))."%' ".
						" OR volunteers.surname LIKE '%".strtolower(trim($tagos_search['vola_last_name']))."%' ";
		}else{
			$where .= "";
		}		
		
		$sql .= "{$where} ORDER BY volunteers.id DESC {$limit}";

		mysql_select_db("volunteers", $_SESSION['connection2']);		
		$result = mysql_query($sql, $_SESSION['connection2']) or die(mysql_error($_SESSION['connection2']));
		mysql_select_db("pahro", $connection);						
		$i=0;
		while($row = @mysql_fetch_array($result)){
			foreach($params as $key){
				$result_array[$i][$key] = $row[$key];
			}	
			$i++;			
		}		
		return $result_array;		
	}
	/* End of the function */
	
	/* This fucntion will return the search result count for volunteers who have involved with the Human Right Project */
	function return_search_result_count_for_volunteers_by_given_parameters($tagos_search, $params){

		global $connection;
		$where = "";
		$sql = "";		
		
		$result_array = array();				
		$sql = "SELECT volunteers.id, volunteers.firstname, volunteers.surname, volunteers.othername, volunteers.emergencyname, programs.program AS program1, programs2.program AS program2, contact__main.email 
				FROM volunteers 
				LEFT JOIN contact__main ON contact__main.id = volunteers.id				
				LEFT JOIN arrange_volunteer ON arrange_volunteer.vol_id = volunteers.id
				LEFT JOIN programs ON programs.id = arrange_volunteer.project
				LEFT JOIN programs2 ON programs2.id = arrange_volunteer.project2 "; 

		if (
			(array_key_exists("vola_project", $tagos_search)) && 
			(array_key_exists("vola_first_name", $tagos_search)) &&
			(array_key_exists("vola_last_name", $tagos_search))
		   ){
			$where .= " WHERE arrange_volunteer.project = ".$tagos_search['vola_project']." OR arrange_volunteer.project2 = ".$tagos_search['vola_project'].
						" AND volunteers.firstname LIKE '%".strtolower(trim($tagos_search['vola_first_name']))."%' ".
						" AND volunteers.surname LIKE '%".strtolower(trim($tagos_search['vola_last_name']))."%' ";
			
		}elseif 
			(
				(array_key_exists("vola_project", $tagos_search)) || 
				(array_key_exists("vola_first_name", $tagos_search)) ||
				(array_key_exists("vola_last_name", $tagos_search))
			)	
		{
			$where .= " WHERE arrange_volunteer.project = ".$tagos_search['vola_project']." OR arrange_volunteer.project2 = ".$tagos_search['vola_project'].
						" OR volunteers.firstname LIKE '%".strtolower(trim($tagos_search['vola_first_name']))."%' ".
						" OR volunteers.surname LIKE '%".strtolower(trim($tagos_search['vola_last_name']))."%' ";
		}else{
			$where .= "";
		}		
		
		$sql .= "{$where} ORDER BY volunteers.id DESC";
				
		mysql_select_db("volunteers", $_SESSION['connection2']);		
		$result = mysql_query($sql, $_SESSION['connection2']) or die(mysql_error($_SESSION['connection2']));
		mysql_select_db("pahro", $connection);						
		$i=0;
		while($row = @mysql_fetch_array($result)){
			foreach($params as $key){
				$result_array[$i][$key] = $row[$key];
			}	
			$i++;			
		}		
		return count($result_array);		
	}
	/* End of the function */
	
	/* This function will grab the full details for the provided volunteer id */
	function retrieve_details_for_volunteer($vol_id, $params){
	
		global $connection;
		$sql = "SELECT volunteers.id, volunteers.firstname, volunteers.surname, contact__main.email 
				FROM volunteers 
				LEFT JOIN contact__main ON contact__main.id = volunteers.id
				WHERE volunteers.id = ".$vol_id; 
		mysql_select_db("volunteers", $_SESSION['connection2']);		
		$result = mysql_query($sql, $_SESSION['connection2']) or die(mysql_error($_SESSION['connection2']));
		mysql_select_db("pahro", $connection);						
		$i=0;
		while($row = @mysql_fetch_array($result)){
			foreach($params as $key){
				$result_array[$i][$key] = $row[$key];
			}	
			$i++;			
		}		
		return $result_array;		
	} 
	/* End of the function */
	
	/* This function will check the user exist as a previously registered volunteer in the database */
	function check_volunteer_exist_prior_to_register($vol_id){
	
		global $connection;
		return (AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query("SELECT tagos_id FROM pahro__user WHERE tagos_id = {$vol_id}")) == "") ? true : false;																
	}
	/* End of the fucntion */
	
	/* This function will search for a particular volunteer and grab the searched volunteer details */
	function search_for_a_particular_volunteer_by_given_name($vol_name){

		global $connection;		
		$sql = "SELECT id FROM volunteers WHERE firstname = '".$vol_name['firstname']."' AND surname = '".$vol_name['surname']."'"; 
		mysql_select_db("volunteers", $_SESSION['connection2']);		
		$result = mysql_query($sql, $_SESSION['connection2']) or die(mysql_error($_SESSION['connection2']));
		$related_vol_id = mysql_result($result, 0);		
		mysql_select_db("pahro", $connection);						
		return ($related_vol_id != "") ? $related_vol_id : false;
	} 
	/* End of the function */
	
	/* This function will search for a particular volunteer and grab the searched volunteer details */
	function check_the_retrieved_volunteer_id_currently_invoved_with_hr_projects($vol_id){

		global $connection;		
		$sql = "SELECT vol_id FROM arrange_volunteer WHERE ((project = 210 OR project = 225) OR (project2 = 210 OR project2 = 225)) AND vol_id = ".$vol_id;
		mysql_select_db("volunteers", $_SESSION['connection2']);		
		$result = mysql_query($sql, $_SESSION['connection2']) or die(mysql_error($_SESSION['connection2']));
		$related_vol_id = mysql_result($result, 0);		
		mysql_select_db("pahro", $connection);						
		if ($related_vol_id != "") $_SESSION['vol_id'] = $related_vol_id;
		return ($related_vol_id != "") ? true : false;
	} 
	/* End of the function */
	
	/* This function will retrieve all voulnteers that undertake the human rights projects */
	function retrieve_volunteers_involved_with_hr_projects($country_params){

		global $connection;
		// Other variables
		$vols_from_volunteers_and_mytpa = array();
		$vols_from_pahro = array();
		$refined_vols_from_volunteers_and_mytpa = array();
		// if the users country list having two or more countries then the sql like this
		$concat_sql = "(volunteers.aboutplacement.FirstDestination1 = ". $country_params." OR volunteers.aboutplacement.SecondDestination2 = ". $country_params.")";
		// First grab the volunteers who have invloved with HR projects
		$sql = "SELECT volunteers.volunteers.id AS mytpa_id, volunteers.volunteers.firstname, volunteers.volunteers.surname, 
						volunteers.volunteers.othername, volunteers.volunteers.emergencyname, volunteers.programs.program AS program1, 
						volunteers.programs2.program AS program2, volunteers.contact__main.email, volunteers.aboutplacement.FirstDestination1, volunteers.aboutplacement.SecondDestination2
				FROM volunteers.volunteers 
				LEFT JOIN volunteers.contact__main ON volunteers.contact__main.id = volunteers.volunteers.id				
				LEFT JOIN volunteers.aboutplacement ON volunteers.aboutplacement.vol_id = volunteers.volunteers.id
				LEFT JOIN volunteers.programs ON volunteers.programs.id = volunteers.aboutplacement.FirstProgramme1
				LEFT JOIN volunteers.programs2 ON volunteers.programs2.id = volunteers.aboutplacement.SecondProgramme1 
				WHERE (
					   (volunteers.aboutplacement.FirstProgramme1 = 210 OR volunteers.aboutplacement.FirstProgramme1 = 225) OR 
					   (volunteers.aboutplacement.SecondProgramme1 = 210 OR volunteers.aboutplacement.SecondProgramme1 = 225)
					  ) AND ". $concat_sql;
		mysql_select_db("volunteers");
		$result = mysql_query($sql, $_SESSION['connection2']) or die(mysql_error($_SESSION['connection2']));
		// Keeping the pahro connection
		mysql_select_db("pahro");
		$params = array('mytpa_id', 'firstname', 'surname', 'othername', 'emergencyname', 'program1', 'program2', 'email', 'FirstDestination1', 'SecondDestination2');
		$i=0;
		while($row = @mysql_fetch_array($result)){
			foreach($params as $key){
				$vols_from_volunteers[$row['mytpa_id']][$key] = $row[$key];
			}	
			$i++;			
		}	
		// Then get their usernames and passwords and merging with existing data
		mysql_select_db("mytpa");		
		foreach($vols_from_volunteers as $each_vol){		
			
			$sql1 = "SELECT mytpa.users.user, mytpa.users.password FROM mytpa.users WHERE mytpa.users.vol_id = ". $each_vol['mytpa_id'];
			$result1 = mysql_query($sql1, $_SESSION['connection2']) or die(mysql_error($_SESSION['connection2']));			
			$params1 = array('user', 'password');			
			$refined_volunteers = AppModel::grab_db_function_class()->result_to_array_for_few_fields($result1, $params1);										
			$vols_from_volunteers_and_mytpa[] = array_merge($each_vol, $refined_volunteers[0]);
		}						
		
		$vols_from_volunteers_and_mytpa_filter = array();	
		foreach($vols_from_volunteers_and_mytpa as $vol){
			if($vol){
				$vols_from_volunteers_and_mytpa_filter[] = $vol;
			}
		}
		// Refine the volunteers from mytpa + volunteers by assign the tagos id
		foreach($vols_from_volunteers_and_mytpa_filter as $each_vol){
		
			$refined_vols_from_volunteers_and_mytpa[$each_vol['mytpa_id']] = $each_vol;
		}
		// Again keeping the pahro connection
		mysql_select_db("pahro");		
		// Then do the splitting of volunteers in to two sections as who have already registered and who have not already registered with pahro
		$sql2 = "SELECT pahro.pahro__user.id, pahro.pahro__user.username, pahro.pahro__user.first_name, pahro.pahro__user.last_name,
					   pahro.pahro__user.email, pahro.pahro__user.tagos_id, pahro.pahro__user.created_at, pahro.pahro__user.last_login
				FROM pahro.pahro__user WHERE pahro.pahro__user.tagos_id != 0";
		$result2 = mysql_query($sql2, $connection) or die($sql2);			
		$params2 = array('id', 'username', 'first_name', 'last_name', 'email', 'tagos_id', 'created_at', 'last_login');			
		$i=0;
		while($row2 = @mysql_fetch_array($result2)){
			foreach($params2 as $key){
				$vols_from_pahro[$row2['tagos_id']][$key] = $row2[$key];
			}	
			$i++;			
		}
		// Filtering the volunteers who are not in the pahro database alrady
		if(count($vols_from_pahro)>0){
			foreach($vols_from_pahro as $each_vol){
				unset($refined_vols_from_volunteers_and_mytpa[$each_vol['tagos_id']]);
			}
		}	
		// return both the volunteers who are in the pahro database and who are not in the pahro database
		return $refined_vols_from_volunteers_and_mytpa;	
	}
	/* End of the fucntion */
	
	/* This function will retrieve previously registered volunteers with the pahro */
	function retrieve_prevoulsly_imported_volunteers($curr_page_no, $sortBy = "", $sortMethod = "", $user_country_params, $curr_country){

		global $connection;
		// Params need to limit the sql
		$limit = "";		
		$sql = "";		
		$sort = "";
		global $start_no_sql;
		global $end_no_sql;
		
		$display_items = NO_OF_RECORDS_PER_PAGE_DEFAULT;					
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
			$sort = " ORDER BY id DESC";				
		}
		//$limit = " Limit {$start_no_sql}, {$end_no_sql}";
		$vols_from_pahro = array();
		if (count($user_country_params) > 1){
			$concat_sql = " ((pahro__users_countries.country_id = 1) OR (pahro__users_countries.country_id = 2)) ";
		}else{
			$concat_sql = "pahro__users_countries.country_id = ".$user_country_params[0];			
		}
		$sql = "SELECT pahro__user.*, pahro__country.country_name 
				FROM pahro__user 
				JOIN pahro__users_countries ON pahro__users_countries.user_id = pahro__user.id 
				JOIN pahro__country ON pahro__country.country_id = pahro__users_countries.country_id
				WHERE pahro__users_countries.country_id = {$curr_country} AND pahro__user.id = pahro__users_countries.user_id AND ".$concat_sql." AND pahro__user.tagos_id != 'NULL' ".$sort;
		$result = mysql_query($sql, $connection) or die(mysql_error($connection));			
		$params = array('id', 'username', 'first_name', 'last_name', 'email', 'tagos_id', 'created_at', 'last_login', 'country_name');			
		$i=0;
		while($row = @mysql_fetch_array($result)){
			foreach($params as $key){
				$vols_from_pahro[$i][$key] = $row[$key];
			}	
			$vols_from_pahro[$i]['country_list'] = $this->retrieve_each_user_countries($row['id']);												
			$i++;			
		}
		//removing duplicates
		$temp_ary = array();
			foreach($vols_from_pahro as $ele){
			$temp_ary[] = $ele['id'];
		}
		$temp_ary = array_keys(array_unique($temp_ary));
		
		$final_result = array();
		foreach($temp_ary as $id){
			$final_result[] = $vols_from_pahro[$id];
		}
		$final_result = array_slice($final_result, $start_no_sql, $end_no_sql);	
		return $final_result;
	}
	/* End of the function */
	
	/* This function will retrieve previously registered volunteers with the pahro */
	function retrieve_count_of_prevoulsly_imported_volunteers($user_country_params, $curr_country){
	
		global $connection;
		$vols_from_pahro = array();
		if (count($user_country_params) > 1){
			$concat_sql = " ((pahro__users_countries.country_id = 1) OR (pahro__users_countries.country_id = 2)) ";
		}else{
			$concat_sql = "pahro__users_countries.country_id = ".$user_country_params[0];			
		}
		$sql = "SELECT pahro__user.*, pahro__country.country_name 
				FROM pahro__user 
				JOIN pahro__users_countries ON pahro__users_countries.user_id = pahro__user.id 
				JOIN pahro__country ON pahro__country.country_id = pahro__users_countries.country_id
				WHERE pahro__users_countries.country_id = {$curr_country} AND pahro__user.id = pahro__users_countries.user_id AND ".$concat_sql." AND pahro__user.tagos_id != 'NULL'";
		$result = mysql_query($sql, $connection) or die(mysql_error($connection));			
		$params = array('id', 'username', 'first_name', 'last_name', 'email', 'tagos_id', 'created_at', 'last_login', 'country_name');			
		$i=0;
		while($row = @mysql_fetch_array($result)){
			foreach($params as $key){
				$vols_from_pahro[$i][$key] = $row[$key];
			}	
			$vols_from_pahro[$i]['country_list'] = $this->retrieve_each_user_countries($row['id']);												
			$i++;			
		}
		
		//removing duplicates
		$temp_ary = array();
			foreach($vols_from_pahro as $ele){
			$temp_ary[] = $ele['id'];
		}
		$temp_ary = array_keys(array_unique($temp_ary));
		
		$final_result = array();
		foreach($temp_ary as $id){
			$final_result[] = $vols_from_pahro[$id];
		}
		return count($final_result);
	}
	/* End of the function */
	
	/* This function will retrieve each user's countries */
	function retrieve_each_user_countries($user_id){
	
		global $connection;
		return AppModel::grab_db_function_class()->result_to_single_array_of_data(AppModel::grab_db_function_class()->execute_query("SELECT country_id FROM pahro__users_countries WHERE user_id = ".$user_id), "country_id");		
	}
	/* End of the function */
	
	/* This function will retrieve country list for each volunteers */
	function retrieve_volunteers_country_list($volunteer_id){
	
		global $connection;
		mysql_select_db("volunteers");
		$sql = "SELECT volunteers.aboutplacement.FirstDestination1, volunteers.aboutplacement.SecondDestination2 FROM volunteers.aboutplacement WHERE vol_id = ".$volunteer_id;
		$result = mysql_query($sql, $_SESSION['connection2']) or die(mysql_error($_SESSION['connection2']));			
		$params = array('FirstDestination1', 'SecondDestination2');			
		$i=0;
		while($row = @mysql_fetch_array($result)){
			foreach($params as $key){
				$vol_country_list[] = $row[$key];
			}	
			$i++;			
		}				
		mysql_select_db("pahro");
		$filter = array();
		if(in_array(4, $vol_country_list)){
			$filter[] = 2;
		}
		if(in_array(13, $vol_country_list)){
			$filter[] = 1;
		}
		return $filter;
	}
	/* End of the function */
	
	/* This function will keep the trak for the import feature interaction with the ogged user */
	function keep_the_track_for_import_interaction($interaction_params){
	
		global $connection;
		$sql = "INSERT INTO pahro__import_log(last_clicked_date ,last_clicked_user, no_of_vols_imported, interaction_by) 
							VALUES('".
									$interaction_params['last_clicked_date']."',".
									$interaction_params['last_clicked_user'].",".
									$interaction_params['no_of_vols_imported'].",'".
									$interaction_params['interaction_by'].									
								 "')"; 
		AppModel::grab_db_function_class()->execute_query($sql);		
	} 
	/* End of the function */
	
	/* This function will retrieve the track for the import and user interaction */
	function retrieve_the_import_interaction_records($for_which_country){
	
		global $connection;
		$sql = "SELECT pahro__import_log.*, pahro__user.username FROM pahro__import_log LEFT JOIN pahro__user ON pahro__user.id = pahro__import_log.last_clicked_user WHERE interaction_by = '".$for_which_country."' ORDER BY last_clicked_date DESC Limit 0, 15";
		$params = array('last_clicked_date', 'username', 'no_of_vols_imported');
		return AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $params);						
	} 
	/* End of the function */
	
	/* This function will unlink the cases assigned for a particular volunteer */
	function unlink_assigned_cases_prior_to_delete($vol_id){
	
		global $connection;
		AppModel::grab_db_function_class()->execute_query("DELETE FROM case__volunteers WHERE vol_id = ".$vol_id);						
	}
	/* End of the function */	
	
	/* This fucntion will check the requested current displayed / selected country and against the each client country */
	function check_pahro_country_with_curr_selected_country($curr_country, $pahro_id){

		global $connection;
		$sql = "SELECT pahro__users_countries.country_id FROM pahro__users_countries LEFT JOIN pahro__user ON pahro__users_countries.user_id = pahro__user.id WHERE pahro__users_countries.user_id = ".$pahro_id;
		$pahro_owned_country = (AppModel::grab_db_function_class()->result_to_single_array_of_data(AppModel::grab_db_function_class()->execute_query($sql), 'country_id'));																		
		return (count($pahro_owned_country) > 1) ? (in_array($curr_country, $pahro_owned_country)) : ($curr_country == $pahro_owned_country[0]);
	}
	/* End of the fucntion */	
}
?>