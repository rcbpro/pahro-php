<?php

class SearchModel{

	/* This function will retrieve all pahro users in to display */
	function display_all_search_results($controller, $searchTable, $searchQuery, $params, $curr_page_no = NULL, $sortBy = "", $sortMethod = "", $user_country_params, $curr_country){

		global $connection;
		$sort = "";
		$limit = "";		
		$where = "";
		$sql = "";		
		$no_of_records = ($controller == "system") ? 100 : NO_OF_RECORDS_PER_PAGE_FOR_LOG;
	
		switch($searchTable[0]){
		
			case "case__main"; case "pahro__user"; case "pahro__log"; $id = "id"; break;
			case "case__category"; $id = "case_cat_id"; break;			
			case "client__main": $id = "client_id"; break;
			case "counter_party__main": $id = "counter_party_id"; break;									
		}
	
		$display_items = $no_of_records;					
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
			$sort = " ORDER BY {$searchTable[0]}.{$id} DESC";				
		}
		$limit = " Limit {$start_no_sql}, {$end_no_sql}";
		switch($controller){
			
			case "case":
				$refine_field = "case_owned_country_id";			
				$sql = "SELECT 
							case__main.reference_number, case__main.case_name, case__main.description, case__main.status, case__main.staff_responsible, case__main.{$refine_field},
							case__main.opend_date, case__main.upcoming_date, case__main.created_date,
							case__category.case_cat_name, pahro__user.username, pahro__country.country_name
						FROM case__main
						LEFT JOIN pahro__country ON pahro__country.country_id = case__main.{$refine_field}
						LEFT JOIN case__category ON case__category.case_cat_id = case__main.case_cat_id
						LEFT JOIN pahro__user ON pahro__user.id = case__main.created_by ";
				$mainSearchingTable = "case__main";		
			break;

			case "case-category":
				$sql = "SELECT *
						FROM case__category ";
				$mainSearchingTable = "case__category";		
			break;
			
			case "client":	
				$refine_field = "client_owned_country_id";								
				$sql = "SELECT 
							client__main.client_id, client__main.first_name, client__main.last_name, client__main.title, client__main.martial_status, client__main.{$refine_field},
							client__main.resident_address, client__main.land_phone, client__main.country,
							client__main.email, client__main.address_of_employment, pahro__country.country_name
						FROM client__main 
						LEFT JOIN pahro__country ON pahro__country.country_id = client__main.{$refine_field} ";								
				$mainSearchingTable = "client__main";								
			break;

			case "counter-party":	
				$refine_field = "cp_owned_country_id";													
				$sql = "SELECT counter_party__main.*, pahro__country.country_name FROM counter_party__main 
						LEFT JOIN pahro__country ON pahro__country.country_id = counter_party__main.{$refine_field} ";
				$mainSearchingTable = "counter_party__main";														
			break;			

			case "pahro":		
				$refine_field = "country_id";							
				$sql = "SELECT pahro__user.id, pahro__user.status, pahro__user_types.user_type, pahro__country.country_name, pahro__user.username, pahro__user.first_name, pahro__user.last_name, 
							   pahro__user.email, pahro__user.created_at, pahro__user.last_login,
							   pahro__users_countries.{$refine_field}, pahro__country.country_name
						FROM pahro__user 
						LEFT JOIN pahro__users_countries ON pahro__users_countries.user_id = pahro__user.id 						
						LEFT JOIN pahro__user_types ON pahro__user_types.user_type_id = pahro__user.user_type_id 
						LEFT JOIN pahro__country ON pahro__country.country_id = pahro__users_countries.country_id ";
				$mainSearchingTable = "pahro__user";																				
			break;		

			case "system":
				$refine_field = "country_id";											
				$sql = "SELECT pahro__log.id, pahro__user.username, pahro__log.action_type_desc, pahro__log.date_time,
							   pahro__users_countries.{$refine_field} 				 
						FROM pahro__user 
						LEFT JOIN pahro__users_countries ON pahro__users_countries.user_id = pahro__user.id 
						LEFT JOIN pahro__log ON pahro__log.user_id = pahro__user.id ";	
				$mainSearchingTable = "pahro__user";																				
			break;		
			
			default:			
				$sql .= "";
				return false;
				exit();
			break;				
		}
		
		if (($controller != "case") && ($controller != "case-category")){
			if ((array_key_exists("fname", $searchQuery)) && (array_key_exists("sname", $searchQuery))){
				$where .= " WHERE {$mainSearchingTable}.first_name LIKE '%".strtolower(trim($searchQuery['fname']))."%' AND {$mainSearchingTable}.last_name LIKE '%".strtolower(trim($searchQuery['sname']))."%'";				
			}elseif (array_key_exists("fname", $searchQuery)){
				$where .= " WHERE {$mainSearchingTable}.first_name LIKE '%".strtolower(trim($searchQuery['fname']))."%'";
			}elseif (array_key_exists("sname", $searchQuery)){
				$where .= " WHERE {$mainSearchingTable}.last_name LIKE '%".strtolower(trim($searchQuery['sname']))."%'";			
			}else{
				$where .= "";
			}
			switch($controller){
				case "client": $owned_country_id = "client_owned_country_id"; $searching_table = "client__main"; break;
				case "counter-party": $owned_country_id = "cp_owned_country_id"; $searching_table = "counter_party__main"; break;
				case "pahro": $owned_country_id = "country_id"; $searching_table = "pahro__users_countries"; break;		
				case "system": $owned_country_id = "country_id"; $searching_table = "pahro__users_countries"; break;												
			}
			$where .= " AND {$searching_table}.{$owned_country_id} = {$curr_country}";						
		}elseif ($controller == "case"){
			if (array_key_exists("case_id", $searchQuery)){
				$where .= " WHERE {$mainSearchingTable}.reference_number LIKE '%".strtolower(trim(urldecode($searchQuery['case_id'])))."%'";				
			}else{
				$where .= "";
			}		
			$where .= " AND {$mainSearchingTable}.case_owned_country_id = {$curr_country}";					
		}elseif ($controller == "case-category"){
			if (array_key_exists("cat_name", $searchQuery)){
				$where .= " WHERE {$mainSearchingTable}.case_cat_name LIKE '%".strtolower(trim($searchQuery['cat_name']))."%'";				
			}else{
				$where .= "";
			}		
		}
		$sql .= "{$where}{$sort}{$limit}";
		$full_results = AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $params);				
		if ($controller != "case-category"){
			foreach($full_results as $each_result){
				if (in_array($each_result[$refine_field], $user_country_params)){
					$final_result[] = $each_result;
				}
			}
		}
		if (($controller == "pahro") || ($controller == "system")){
			$final_result = CommonFunctions::array_multi_unique($final_result, "id");		
		}	
		return ($controller != "case-category") ? $final_result : $full_results;
	}
	/* End of the fucntion */
	
	/* This function will retrieve the count for all pahro users in to display */
	function display_count_on_all_search_results($controller, $searchTable, $searchQuery, $params, $curr_page_no = NULL, $sortBy = "", $sortMethod = "", $user_country_params, $curr_country){
	
		global $connection;
		
		switch($searchTable[0]){
		
			case "case__main"; case "pahro__user"; case "pahro__log"; $id = "id"; break;
			case "case__category"; $id = "case_cat_id"; break;			
			case "client__main": $id = "client_id"; break;
			case "counter_party__main": $id = "counter_party_id"; break;						
		}
	
		$where = "";
		$sort = "";
		$sql = "";		
		if ($sortBy != ""){
			if ($sortMethod == ""){
				$sortMethod = "asc";
			}
			$sort = " ORDER BY {$sortBy} {$sortMethod}";	
		}else{
			$sort = " ORDER BY {$searchTable[0]}.{$id} DESC";				
		}
		
		switch($controller){
			
			case "case":
				$refine_field = "case_owned_country_id";			
				$sql = "SELECT 
							case__main.reference_number, case__main.case_name, case__main.description, case__main.status, case__main.staff_responsible, case__main.{$refine_field},
							case__main.opend_date, case__main.upcoming_date, case__main.created_date,
							case__category.case_cat_name, pahro__user.username, pahro__country.country_name
						FROM case__main
						LEFT JOIN pahro__country ON pahro__country.country_id = case__main.{$refine_field}
						LEFT JOIN case__category ON case__category.case_cat_id = case__main.case_cat_id
						LEFT JOIN pahro__user ON pahro__user.id = case__main.created_by ";
				$mainSearchingTable = "case__main";		
			break;
			
			case "case-category":
				$sql = "SELECT *
						FROM case__category ";
				$mainSearchingTable = "case__category";		
			break;
			
			case "client":	
				$refine_field = "client_owned_country_id";								
				$sql = "SELECT 
							client__main.client_id, client__main.first_name, client__main.last_name, client__main.title, client__main.martial_status, client__main.{$refine_field},
							client__main.resident_address, client__main.land_phone, client__main.country,
							client__main.email, client__main.address_of_employment, pahro__country.country_name
						FROM client__main 
						LEFT JOIN pahro__country ON pahro__country.country_id = client__main.{$refine_field} ";								
				$mainSearchingTable = "client__main";								
			break;

			case "counter-party":	
				$refine_field = "cp_owned_country_id";													
				$sql = "SELECT counter_party__main.*, pahro__country.country_name FROM counter_party__main 
						LEFT JOIN pahro__country ON pahro__country.country_id = counter_party__main.{$refine_field} ";
				$mainSearchingTable = "counter_party__main";														
			break;			

			case "pahro":		
				$refine_field = "country_id";							
				$sql = "SELECT pahro__user.id, pahro__user.status, pahro__user_types.user_type, pahro__country.country_name, pahro__user.username, pahro__user.first_name, pahro__user.last_name, 
							   pahro__user.email, pahro__user.created_at, pahro__user.last_login,
							   pahro__users_countries.{$refine_field}, pahro__country.country_name
						FROM pahro__user 
						LEFT JOIN pahro__users_countries ON pahro__users_countries.user_id = pahro__user.id 						
						LEFT JOIN pahro__user_types ON pahro__user_types.user_type_id = pahro__user.user_type_id 
						LEFT JOIN pahro__country ON pahro__country.country_id = pahro__users_countries.country_id ";
				$mainSearchingTable = "pahro__user";																				
			break;		

			case "system":
				$refine_field = "country_id";											
				$sql = "SELECT pahro__log.id, pahro__user.username, pahro__log.action_type_desc, pahro__log.date_time,
							   pahro__users_countries.{$refine_field} 				 
						FROM pahro__user 
						LEFT JOIN pahro__users_countries ON pahro__users_countries.user_id = pahro__user.id 
						LEFT JOIN pahro__log ON pahro__log.user_id = pahro__user.id ";	
				$mainSearchingTable = "pahro__user";																				
			break;		
			
			default:			
				$sql .= "";
				return false;
				exit();
			break;				
		}
				
		if (($controller != "case") && ($controller != "case-category")){
			if ((array_key_exists("fname", $searchQuery)) && (array_key_exists("sname", $searchQuery))){
				$where .= " WHERE {$mainSearchingTable}.first_name LIKE '%".strtolower(trim($searchQuery['fname']))."%' AND {$mainSearchingTable}.last_name LIKE '%".strtolower(trim($searchQuery['sname']))."%'";				
			}elseif (array_key_exists("fname", $searchQuery)){
				$where .= " WHERE {$mainSearchingTable}.first_name LIKE '%".strtolower(trim($searchQuery['fname']))."%'";
			}elseif (array_key_exists("sname", $searchQuery)){
				$where .= " WHERE {$mainSearchingTable}.last_name LIKE '%".strtolower(trim($searchQuery['sname']))."%'";			
			}else{
				$where .= "";
			}	
			switch($controller){
				case "client": $owned_country_id = "client_owned_country_id"; $searching_table = "client__main"; break;
				case "counter-party": $owned_country_id = "cp_owned_country_id"; $searching_table = "counter_party__main"; break;
				case "pahro": $owned_country_id = "country_id"; $searching_table = "pahro__users_countries"; break;		
				case "system": $owned_country_id = "country_id"; $searching_table = "pahro__users_countries"; break;												
			}
			$where .= " AND {$searching_table}.{$owned_country_id} = {$curr_country}";						
		}elseif ($controller == "case"){
			if (array_key_exists("case_id", $searchQuery)){
				$where .= " WHERE {$mainSearchingTable}.reference_number LIKE '%".strtolower(trim(urldecode($searchQuery['case_id'])))."%'";				
			}else{
				$where .= "";
			}		
			$where .= " AND {$mainSearchingTable}.case_owned_country_id = {$curr_country}";								
		}elseif ($controller == "case-category"){
			if (array_key_exists("cat_name", $searchQuery)){
				$where .= " WHERE {$mainSearchingTable}.case_cat_name LIKE '%".strtolower(trim($searchQuery['cat_name']))."%'";				
			}else{
				$where .= "";
			}		
		}	
		$sql .= "{$where}{$sort}";
		$full_results = AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $params);				
		if ($controller != "case-category"){		
			foreach($full_results as $each_result){
				if (in_array($each_result[$refine_field], $user_country_params)){
					$final_result[] = $each_result;
				}
			}
		}	
		if (($controller == "pahro") || ($controller == "system")){
			$final_result = CommonFunctions::array_multi_unique($final_result, "id");		
		}			
		return ($controller != "case-category") ? count($final_result) : count($final_result);
	}
	/* End of the fucntion */		
	
	/* This function will insert log details every time against the user action */
	function keep_track_of_activity_log_in_search($logParmas){

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
}
?>