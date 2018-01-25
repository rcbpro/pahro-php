<?php

class CounterPartyModel{

	/* This function will insert counter party details in to database */
	function insert_counter_party_details($counter_party_details_param, $country){

		global $connection;
		$sql = "INSERT INTO counter_party__main(cp_owned_country_id, company_name, first_name, last_name, title, resident_address, postal_address, land_phone,
												 mobile_phone, email, comment) 
							VALUES(".
									$country.",'".							
									CommonFunctions::mysql_preperation(trim($counter_party_details_param['company_name']))."','".							
									CommonFunctions::mysql_preperation(trim($counter_party_details_param['first_name']))."','".
									CommonFunctions::mysql_preperation(trim($counter_party_details_param['last_name']))."','".
									CommonFunctions::mysql_preperation(trim($counter_party_details_param['title']))."','".
									CommonFunctions::mysql_preperation(trim($counter_party_details_param['resident_address']))."','".
									CommonFunctions::mysql_preperation(trim($counter_party_details_param['postal_address']))."','".
									CommonFunctions::mysql_preperation(trim($counter_party_details_param['land_phone']))."','".								
									CommonFunctions::mysql_preperation(trim($counter_party_details_param['mobile_phone']))."','".
									CommonFunctions::mysql_preperation(trim($counter_party_details_param['email']))."','".
									CommonFunctions::mysql_preperation(trim($counter_party_details_param['comment'])).
								 "')"; 
		AppModel::grab_db_function_class()->execute_query($sql);		
		return AppModel::grab_db_function_class()->return_last_inserted_id();		
	}
	/* End of the function */
	
	/* This function wiil insert volunteers id who currently invoved with newly inseted case */
	function insert_owned_cases($counter_party_id, $case_id_params){

		global $connection;
		if (count($case_id_params) != 1){
			for($i=0; $i<count($case_id_params); $i++){			
				if (!empty($case_id_params[$i])){
					$sql = "INSERT INTO counter_party__cases(counter_party_id, case_id) 
										VALUES(".
												$counter_party_id.",'".
												trim($case_id_params[$i]).
											  "')"; 
					AppModel::grab_db_function_class()->execute_query($sql);
				}					
			}
		}else{
			$sql = "INSERT INTO counter_party__cases(counter_party_id, case_id) 
								VALUES(".
										$counter_party_id.",'".
										$case_id_params.
									  "')"; 
			AppModel::grab_db_function_class()->execute_query($sql);				
		}	
	}
	/* End of the functin */
	
	/* This function will retrieve all case ids owned by this client */
	function retrieve_owned_case_ids_by_this_client($client_id){
	
		global $connection;
		return (AppModel::grab_db_function_class()->result_to_single_array_of_data(AppModel::grab_db_function_class()->execute_query("SELECT case_id FROM client__cases WHERE client__cases.client_id = {$client_id}"), "case_id"));														
	} 
	/* End of the function */

	/* This function will retrieve all case ids owned by this client */
	function delete_previous_owned_case_ids_by_this_counter_party($counter_party_id){
	
		global $connection;
		return AppModel::grab_db_function_class()->execute_query("DELETE FROM counter_party__cases WHERE counter_party_id = {$counter_party_id}");														
	} 
	/* End of the function */
	
	/* This function will check whether the client id exist in the database */
	function check_case_id_exist_in_system($case_id){
	
		global $connection;
		return (AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query("SELECT reference_number FROM case__main WHERE reference_number = '{$case_id}'")) != 0) ? true : false;																
	}
	/* End of the function */
	
	/* This function will check whether the client id exist in the database */
	function check_counter_party_id_exist_in_system($counter_party_id ){
	
		global $connection;
		return (AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query("SELECT counter_party_id FROM counter_party__main WHERE counter_party_id = {$counter_party_id }")) != 0) ? true : false;																
	}
	/* End of the function */
	
	/* This function will insert addbook details in to datbase */
	function update_counter_party_details($counter_party_details_param, $counter_party_id, $country){

		global $connection;
		$sql = "UPDATE counter_party__main 
						SET 
							cp_owned_country_id = ". $country . ", 													
							company_name = '". CommonFunctions::mysql_preperation(trim($counter_party_details_param['company_name'])) . "', 
							first_name = '". CommonFunctions::mysql_preperation(trim($counter_party_details_param['first_name'])) . "', 
							last_name = '". CommonFunctions::mysql_preperation(trim($counter_party_details_param['last_name'])) . "', 													
							title = '". CommonFunctions::mysql_preperation(trim($counter_party_details_param['title'])) . "', 
							resident_address = '". CommonFunctions::mysql_preperation(trim($counter_party_details_param['resident_address'])) . "', 
							postal_address = '". CommonFunctions::mysql_preperation(trim($counter_party_details_param['postal_address'])) . "', 
							land_phone = '". CommonFunctions::mysql_preperation(trim($counter_party_details_param['land_phone'])) . "', 													
							mobile_phone = '". CommonFunctions::mysql_preperation(trim($counter_party_details_param['mobile_phone'])) . "', 
							email = '". CommonFunctions::mysql_preperation(trim($counter_party_details_param['email'])) . "', 
							comment = '". CommonFunctions::mysql_preperation(trim($counter_party_details_param['comment'])) . "' 
						 WHERE counter_party_id = {$counter_party_id}";
		AppModel::grab_db_function_class()->execute_query($sql);		
	}
	/* End of the function */	
	
	/* This function will retrieve all pfa clients in to display */
	function display_all_counter_partys($params, $curr_page_no = NULL, $sortBy = "", $sortMethod = "", $user_country_param, $curr_country){
	
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
			$sort = " ORDER BY counter_party__main.counter_party_id DESC";				
		}
		$limit = " Limit {$start_no_sql}, {$end_no_sql}";				
		$sql = "SELECT counter_party__main.*, pahro__country.country_name
				FROM counter_party__main 
				LEFT JOIN pahro__country ON pahro__country.country_id = counter_party__main.cp_owned_country_id
				WHERE counter_party__main.cp_owned_country_id = {$curr_country} 
				{$sort}{$limit}";	
		$full_results = AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $params);				
		foreach($full_results as $each_result){
			if (in_array($each_result['cp_owned_country_id'], $user_country_param)){
				$final_result[] = $each_result;
			}
		}
		return $final_result;
	}
	/* End of the fucntion */
	
	/* This function will retrieve the count for all pfa clients in to display */
	function display_counter_partys_all_count($params, $user_country_param, $curr_country){
	
		global $connection;
		$sql = "SELECT * FROM counter_party__main WHERE cp_owned_country_id = {$curr_country} ORDER BY counter_party_id DESC";
		$full_results = AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $params);				
		foreach($full_results as $each_result){
			if (in_array($each_result['cp_owned_country_id'], $user_country_param)){
				$final_result[] = $each_result;
			}
		}
		return count($final_result);
	}
	/* End of the fucntion */	
	
	/* This function will remove prevous notes categories relations for a given note id */
	function remove_previous_notes_categories_relation($note_id){
	
		global $connection;
		return AppModel::grab_db_function_class()->execute_query("DELETE FROM pahro__notes_category_rel WHERE note_id = ".$note_id . " AND note_owner_section = 'COUNTER-PARTY'");						
	}
	/* End of the fucntion */
	
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
	
	/* This function will list all categories for a note which it owned */
	function list_all_categories_for_a_given_note($note_id){
	
		global $connection;
		$sql = "SELECT pahro__notes_category.note_cat_name, pahro__notes_category.note_cat_id  
				FROM pahro__notes_category_rel 
				LEFT JOIN pahro__notes_category ON pahro__notes_category.note_cat_id = pahro__notes_category_rel.note_cat_id 
				WHERE pahro__notes_category_rel.note_id = ".$note_id . " AND note_owner_section = 'COUNTER-PARTY'";
		$notes_category_param = array('note_cat_name', 'note_cat_id');
		return AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $notes_category_param);				
	}
	/* End of the function */
	
	/* This function will check whether the given address book id from the get value is exist in database */
	function check_case_id_exist($case_id){
	
		global $connection;
		return (AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query("SELECT reference_number FROM case__main WHERE reference_number = '{$case_id}'")) != 0) ? true : false;																
	}
	/* End of the function */
	
	/* This function will retrieve full details per each address book contact */
	function retrieve_full_details_per_each_counter_party($counter_party_id){

		global $connection;
		$params = array('counter_party_id', 'cp_owned_country_id', 'first_name', 'last_name', 'title', 'company_name', 'resident_address', 'postal_address', 'land_phone', 'mobile_phone', 
						'email', 'comment');	
		return AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query("SELECT * FROM counter_party__main WHERE counter_party_id = {$counter_party_id}"), $params);							
	}
	/* End of the function */	
	
	/* This function will insert log details every time against the user action */
	function keep_track_of_activity_log_in_counter_party($logParmas){
	
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
	
	/* This function will insert a new note to the datbase */
	function insert_new_note($notes_params){
	
		global $connection;
		$sql = "INSERT INTO pahro__notes
							(note_owner_id, note_owner_type, note, added_by, added_date) 
							VALUES(".
									$notes_params['counter_party_id'].",'".
									$notes_params['note_owner_type']."','".
									CommonFunctions::mysql_preperation(trim($notes_params['note']))."',".									
									$notes_params['added_by'].",'".
									$notes_params['added_date'].
								  "')";
		AppModel::grab_db_function_class()->execute_query($sql);	
		return AppModel::grab_db_function_class()->return_last_inserted_id();
	}
	/* End of the function */

	/* This function will insert a new note to the datbase */
	function update_the_exsiting_note($notes_params, $note_id){
	
		global $connection;
		$sql = "UPDATE pahro__notes 
						SET 				
							note = '". CommonFunctions::mysql_preperation(trim($notes_params['note'])) . "', 
							date_modified = '". $notes_params['date_modified'] . "', 
							modified_by = ". $notes_params['modified_by'] ." 
						 WHERE note_id = {$note_id} AND note_owner_id = {$notes_params['counter_party_id']} AND note_owner_type = '{$notes_params['note_owner_type']}'";
		AppModel::grab_db_function_class()->execute_query($sql);		
	}
	/* End of the function */
	
	/* This function will delete the pfac general details for a given pfac_id */
	function delete_selected_note($note_id, $counter_party_id, $note_owner_type){
	
		global $connection;
		AppModel::grab_db_function_class()->execute_query("DELETE FROM pahro__notes WHERE note_id = {$note_id} AND note_owner_id = {$counter_party_id} AND note_owner_type = '{$note_owner_type}'");						
	}
	/* End of the function */		

	/* This function will delete the pfac general details for a given pfac_id */
	function delete_owned_note($cp_id, $note_owner_type){
	
		global $connection;
		AppModel::grab_db_function_class()->execute_query("DELETE FROM pahro__notes WHERE note_owner_id = {$cp_id} AND note_owner_type = '{$note_owner_type}'");						
	}
	/* End of the function */		

	/* This function will retrieve all notes with other details regarding to the client */
	function retrieve_all_notes_owned_by_this_counter_party($counter_party_id, $param_array, $note_owner_type){
	
		global $connection;
		$all_notes_in_add_mode = AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query("SELECT * FROM pahro__notes WHERE note_owner_id = {$counter_party_id} AND note_owner_type = '{$note_owner_type}'"), $param_array);									
		for($i=0; $i<count($all_notes_in_add_mode); $i++){
			$all_notes_in_add_mode[$i]['note_cats'] = $this->list_all_categories_for_a_given_note($all_notes_in_add_mode[$i]['note_id']);
		}
		return $all_notes_in_add_mode;
	}
	/* End of the fucntion */
	
	/* This function will retrieve all notes with other details regarding to the client */
	function retrieve_all_notes_owned_by_this_counter_party_only_for_the_edit_view($param_array, $curr_page_no = NULL, $sortBy = "", $sortMethod = "", $counter_party_id, $filtering=false, $notes_cats=""){

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
					WHERE pahro__notes.note_owner_id = '{$counter_party_id}' AND pahro__notes.note_owner_type = 'COUNTER-PARTY' {$sort}{$limit}";
			$all_notes_in_edit_mode = AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $param_array);														
		}else{				
			$sql = "SELECT DISTINCT pahro__notes.*, pahro__user.username  
					FROM pahro__notes_category_rel 
					LEFT JOIN pahro__notes ON pahro__notes.note_id = pahro__notes_category_rel.note_id 
					LEFT JOIN pahro__user ON pahro__user.id = pahro__notes.added_by						
					WHERE pahro__notes.note_owner_id = '{$counter_party_id}' AND pahro__notes_category_rel.note_owner_section = 'COUNTER-PARTY' AND (";					
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

	/* This function will retrieve all notes count regarding to the client */
	function retrieve_all_notes_count_owned_by_this_counter_party_only_for_the_edit_view($counter_party_id, $param_array, $filtering=false, $notes_cats=""){
	
		global $connection;
		if (!$filtering){
		$sql = "SELECT pahro__notes.note_id, pahro__notes.note, pahro__user.username,
					   pahro__notes.added_date, pahro__notes.modified_by, pahro__notes.date_modified 
				FROM pahro__notes
				LEFT JOIN pahro__user ON pahro__user.id = pahro__notes.added_by
				WHERE pahro__notes.note_owner_id = ".$counter_party_id." AND pahro__notes.note_owner_type = 'COUNTER-PARTY' ORDER BY note_id DESC";	
		}else{								
			$sql = "SELECT DISTINCT pahro__notes.*, pahro__user.username  
					FROM pahro__notes_category_rel 
					LEFT JOIN pahro__notes ON pahro__notes.note_id = pahro__notes_category_rel.note_id 
					LEFT JOIN pahro__user ON pahro__user.id = pahro__notes.added_by						
					WHERE pahro__notes.note_owner_id = ".$counter_party_id." AND note_owner_section = 'COUNTER-PARTY' AND (";					
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

	/* This function will check the given note id is owned by the given client */
	function check_note_id_owned_by_the_correct_counter_party($counter_party_id, $note_id){

		global $connection;
		$counter_party_id_in_db = AppModel::grab_db_function_class()->return_single_result_from_mysql_resource(AppModel::grab_db_function_class()->execute_query("SELECT note_owner_id FROM pahro__notes WHERE note_id = {$note_id}"), 0);																
		return ($counter_party_id == $counter_party_id_in_db) ? true : false;
	}		
	/* End of the function */

	/* This function will retrieve note category name for the its cat id */
	function retrieve_full_details_of_selected_note($note_id, $param_array, $note_owner_type){
	
		global $connection;
		$sql = "SELECT pahro__notes.note_id, pahro__notes.note, pahro__notes.added_date, pahro__user.username, pahro__notes.modified_by, pahro__notes.date_modified
				FROM pahro__notes 
				LEFT JOIN pahro__user ON pahro__user.id = pahro__notes.added_by
				WHERE pahro__notes.note_id = {$note_id} AND note_owner_type = '{$note_owner_type}'";	
		$notes_details = AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $param_array);									
		$notes_categories = array("notes_categories" => $this->list_all_categories_for_a_given_note($notes_details[0]['note_id']));
		return (array_merge($notes_details[0], $notes_categories));
	}
	/* End of the function */
	
	/* This function will check whethr the client note id exist in the database before any further action */
	function check_note_id_exist($note_id){
	
		global $connection;
		return (AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query("SELECT note_id FROM pahro__notes WHERE note_id = {$note_id}")) != 0) ? true : false;																
	}
	/* End of the fucntion */
	
	/* This function will load all neccessary details for the single view */
	function grab_full_details_for_the_single_view_in_counter_party($counter_party_id){
	
		global $connection;
		$params = array('counter_party_id', 'country_name', 'company_name', 'first_name', 'last_name', 'title', 'resident_address', 'postal_address', 'land_phone', 
						'mobile_phone', 'email', 'comment');		
		$sql = "SELECT *, pahro__country.country_name 
				FROM counter_party__main 
				LEFT JOIN pahro__country ON pahro__country.country_id = counter_party__main.cp_owned_country_id
				WHERE counter_party_id = {$counter_party_id}";						
		return AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $params);							
	}
	/* End of the fucntion */		
	
	/* This function will retreive all volunteers currently involved with a given case */
	function retrieve_owned_case_ids_by_this_counter_party($counter_party_id){
	
		global $connection;
		return (AppModel::grab_db_function_class()->result_to_single_array_of_data(AppModel::grab_db_function_class()->execute_query("SELECT case_id FROM counter_party__cases WHERE counter_party_id = {$counter_party_id}"), "case_id"));														
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
	
	/* This function will retrieve details per each given parameter */
	function grab_client_single_information_for_the_counter_party_section($title, $case_id){
	
		global $connection;
		return AppModel::grab_db_function_class()->return_single_result_from_mysql_resource(AppModel::grab_db_function_class()->execute_query("SELECT {$title} FROM case__main WHERE reference_number = '{$case_id}'"), 0);														
	}
	/* End of the fucntion */	
	
	/* This function will check whether the given user is being responsible for any case as a staff memeber */
	function is_having_any_cases($counter_party_id){

		global $connection;
		return (AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query("SELECT counter_party_id FROM counter_party__cases WHERE counter_party_id = {$counter_party_id}")) != 0) ? true : false;																
	}
	/* End of the fucntion */			
	
	/* This function will delete the user record from the users table */
	function delete_user_details_from_the_table($cp_id){
	
		global $connection;
		if ($cp_id != ""){ 
			AppModel::grab_db_function_class()->execute_query("DELETE FROM counter_party__main WHERE counter_party_id = ".$cp_id);								
		}	
	} 
	/* End of the function */
	
	/* This function will retreieve all case templates for this case */
	function retrieve_all_counter_party_attachments_for_this_counter_party($counter_party_id){
	
		global $connection;
		$sql = "SELECT id, name
				FROM counter_party__attachments
				WHERE counter_party_id = {$counter_party_id}";
		return (AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), array("id", "name")));														
	}
	/* End of the fucntion */
	
	/* This function will insert client attachments data in to the client__attachments table */
	function insert_cp_attachments_details($cp_attachments_params, $mode){
	
		global $connection;
		foreach($cp_attachments_params['name'] as $param){
		
			$sql = "INSERT INTO counter_party__attachments(name, counter_party_id) 
								VALUES('".
										$param['name']."',".								
										$cp_attachments_params['cp_id'].
									 ")"; 
			AppModel::grab_db_function_class()->execute_query($sql);

			$file_temp_path = $param['file_path_counter_party'];
			$cp_id_for_temps = ($mode == "add") ? $_SESSION['newly_inserted_counter_id'] : $cp_attachments_params['cp_id']; 
			
			$file_target_path = $_SERVER['DOCUMENT_ROOT'].DS."user-uploads/counter-parties".DS.$cp_id_for_temps.DS.$param['name'];
			rename($file_temp_path,$file_target_path);
		}
	}
	/* End of the function */
	
	/* This function will check the user inputted counter party first name and last name will exist in the clients table */
	function validate_cp_names_against_client_records($user_inputted_params){

		global $connection;
		if (
			(AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query("SELECT first_name FROM client__main WHERE first_name = '{$user_inputted_params['first_name']}'")) == 0)
				&&	
			(AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query("SELECT last_name FROM client__main WHERE first_name = '{$user_inputted_params['last_name']}'")) == 0)
			){
				return true;
			}else{
				return false;				
			}
	}
	/* End of the function */
	
	/* This function will delete volunteers who invloved with the caser which about to be deleted from the case__volunteers table */
	function delete_cases_involved_with_this_counter_party($cp_id){
	
		global $connection;
		AppModel::grab_db_function_class()->execute_query("DELETE FROM counter_party__cases WHERE counter_party_id = {$cp_id}");								
	}
	/* End of the fucntion */
	
	/* This function will delete the pfac general details for a given pfac_id */
	function delete_cp_attachments_names($cp_id){
	
		global $connection;
		AppModel::grab_db_function_class()->execute_query("DELETE FROM counter_party__attachments WHERE counter_party_id = ".$cp_id);						
	}
	/* End of the function */		
	
	/* This function will retreive all countries listed in the users adding section */
	function retrieve_all_countires_for_users($param_array){
	
		global $connection;
		return AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query("SELECT country_id, country_name FROM pahro__country"), $param_array);									
	}
	/* End of the function */	
	
	/* This function will check the provided case id by the url is actually can be visible to the requested user by checking the owned country */
	function check_counter_party_id_can_be_visble_to_requested_user_by_country($user_countries_param, $cp_id){

		$country_own = $this->grab_the_owned_country_id($cp_id);
		return	(in_array($country_own, $user_countries_param)) ? true : false;
	}
	/* End of the funtion */
	
	/* This function will grab the owned country_id for the given case id */
	function grab_the_owned_country_id($cp_id){
	
		global $connection;
		return (AppModel::grab_db_function_class()->return_single_result_from_mysql_resource(AppModel::grab_db_function_class()->execute_query("SELECT cp_owned_country_id FROM counter_party__main WHERE counter_party_id = {$cp_id}"), 0));																
	}
	/* End of the function */
	
	/* This function will check the case id dose belongs to the currently logged user's country */
	function check_case_id_does_belongs_to_logged_users_owned_country_for_cp($case_id, $cp_id){
	
		global $connection;	
		$case_owned_country_id = AppModel::grab_db_function_class()->return_single_result_from_mysql_resource(AppModel::grab_db_function_class()->execute_query("SELECT case_owned_country_id FROM case__main WHERE reference_number = '".urldecode($case_id)."'"), 0);																
		$cp_owned_country_id = AppModel::grab_db_function_class()->return_single_result_from_mysql_resource(AppModel::grab_db_function_class()->execute_query("SELECT cp_owned_country_id FROM counter_party__main WHERE counter_party_id = ".$cp_id), 0);																				
		if ($case_owned_country_id == $cp_owned_country_id){
			return true;
		}else{
			return false;			
		}		
	}
	/* End of the function */
	
	/* This function will check the case id dose belongs to the currently logged user's country */
	function check_case_id_does_belongs_to_logged_users_owned_country_for_cp_in_add_mode($case_id, $cp_owned_country_id){

		global $connection;	
		$case_owned_country_id = AppModel::grab_db_function_class()->return_single_result_from_mysql_resource(AppModel::grab_db_function_class()->execute_query("SELECT case_owned_country_id FROM case__main WHERE reference_number = '".urldecode($case_id)."'"), 0);
		if ($case_owned_country_id == $cp_owned_country_id){
			return true;
		}else{
			return false;			
		}		
	}
	/* End of the function */
	
	/* This fucntion will protect the case adding process for the clients by notifying about the duplications */
	function notifying_about_the_case_adding_for_the_duplications($case_id, $cp_id){

		global $connection;
		return (AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query("SELECT case_id FROM counter_party__cases WHERE case_id = '".urldecode($case_id)."' AND counter_party_id = ".$cp_id)) != 0) ? true : false;																
	} 
	/* End of the function */	
	
	/* This fucntion will check the requested current displayed / selected country and against the each client country */
	function check_counter_party_country_with_curr_selected_country($curr_country, $cp_id){
	
		global $connection;
		$cp_owned_country = (AppModel::grab_db_function_class()->return_single_result_from_mysql_resource(AppModel::grab_db_function_class()->execute_query("SELECT cp_owned_country_id FROM counter_party__main WHERE counter_party_id = ".$cp_id), 0));																		
		return ($curr_country == $cp_owned_country) ? true : false;
	}
	/* End of the fucntion */	
}
?>