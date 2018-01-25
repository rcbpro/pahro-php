<?php

class CaseCategoryModel{

	/* This function will insert addbook details in to datbase */
	function insert_case_cat_details($case_cat_details_param){
	
		global $connection;
		$sql = "INSERT INTO case__category(case_cat_name, case_cat_description) 
							VALUES('".
									CommonFunctions::mysql_preperation(trim($case_cat_details_param['case_cat_name']))."','".
									CommonFunctions::mysql_preperation(trim($case_cat_details_param['case_cat_desc'])).
								  "')"; 
		AppModel::grab_db_function_class()->execute_query($sql);
		$track_id = AppModel::grab_db_function_class()->return_last_inserted_id();			
		return AppModel::grab_db_function_class()->return_single_result_from_mysql_resource(AppModel::grab_db_function_class()->execute_query("SELECT case_cat_name FROM case__category WHERE id = {$track_id}"), 0);		
	}
	/* End of the function */
	
	/* This function will insert addbook details in to datbase */
	function update_case_category_details($case_cat_details_param){

		global $connection;
		$sql = "UPDATE case__category 
						SET 							
							case_cat_name = '". CommonFunctions::mysql_preperation(trim($case_cat_details_param['case_cat_name'])) . "', 
							case_cat_description = '". CommonFunctions::mysql_preperation(trim($case_cat_details_param['case_cat_desc'])) . "' 													
						 WHERE case_cat_id = ".$case_cat_details_param['case_cat_id'];
		AppModel::grab_db_function_class()->execute_query($sql);		
	}
	/* End of the function */	
	
	/* This function will retrieve all pfa clients in to display */
	function display_all_case_categories($params, $curr_page_no = NULL, $sortBy = "", $sortMethod = ""){
	
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
			$sort = " ORDER BY case__category.case_cat_id DESC";				
		}
		$limit = " Limit {$start_no_sql}, {$end_no_sql}";				
		$sql = "SELECT case_cat_id, case_cat_name, case_cat_description FROM case__category {$sort}{$limit}";	
		return AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $params);				
	}
	/* End of the fucntion */
	
	/* This function will retrieve the count for all pfa clients in to display */
	function display_cases_cats_all_count(){
	
		global $connection;
		return AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query("SELECT case_cat_name, case_cat_description FROM case__category"));				
	}
	/* End of the fucntion */	
	
	/* This function will retrieve full details per each address book contact */
	function retrieve_full_details_per_each_case($case_cat_id){
	
		global $connection;
		$params = array('case_cat_id', 'case_cat_name', 'case_cat_description');				
		return AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query("SELECT * FROM case__category WHERE case_cat_id = ".$case_cat_id), $params);							
	}
	/* End of the function */	
	
	/* This function will insert log details every time against the user action */
	function keep_track_of_activity_log_in_case_category($logParmas){
	
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
	
	/* This function will load all neccessary details for the single view */
	function grab_full_details_for_the_single_view_in_case_category($case_cat_id){
	
		global $connection;
		$params = array('case_cat_id', 'case_cat_name', 'case_cat_description');		
		return AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query("SELECT * FROM case__category WHERE case_cat_id = ".$case_cat_id . " AND status = 1"), $params);							
	}
	/* End of the fucntion */		
	
	/* This function will delete volunteers who invloved with the caser which about to be deleted from the case__volunteers table */
	function delete_selected_case_category($case_cat_id){
	
		global $connection;
		AppModel::grab_db_function_class()->execute_query("DELETE FROM case__category WHERE case_cat_id = ".$case_cat_id);								
	}
	/* End of the fucntion */
	
	/* This function will check whether the given address book id from the get value is exist in database */
	function check_case_category_id_exist($case_cat_id){
	
		global $connection;
		return (AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query("SELECT case_cat_id FROM case__category WHERE case_cat_id = ".$case_cat_id)) != 0) ? true : false;																
	}
	/* End of the function */

	/* This function will retieve the case category name by the given case category id */
	function retrieve_case_category_name_by_id($case_cat_id){
		
		global $connection;
		return AppModel::grab_db_function_class()->return_single_result_from_mysql_resource(AppModel::grab_db_function_class()->execute_query("SELECT case_cat_name FROM case__category WHERE case_cat_id = {$case_cat_id}"), 0);		
	} 
	/* End of the function */			
	
	/* This function will check the selected case category has been assigned with cases */
	function check_case_cat_has_been_assigned_with_any_cases($case_cat_id){
	
		global $connection;
		return (AppModel::grab_db_function_class()->return_num_of_rows_in_result(AppModel::grab_db_function_class()->execute_query("SELECT case_cat_id FROM case__main WHERE case_cat_id = ".$case_cat_id)) != 0) ? false : true;																
	}  
	/* End of the fucntion */
	
	/* This function will retreive cases owned by the given case category */
	function retrieve_cases_owned_given_case_category($case_cat_id){
	
		global $connection;
		$params_array = array('reference_number', 'case_name');
		return (AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query("SELECT case__main.reference_number, case__main.case_name FROM case__main WHERE case__main.case_cat_id = ".$case_cat_id), $params_array));														
	}
	/* End of the function */
}
?>