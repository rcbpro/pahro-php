<?php

class CaseModel{

	/* This function will insert addbook details in to datbase */
	function insert_case_details($case_details_param, $case_dates, $user_id, $created_date, $status, $country){
	
		global $connection;
		$opend_date = $case_dates['case_open_date']['year']."-".$case_dates['case_open_date']['month']."-".$case_dates['case_open_date']['day'];		
		$upcoming_date = $case_dates['case_upcoming_date']['year']."-".$case_dates['case_upcoming_date']['month']."-".$case_dates['case_upcoming_date']['day'];		
		$closed_date = $case_dates['case_close_date']['year']."-".$case_dates['case_close_date']['month']."-".$case_dates['case_close_date']['day'];				
		$sql = "INSERT INTO case__main(reference_number, case_name, description, comment, case_cat_id, case_owned_country_id, staff_responsible, opend_date,
												upcoming_date, created_by, created_date, closed_date, reasone_for_close, status) 
							VALUES('".
									CommonFunctions::mysql_preperation(trim($case_details_param['reference_number']))."','".
									CommonFunctions::mysql_preperation(trim($case_details_param['case_name']))."','".
									CommonFunctions::mysql_preperation(trim($case_details_param['description']))."','".
									CommonFunctions::mysql_preperation(trim($case_details_param['comment']))."',".
									CommonFunctions::mysql_preperation(trim($case_details_param['case_cat_id'])).",".
									$country.",".
									CommonFunctions::mysql_preperation(trim($case_details_param['staff_responsible'])).",'".
									$opend_date."','".
									$upcoming_date."',".
									$user_id.",'".
									$created_date."','".
									$closed_date."','".
									CommonFunctions::mysql_preperation(trim($case_details_param['reasone_for_close']))."','".
									$status.
								"')"; 
		AppModel::grab_db_function_class()->execute_query($sql);	
		$track_id = AppModel::grab_db_function_class()->return_last_inserted_id();	
		return AppModel::grab_db_function_class()->return_single_result_from_mysql_resource(AppModel::grab_db_function_class()->execute_query("SELECT reference_number FROM case__main WHERE id = {$track_id}"), 0);		
	}
	/* End of the function */
	
	/* This function wiil insert volunteers id who currently invoved with newly inseted case */
	function insert_involved_volunteers($case_id, $vols_param){

		global $connection;
		for($i=0; $i<count($vols_param); $i++){			
			$sql = "INSERT INTO case__volunteers(case_id, vol_id) 
								VALUES('".
										trim(urldecode($case_id))."',".
										$vols_param[$i].
									  ")"; 
			AppModel::grab_db_function_class()->execute_query($sql);					
		}
	}
	/* End of the functin */
	
	/* This function will insert addbook details in to datbase */
	function update_case_details($case_details_param, $case_dates, $case_id, $user_params, $status){

		global $connection;
		$opend_date = $case_dates['case_open_date']['year']."-".$case_dates['case_open_date']['month']."-".$case_dates['case_open_date']['day'];		
		$upcoming_date = $case_dates['case_upcoming_date']['year']."-".$case_dates['case_upcoming_date']['month']."-".$case_dates['case_upcoming_date']['day'];		
		$closed_date = $case_dates['case_close_date']['year']."-".$case_dates['case_close_date']['month']."-".$case_dates['case_close_date']['day'];				
		if ($status != "Closed"){
			$closed_date = "0000-00-00";
		}
		$sql = "UPDATE case__main 
						SET 							
							case_name = '". CommonFunctions::mysql_preperation(trim($case_details_param['case_name'])) . "', 
							description = '". CommonFunctions::mysql_preperation(trim($case_details_param['description'])) . "', 													
							comment = '". CommonFunctions::mysql_preperation(trim($case_details_param['comment'])) . "', 
							case_cat_id = ". CommonFunctions::mysql_preperation(trim($case_details_param['case_cat_id'])) . ", 
							staff_responsible = ". CommonFunctions::mysql_preperation(trim($case_details_param['staff_responsible'])) . ", 
							opend_date = '". $opend_date . "', 
							upcoming_date = '". $upcoming_date . "', 
							edited_by = ". trim($user_params['user_id']) . ", 																																			
							edited_date = '". trim($user_params['edited_date']) . "', 
							closed_date = '". $closed_date ."', 
							reasone_for_close = '". CommonFunctions::mysql_preperation(trim($case_details_param['reasone_for_close'])) ."',
							status = '". $status ."'
						 WHERE reference_number = '".urldecode($case_id)."'";
		AppModel::grab_db_function_class()->execute_query($sql);		
	}
	/* End of the function */	
	
	/* This function will load the address book categories from the database */
	function retrieve_case_categories(){
	
		global $connection;
		$params = array('case_cat_id', 'case_cat_name', 'case_cat_description');
		return AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query("SELECT * FROM case__category WHERE status = 1"), $params);		
	}
	/* End of the function */

	/* This function will load the address book categories from the database */
	function retrieve_all_staff_memebers($curr_country){
	
		global $connection;
		$params = array('id', 'username');
		$sql = "SELECT pahro__user.id, pahro__user.username FROM pahro__user LEFT JOIN pahro__users_countries ON pahro__user.id = pahro__users_countries.user_id WHERE pahro__user.user_type_id = 1 AND pahro__users_countries.country_id = {$curr_country}";		
		return AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $params);		
	}
	/* End of the function */

	/* This function will load the address book categories from the database */
	function retrieve_all_volunteers_to_assign_case($curr_country){
	
		global $connection;
		$params = array('id', 'username', 'country_id');
		$sql = "SELECT pahro__user.id, pahro__user.username, pahro__users_countries.country_id 
				FROM pahro__user 
				LEFT JOIN pahro__users_countries ON pahro__users_countries.user_id = pahro__user.id
				WHERE pahro__user.user_type_id = 2 AND pahro__users_countries.country_id = {$curr_country}";	
		return AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $params);		
	}
	/* End of the function */
	
	/* This function will retrieve all pfa clients in to display */
	function display_all_cases($params, $curr_page_no = NULL, $sortBy = "", $sortMethod = "", $user_country_params, $curr_country){
	
		global $connection;
		$sort = "";
		$limit = "";		

		$display_items = NO_OF_RECORDS_PER_PAGE_FOR_CASES;					
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
			$sort = " ORDER BY case__main.id DESC";				
		}
		$limit = " Limit {$start_no_sql}, {$end_no_sql}";				
		$sql = "SELECT 
					case__main.reference_number, case__main.case_name, case__main.description, case__main.status, case__main.staff_responsible, case__main.case_owned_country_id,
					case__main.opend_date, case__main.upcoming_date, case__main.created_date,
					case__category.case_cat_name, pahro__user.username, pahro__country.country_name
				FROM case__main
				LEFT JOIN pahro__country ON pahro__country.country_id = case__main.case_owned_country_id				
				LEFT JOIN case__category ON case__category.case_cat_id = case__main.case_cat_id
				LEFT JOIN pahro__user ON pahro__user.id = case__main.created_by 
				WHERE case__main.case_owned_country_id = {$curr_country}
				{$sort}{$limit}";	
		$full_results = AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $params);				
		foreach($full_results as $each_result){
			if (in_array($each_result['case_owned_country_id'], $user_country_params)){
				$final_result[] = $each_result;
			}
		}
		return $final_result;
	}
	/* End of the fucntion */
	
	/* This function will retrieve the count for all pfa clients in to display */
	function display_cases_all_count($params, $user_country_params, $curr_country_code){
	
		global $connection;
		$sql = "SELECT 
					case__main.reference_number, case__main.case_name, case__main.description, case__main.status, case__main.staff_responsible, case__main.case_owned_country_id,
					case__main.opend_date, case__main.upcoming_date, case__main.created_date,
					case__category.case_cat_name, pahro__user.username, pahro__country.country_name
				FROM case__main
				LEFT JOIN pahro__country ON pahro__country.country_id = case__main.case_owned_country_id				
				LEFT JOIN case__category ON case__category.case_cat_id = case__main.case_cat_id
				LEFT JOIN pahro__user ON pahro__user.id = case__main.created_by 
				WHERE case__main.case_owned_country_id = {$curr_country_code}				
				ORDER BY case__main.id DESC";						
		$full_results = AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $params);				
		foreach($full_results as $each_result){
			if (in_array($each_result['case_owned_country_id'], $user_country_params)){
				$final_result[] = $each_result;
			}
		}	
		return count($final_result);	
	}
	/* End of the fucntion */	
	
	/* This function will check whether the given address book id from the get value is exist in database */
	function check_case_id_exist($case_id){
	
		global $connection;
		return (AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query("SELECT reference_number FROM case__main WHERE reference_number = '".urldecode($case_id)."'")) != 0) ? true : false;																
	}
	/* End of the function */
	
	/* This function will retrieve full details per each address book contact */
	function retrieve_full_details_per_each_case($case_id){
	
		global $connection;
		$sql = "SELECT 
					case__main.id, case__main.reference_number, case__main.case_name, case__main.description, case__main.comment, case__main.case_owned_country_id,
					case__category.case_cat_name, pahro__user.username, case__main.case_cat_id, case__main.staff_responsible, 
					case__main.opend_date, case__main.upcoming_date, case__main.closed_date, case__main.reasone_for_close, case__main.status
				FROM case__main
				LEFT JOIN case__category ON case__category.case_cat_id = case__main.case_cat_id 
				LEFT JOIN pahro__user ON pahro__user.id = case__main.staff_responsible				
				WHERE case__main.reference_number = '".urldecode($case_id)."'";	
		$params = array('id', 'reference_number', 'case_name', 'case_owned_country_id', 'staff_responsible', 'case_cat_id', 'description', 'comment', 'case_cat_name', 'username', 'opend_date', 'upcoming_date', 'closed_date', 'reasone_for_close', 
						'status');		
		return AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $params);							
	}
	/* End of the function */	
	
	/* This function will retrieve the case owned volunteers from the case volunteer table */
	function is_current_case_handlling_volunteer_for_this_case($case_id, $vol_id){
	
		global $connection;
		$vol_id_in_db = AppModel::grab_db_function_class()->result_to_single_array_of_data(AppModel::grab_db_function_class()->execute_query("SELECT vol_id FROM case__volunteers WHERE case_id = '".urldecode($case_id)."'"), "vol_id");		
		return (in_array($vol_id, $vol_id_in_db)) ? true : false;
	}
	/* End of the function */
	
	/* This function will insert log details every time against the user action */
	function keep_track_of_activity_log_in_case($logParmas){
	
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
		$sql = "INSERT INTO case__notes
							(case_id, note, added_by, added_date) 
							VALUES('".
									CommonFunctions::mysql_preperation(trim(urldecode($notes_params['case_id'])))."','".
									CommonFunctions::mysql_preperation(trim($notes_params['note']))."',".
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
								VALUES(".$notes_categories_params['note_id'].", ".$notes_categories_params['notes_categories'][$i].", '".$notes_categories_params['note_owner_section']."')";
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
				WHERE pahro__notes_category_rel.note_id = ".$note_id . " AND note_owner_section = 'CASE'";
		$notes_category_param = array('note_cat_name', 'note_cat_id');
		return AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $notes_category_param);				
	}
	/* End of the function */
	
	/* This function will remove prevous notes categories relations for a given note id */
	function remove_previous_notes_categories_relation($note_id){
	
		global $connection;
		return AppModel::grab_db_function_class()->execute_query("DELETE FROM pahro__notes_category_rel WHERE note_id = ".$note_id . " AND note_owner_section = 'CASE'");						
	}
	/* End of the fucntion */
	
	/* This function will insert a new note to the datbase */
	function update_the_exsiting_note($notes_params, $note_id){
	
		global $connection;
		$sql = "UPDATE case__notes 
						SET 				
							note = '". CommonFunctions::mysql_preperation(trim($notes_params['note'])) . "', 
							modified_date = '". $notes_params['modified_date'] . "', 
							modified_by = ". $notes_params['modified_by'] ." 
						 WHERE note_id = {$note_id} AND case_id = '".urldecode($notes_params['case_id'])."'";
		AppModel::grab_db_function_class()->execute_query($sql);		
	}
	/* End of the function */
	
	/* This function will delete the pfac general details for a given pfac_id */
	function delete_selected_note($note_id, $case_id){
	
		global $connection;
		$sql = "DELETE FROM case__notes
						WHERE case__notes.note_id = {$note_id} AND case__notes.case_id = '".CommonFunctions::mysql_preperation(trim(urldecode($case_id)))."'";
		AppModel::grab_db_function_class()->execute_query($sql);						
	}
	/* End of the function */		

	/* This function will retrieve all notes with other details regarding to the client */
	function retrieve_all_notes_owned_by_this_client($case_id, $param_array){
	
		global $connection;
		$sql = "SELECT * FROM case__notes WHERE case_id = '".CommonFunctions::mysql_preperation(trim(urldecode($case_id)))."' ORDER BY note_id DESC";	
		$all_notes_in_add_mode = AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $param_array);									
		for($i=0; $i<count($all_notes_in_add_mode); $i++){
			$all_notes_in_add_mode[$i]['note_cats'] = $this->list_all_categories_for_a_given_note($all_notes_in_add_mode[$i]['note_id']);
		}
		return $all_notes_in_add_mode;
	}
	/* End of the fucntion */

	/* This function will retrieve all notes with other details regarding to the client */
	function retrieve_all_notes_owned_by_this_client_only_for_the_edit_view($param_array, $curr_page_no = NULL, $sortBy = "", $sortMethod = "", $case_id, $filtering=false, $notes_cats=""){

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
			$sort = " ORDER BY case__notes.note_id DESC";				
		}
		$limit = " Limit {$start_no_sql}, {$end_no_sql}";		
		if (!$filtering){
			$sql = "SELECT case__notes.note_id, case__notes.note, pahro__user.username, 
						   case__notes.added_date, case__notes.modified_by, case__notes.modified_date 
					FROM case__notes
					LEFT JOIN pahro__user ON pahro__user.id = case__notes.added_by
					WHERE case__notes.case_id = '".urldecode($case_id)."' {$sort}{$limit}";
			$all_notes_in_edit_mode = AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $param_array);														
		}else{								
			$sql = "SELECT DISTINCT case__notes.*, pahro__user.username  
					FROM pahro__notes_category_rel 
					LEFT JOIN case__notes ON case__notes.note_id = pahro__notes_category_rel.note_id 
					LEFT JOIN pahro__user ON pahro__user.id = case__notes.added_by						
					WHERE case__notes.case_id = '".urldecode($case_id)."' AND pahro__notes_category_rel.note_owner_section = 'CASE' AND (";					
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
	function retrieve_all_notes_count_owned_by_this_client_only_for_the_edit_view($case_id, $param_array, $filtering=false, $notes_cats=""){
	
		global $connection;
		if (!$filtering){
		$sql = "SELECT case__notes.note_id, case__notes.note, pahro__user.username,
					   case__notes.added_date, case__notes.modified_by, case__notes.modified_date 
				FROM case__notes
				LEFT JOIN pahro__user ON pahro__user.id = case__notes.added_by
				WHERE case__notes.case_id = '".urldecode($case_id)."' ORDER BY note_id DESC";	
		}else{								
			$sql = "SELECT DISTINCT case__notes.*, pahro__user.username  
					FROM pahro__notes_category_rel 
					LEFT JOIN case__notes ON case__notes.note_id = pahro__notes_category_rel.note_id 
					LEFT JOIN pahro__user ON pahro__user.id = case__notes.added_by						
					WHERE case__notes.case_id = '".urldecode($case_id)."' AND note_owner_section = 'CASE' AND (";					
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
	function check_note_id_owned_by_the_correct_case($case_id, $note_id){	

		global $connection;
		$case_id_in_db = AppModel::grab_db_function_class()->return_single_result_from_mysql_resource(AppModel::grab_db_function_class()->execute_query("SELECT case_id FROM case__notes WHERE note_id = {$note_id}"), 0);																
		return (urldecode($case_id) == $case_id_in_db) ? true : false;
	}		
	/* End of the function */

	/* This function will retrieve note category name for the its cat id */
	function retrieve_full_details_of_selected_note($note_id, $param_array){
	
		global $connection;
		$sql = "SELECT case__notes.note_id, case__notes.note, case__notes.added_date, pahro__user.username, case__notes.modified_by, case__notes.modified_date
				FROM case__notes 
				LEFT JOIN pahro__user ON pahro__user.id = case__notes.added_by
				WHERE case__notes.note_id = {$note_id}";	
		$notes_details = AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $param_array);									
		$notes_categories = array("notes_categories" => $this->list_all_categories_for_a_given_note($notes_details[0]['note_id']));
		return (array_merge($notes_details[0], $notes_categories));
	}
	/* End of the function */
	
	/* This function will check whethr the client note id exist in the database before any further action */
	function check_note_id_exist($note_id){
	
		global $connection;
		return (AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query("SELECT note_id FROM case__notes WHERE note_id = {$note_id}")) != 0) ? true : false;																
	}
	/* End of the fucntion */
	
	/* This function will grab the responsible staff name for a given user id */
	function grab_the_responsible_staff_name($user_id){
	
		global $connection;
		return (AppModel::grab_db_function_class()->return_single_result_from_mysql_resource(AppModel::grab_db_function_class()->execute_query("SELECT username FROM pahro__user WHERE id = {$user_id}"), 0));																
	}
	/* End of the function */	
	
	/* This function will clear the user previous user group details */
	function clear_the_current_volunteers_assigned($case_id){
	
		global $connection;
		AppModel::grab_db_function_class()->execute_query("DELETE FROM case__volunteers WHERE case_id = '".urldecode($case_id)."'");							
	}
	/* End of the function */
	
	/* This fucntion will grab the username as note modified person from the users table */
	function grab_the_username_related_note_as_modifield_person($modified_by){
	
		global $connection;
		$sql = "SELECT username 
				FROM pahro__user 
				LEFT JOIN case__notes ON case__notes.modified_by = pahro__user.id 
				WHERE case__notes.modified_by = {$modified_by}";
		return (AppModel::grab_db_function_class()->return_single_result_from_mysql_resource(AppModel::grab_db_function_class()->execute_query($sql), 0));																
	}  
	/* End of the function */
	
	/* This function will check the case id whether it is unique */
	function check_case_ref_no_is_unique($input_value){
	
		global $connection;
		return (AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query("SELECT reference_number FROM case__main WHERE reference_number = '{$input_value}'")) != 0) ? true : false;																
	}
	/* End of the function */
	
	/* This function will insert case templates data in to the case__templates table */
	function insert_case_templates_details($case_templates_params, $mode){
	
		global $connection;
		foreach($case_templates_params['name'] as $param){
		
			$sql = "INSERT INTO case__templates(name, case_id) 
								VALUES('".
										$param['name']."','".								
										CommonFunctions::mysql_preperation(trim($case_templates_params['case_id']))."'".
									  ")"; 
			AppModel::grab_db_function_class()->execute_query($sql);

			$file_temp_path = $param['file_path_case'];
			$case_id_for_temps = ($mode == "add") ? $_SESSION['case_main_reqired']['reference_number'] : $case_templates_params['case_id']; 
			
			$file_target_path = $_SERVER['DOCUMENT_ROOT'].DS."user-uploads/cases".DS.$case_id_for_temps.DS.$param['name'];
			rename($file_temp_path,$file_target_path);
		}
	}
	/* End of the function */
	
	/* This function will load all neccessary details for the single view */
	function grab_full_details_for_the_single_view_in_case($case_id){
	
		global $connection;
		$sql = "SELECT 
					case__main.reference_number, case__main.case_name, case__main.description, case__main.comment,
					case__category.case_cat_name, pahro__user.username, 
					pahro__country.country_name, 
					case__main.opend_date, case__main.upcoming_date, case__main.edited_by, case__main.edited_date, case__main.created_by, 
					case__main.created_date, case__main.closed_date, case__main.reasone_for_close, case__main.status
				FROM case__main
				LEFT JOIN case__category ON case__category.case_cat_id = case__main.case_cat_id 
				LEFT JOIN pahro__country ON pahro__country.country_id = case__main.case_owned_country_id
				LEFT JOIN pahro__user ON pahro__user.id = case__main.staff_responsible				
				WHERE case__main.reference_number = '".urldecode($case_id)."'";
		$params = array('reference_number', 'case_name', 'country_name', 'description', 'comment', 'case_cat_name', 'username', 'opend_date', 'upcoming_date', 
						'edited_by', 'edited_date', 'created_by', 'created_date', 'closed_date', 'reasone_for_close', 'status');		
		return AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $params);							
	}
	/* End of the fucntion */		
	
	/* This fucntion will grab the username as note modified person from the users table */
	function grab_the_username_for_cases($userid, $title){
	
		global $connection;
		$sql = "SELECT username 
				FROM pahro__user 
				LEFT JOIN case__main ON case__main.{$title} = pahro__user.id 
				WHERE case__main.{$title} = {$userid}";
		return (AppModel::grab_db_function_class()->return_single_result_from_mysql_resource(AppModel::grab_db_function_class()->execute_query($sql), 0));																
	}  
	/* End of the function */	
	
	/* This function will retreive all volunteers currently involved with a given case */
	function retrieve_volunteers_for_the_given_case($case_id){
	
		global $connection;
		$sql = "SELECT pahro__user.username, pahro__user.first_name, pahro__user.last_name, pahro__user.id
				FROM pahro__user
				LEFT JOIN case__volunteers ON case__volunteers.vol_id = pahro__user.id
				LEFT JOIN case__main ON case__main.reference_number = case__volunteers.case_id
				WHERE case__volunteers.case_id = '".urldecode($case_id)."'";
		$params = array('username','first_name','last_name', 'id');		
		return (AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $params));														
	}
	/* End of the function */
	
	/* This function will retreieve all case templates for this case */
	function retrieve_all_case_templates_for_this_case($case_id){
	
		global $connection;
		return (AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query("SELECT id, name FROM case__templates WHERE case_id = '".urldecode($case_id)."'"), array("id", "name")));														
	}
	/* End of the fucntion */
	
	/* This function will check the given case id having any volunteers working on it */
	function is_any_volunteers_invloved($case_id){
	
		global $connection;
		return (AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query("SELECT case_id FROM case__volunteers WHERE case_id = '".urldecode($case_id)."'")) != 0) ? true : false;																
	} 
	/* End of the function */
	
	/* This function will delete volunteers who invloved with the caser which about to be deleted from the case__volunteers table */
	function delete_volunteer_details_from_case_volunteers($case_id){
	
		global $connection;
		AppModel::grab_db_function_class()->execute_query("DELETE FROM case__volunteers WHERE case_id = '".urldecode($case_id)."'");								
	}
	/* End of the fucntion */
	
	/* This function will delete volunteers who invloved with the caser which about to be deleted from the case__volunteers table */
	function delete_selected_case($case_id){
	
		global $connection;
		AppModel::grab_db_function_class()->execute_query("DELETE FROM case__main WHERE reference_number = '".urldecode($case_id)."'");								
	}
	/* End of the fucntion */
	
	/* This function will delete the pfac general details for a given pfac_id */
	function delete_owned_notes($case_id){
	
		global $connection;
		AppModel::grab_db_function_class()->execute_query("DELETE FROM case__notes WHERE case_id = '".urldecode($case_id)."'");						
	}
	/* End of the function */		
	
	/* This function will delete the pfac general details for a given pfac_id */
	function delete_case_templates_names($case_id){
	
		global $connection;
		AppModel::grab_db_function_class()->execute_query("DELETE FROM case__templates WHERE case_id = '".urldecode($case_id)."'");						
	}
	/* End of the function */		
	
	/* This function will retreive all countries listed in the users adding section */
	function retrieve_all_countires_for_users($param_array){
	
		global $connection;
		return AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query("SELECT country_id, country_name FROM pahro__country"), $param_array);									
	}
	/* End of the function */	
	
	/* This function will check the provided case id by the url is actually can be visible to the requested user by checking the owned country */
	function check_case_id_can_be_visble_to_requested_user_by_country($user_countries_param, $case_id){

		return	(in_array($this->grab_the_owned_country_id(urldecode($case_id)), $user_countries_param)) ? true : false;
	}
	/* End of the funtion */
	
	/* This fucntion will check the requested current displayed / selected country and against the each case country */
	function check_case_country_with_curr_selected_country($curr_country, $case_id){
	
		global $connection;
		$case_owned_country = (AppModel::grab_db_function_class()->return_single_result_from_mysql_resource(AppModel::grab_db_function_class()->execute_query("SELECT case_owned_country_id FROM case__main WHERE reference_number = '".$case_id."'"), 0));																		
		return ($curr_country == $case_owned_country) ? true : false;
	}
	/* End of the fucntion */
	
	/* This function will grab the owned country_id for the given case id */
	function grab_the_owned_country_id($case_id){
	
		global $connection;
		return (AppModel::grab_db_function_class()->return_single_result_from_mysql_resource(AppModel::grab_db_function_class()->execute_query("SELECT case_owned_country_id FROM case__main WHERE reference_number = '{$case_id}'"), 0));																
	}
	/* End of the function */
	
	/* This function will retreive all volunteers currently involved with a given case */
	function retrieve_clients_for_the_given_case($case_id){
	
		global $connection;
		$params_array = array('client_id', 'first_name', 'last_name', 'reference_number', 'case_name');
		$sql = "SELECT client__main.client_id, client__main.first_name, client__main.last_name, case__main.reference_number, case__main.case_name
				FROM client__main
				LEFT JOIN client__cases ON client__cases.client_id = client__main.client_id
				LEFT JOIN case__main ON client__cases.case_id = case__main.reference_number
				WHERE client__cases.case_id = '".urldecode($case_id)."'";
		return (AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $params_array));														
	}
	/* End of the function */
	
	/* This function will retreive all volunteers currently involved with a given case */
	function retrieve_cp_for_the_given_case($case_id){
	
		global $connection;
		$params_array = array('counter_party_id', 'first_name', 'last_name', 'reference_number', 'case_name');
		$sql = "SELECT counter_party__main.counter_party_id, counter_party__main.first_name, counter_party__main.last_name, case__main.reference_number, case__main.case_name
				FROM counter_party__main
				LEFT JOIN counter_party__cases ON counter_party__cases.counter_party_id = counter_party__main.counter_party_id
				LEFT JOIN case__main ON counter_party__cases.case_id = case__main.reference_number
				WHERE counter_party__cases.case_id = '".urldecode($case_id)."'";
		return (AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $params_array));														
	}
	/* End of the function */
	
	/* This fucntion will retrieve the volunteers currently working on the cases */
	function retrieve_volunteers_currently_involved_with_cases($case_id){
	
		global $connection;
		$sql = "SELECT case__volunteers.vol_id, pahro__user.username 
				FROM case__volunteers 
				LEFT JOIN pahro__user ON pahro__user.id = case__volunteers.vol_id
				WHERE case__volunteers.case_id = '".urldecode($case_id)."'";
		$params = array('vol_id', 'username');		
		return (AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $params));														
	}
	/* End of the function */	
	
	/* This function will remove the involved clients form the table */
	function delete_involved_clients_with_cases($case_id){
	
		global $connection;
		AppModel::grab_db_function_class()->execute_query("DELETE FROM client__cases WHERE case_id = '".urldecode($case_id)."'");																
	}
	/* End of the function */ 
	
	/* This function will remove the involved clients form the table */
	function delete_involved_counter_parties_with_cases($case_id){
	
		global $connection;
		AppModel::grab_db_function_class()->execute_query("DELETE FROM counter_party__cases WHERE case_id = '".urldecode($case_id)."'");																
	}
	/* End of the function */ 		
}
?>