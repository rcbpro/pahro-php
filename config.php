<?php

require './define.inc';
require './lib/database.php';
require './lib/common_functions.php';
require './lib/controller.php';
require './lib/model.php';
require './lib/view.php';

class Configuration{

	var $db_settings = NULL;
	var $db = NULL;
	var $db_settings2 = NULL;
	var $db2 = NULL;
	
	var $controllers_routes = array("case", "client", "case-category", "counter-party", "pahro");	
	var $views_routes =	array("add", "view", "edit", "show", "drop", "notes", "index", "search", "download", "delete-multiple", "import", "delete", "acc-edit");	
	var $constable_routes = array(
								  array('url' => '/^\/$/', 'controller' => 'system', 'view' => 'home'),	
								  array('url' => '/^\/index.php$/', 'controller' => 'system', 'view' => 'home'),						  
								  // Route for Logged url with the username after loging
								  array('url' => '/^\/logged\/(\?[A-Za-z+&\$_.-][A-Za-z0-9;:@&%=+\/\$_.-]*)\/$/', 'controller' => 'system', 'view' => 'index'),						  					  
								  array('url' => '/^\/logged\/(\?[A-Za-z+&\$_.-][A-Za-z0-9;:@&%=+\/\$_.-]*)$/', 'controller' => 'system', 'view' => 'index'),						  					  					  
								  // Route for Logging url with the before loging
								  array('url' => '/^\/system\/login\/$/', 'controller' => 'system', 'view' => 'login'),						  					  
								  array('url' => '/^\/system\/login$/', 'controller' => 'system', 'view' => 'login'),						  					  					  
								  // Route for Logged url with the username after loging and the activity log
								  array('url' => '/^\/system\/logout\/$/', 'controller' => 'system', 'view' => 'logout'),						  					  
								  array('url' => '/^\/system\/logout$/', 'controller' => 'system', 'view' => 'logout'),						  					  					  
								  array('url' => '/^\/system\/activity-log\/$/', 'controller' => 'system', 'view' => 'activity-log'),						  					  					  
								  array('url' => '/^\/system\/activity-log$/', 'controller' => 'system', 'view' => 'activity-log'),						  					  					  					  
								  // Routes for System log with Query Strins						  
								  array('url' => '/^\/system\/activity-log\/(\?[A-Za-z+&\$_.-][A-Za-z0-9;:@&%=+\/\$_.-]*)?$/', 'controller' => 'system', 'view' => 'activity-log'),						  					  					  					  
								  // Route for Logged url with the username after loging and the activity log with search
								  array('url' => '/^\/system\/search\/(\?[A-Za-z+&\$_.-][A-Za-z0-9;:@&%=+\/\$_.-]*)?$/', 'controller' => 'search', 'view' => 'view'),						  					  					  					  
								  array('url' => '/^\/system\/search$(\?[A-Za-z+&\$_.-][A-Za-z0-9;:@&%=+\/\$_.-]*)?/', 'controller' => 'search', 'view' => 'view'),						  					  					  					  					  
								  // Routes whci use for the account changing purposes
								  array('url' => "/^\/pahro\/acc-edit(\?[A-Za-z+&\$_.-][A-Za-z0-9;:@&%=+\/\$_.-]*)?$/", 'controller' => 'pahro', 'view' => 'acc-edit'),
								  array('url' => "/^\/pahro\/acc-edit\/(\?[A-Za-z+&\$_.-][A-Za-z0-9;:@&%=+\/\$_.-]*)?$/", 'controller' => 'pahro', 'view' => 'acc-edit')
								 );					  					  					  					  

	/* This function will return the database connection */
	function return_db_connection(){
		
		$situation = (!strstr($_SERVER['REQUEST_URI'], "pahro/import")) ? "pahro" : "pahro_tagos";
		if ($situation == "pahro"){
			$this->db_settings = new PahroSettings();	
			$this->db = new DBFunctions();
			return $this->db->db_connect($this->db_settings->db_settings);
		}else{
			$connection2 = mysql_connect(HOST_TAGOS, USER_TAGOS, PASS_TAGOS);
			$_SESSION['connection2'] = $connection2;
			
			$this->db_settings = new PahroSettings();	
			$this->db = new DBFunctions();
			return $this->db->db_connect($this->db_settings->db_settings);
		}	
	}
	/* End of the function */
	
	/* This function will return the pahro web url */
	function return_site_base_details(){

		$this->db = new DBFunctions();		
		$pahro_site_base = array(
									'title' => $this->db->return_single_result_from_mysql_resource($this->db->execute_query("SELECT title FROM pahro__site_access"), 0),
									'base_url' => $this->db->return_single_result_from_mysql_resource($this->db->execute_query("SELECT base_url FROM pahro__site_access"), 0)										
								 );
		return $pahro_site_base;		
	}
	/* End of the function */			
	
	/* This function will return check the requested url exist */
	function check_routes_map($url){
	
		global $site_config;
		global $case_related_search;
		global $case_category_related_search;
		global $allow_pahro_access;	
		// This following code need only to check in the localhost
		//$url = str_replace('/'.APP_ROOT.'/', '', $url);
		// Holds the names captured
		$params = CommonFunctions::parse_param();
		// Becomes true if $route['url'] matches $url
		$route_match = false;
		$route = array();		
		// If match found append $matches to $params
		// Sets $route_match to true and also exit from the loop
		
		// Sets $route_match to true and also exit from the loop
		foreach($this->controllers_routes as $each_controller){
	
			foreach($this->views_routes as $each_view){
			
				$defined_url = '/^\/'.$each_controller.'\/'.$each_view.'\/(\?[A-Za-z+&\$_.-][A-Za-z0-9;:@&%=+\/\$_.-]*)?$/';			
				if(preg_match($defined_url, $url, $matches)){
					$params = (is_array($params)) ? array_merge($params, $matches) : null;
					$route_match = true;
					if ($each_view == "search"){
						$route['controller'] = "search";
						$route['view'] = "view";											
					}else{
						$route['controller'] = $each_controller;					
						$route['view'] = $each_view;										
					}
					$route['url'] = $matches[0];										
					break;
				}			
			}
		}
		if (!$route_match){

			foreach($this->constable_routes as $each_route){
				if(preg_match($each_route['url'], $url, $matches)){
					$params = (is_array($params)) ? array_merge($params, $matches) : null;
					$route['controller'] = $each_route['controller'];
					$route['view'] = $each_route['view'];					
					$route['url'] = $each_route['url'];										
					$route_match = true;									
					break;
				}
			}
		}	
		// If no matches found display error
		if (AppController::check_authentication()){
			$allow_pahro_access = $this->display_welcome_link();
			$search_params_arr = array("case", "case-category", "client", "counter-party", "pahro", "file-manager");
			$exploded_url = explode("/", $_SERVER['REQUEST_URI']);
			$search_controller = (in_array($exploded_url[1], $search_params_arr)) ? $exploded_url[1] : $route['controller'];
			if ($search_controller == "file-manager") $search_controller = "case";
			$case_related_search = $this->check_what_panel_to_load_in_search($search_controller);							
			if (!$case_related_search){
				$case_category_related_search = $this->check_exceptional_panel_to_load_in_search($search_controller);
			}	
		}	
		if(!$route_match){		
			// This is specifically added to the file management section					 
			if (!strstr($_SERVER['REQUEST_URI'], "file-manager/")){
				AppController::redirect_to($site_config['base_url']);			
			}else{			
				global $file_manager_template;
				$file_manager_template = 1;
			}
		}else{
			// Include controller	
			include CONTROLLER_PATH.$route['controller'].'.php';
			return VIEW_PATH.$route['controller'].DS.$route['view'].'.php';	
		}
	}
	/* End of the function */
	
	/* This function will act as the admin gate keeper for the permissions and redirections */
	function admin_gate_keeper($uri, $user_permissions){
	
		$match_against_routes = array(
									  "case/view" => 1, "case/add" => 2, "case/edit" => 3, "case/delete" => 4, 
									  "case-category/view" => 5, "case-category/add" => 6, "case-category/edit" => 7, "case-category/delete" => 8, 									  
									  "client/view" => 9, "client/add" => 10, "client/edit" => 11, "client/delete" => 12, 
									  "counter-party/view" => 13, "counter-party/add" => 14, "counter-party/edit" => 15, "counter-party/delete" => 16, 
									  "system/activity-log" => 17, 
									  "pahro/view" => 18, "pahro/add" => 19, "pahro/import" => 19, "pahro/edit" => 20, "pahro/delete" => 21, "pahro/status" => 21,
									  "case/search" => 1, "case-category/view" => 5, "client/search" => 9, "counter-party/search" => 13, "pahro/search" => 18, "system/search" => 17,
									  "case/show" => 1, "case-category/show" => 5, "client/show" => 9, "counter-party/show" => 13, "pahro/show" => 18, "system/show" => 17,									  
									  "counter-party/notes/?mode=edit-notes" => 71, "client/notes/?mode=edit-notes" => 67, "case/notes/?mode=edit-notes" => 62,
									  "case/notes/?mode=edit-notes&opt=edit" => 64, "client/notes/?mode=edit-notes&opt=edit" => 69, "counter-party/notes/?mode=edit-notes&opt=edit" => 74, 									  
									  "do=view_files&where=uploads/Agreements" => 49, "do=create_folder&where=uploads/Agreements" => 50, "do=upload_file&where=uploads/Agreements" => 50, "do=delete_file&where=uploads/Agreements" => 52,
									  "do=view_files&where=uploads/Handbooks" => 30, "do=create_folder&where=uploads/Handbooks" => 31, "do=upload_file&where=uploads/Handbooks" => 31, "do=delete_file&where=uploads/Handbooks" => 33,
									  "do=view_files&where=uploads/Legislation" => 22, "do=create_folder&where=uploads/Legislation" => 23, "do=upload_file&where=uploads/Legislation" => 23, "do=delete_file&where=uploads/Legislation" => 25,
									  "do=view_files&where=uploads/Minutes" => 41,  "do=create_folder&where=uploads/Minutes" => 42, "do=upload_file&where=uploads/Minutes" => 42, "do=delete_file&where=uploads/Minutes" => 44,
									  "do=view_files&where=uploads/Miscellaneous" => 53, "do=create_folder&where=uploads/Miscellaneous" => 54, "do=upload_file&where=uploads/Miscellaneous" => 54, "do=delete_file&where=uploads/Miscellaneous" => 56,
									 // "do=view_files&where=uploads/Other" => 33, "do=create_folder&where=uploads/Other" => 34, "do=upload_file&where=uploads/Other" => 34, "do=delete_file&where=uploads/Other" => 36,
									  "do=view_files&where=uploads/Parliamentary" => 34, "do=create_folder&where=uploads/Parliamentary" => 35, "do=upload_file&where=uploads/Parliamentary" => 35, "do=delete_file&where=uploads/Parliamentary" => 57,
									  "do=view_files&where=uploads/Research" => 26, "do=create_folder&where=uploads/Research" => 27, "do=upload_file&where=uploads/Research" => 27, "do=delete_file&where=uploads/Research" => 29,
									  "do=view_files&where=uploads/Templates" => 45, "do=create_folder&where=uploads/Templates" => 46, "do=upload_file&where=uploads/Templates" => 46, "do=delete_file&where=uploads/Templates" => 48,
									  "do=view_files&where=uploads/Other%20Organisations" => 37, "do=create_folder&where=uploads/Other%20Organisations" => 38, "do=upload_file&where=uploads/Other%20Organisations" => 38, "do=delete_file&where=uploads/Other%20Organisations" => 40,
									  "do=view_files&where=uploads/Other Organisations" => 37, "do=create_folder&where=uploads/Other%20Organisations" => 38, "do=upload_file&where=uploads/Other%20Organisations" => 38, "do=delete_file&where=uploads/Other%20Organisations" => 40,									  
									  "do=view_files&where=uploads/Social%20Justice" => 58, "do=create_folder&where=uploads/Social%20Justice" => 59, "do=upload_file&where=uploads/Social%20Justice" => 59, "do=delete_file&where=uploads/Social%20Justice" => 61,									  
									  "do=view_files&where=uploads/Social Justice" => 58, "do=create_folder&where=uploads/Social Justice" => 59, "do=upload_file&where=uploads/Social Justice" => 59, "do=delete_file&where=uploads/Social Justice" => 61									  									  
									);

		$redirection_location = "http://".$_SERVER['HTTP_HOST'];
		$redirection_location .= (strstr($uri, "file-manager")) ? "/file-manager/?do=view_files&where=uploads/" : "";

		foreach($match_against_routes as $key => $value){
			if (@strstr($uri, $key)){
				if (!@in_array($value, $user_permissions)){
					AppController::redirect_to($redirection_location);
					exit();
				}
			}
		}
	}
	/* End of the fucntion */
	
	/* This function will load the with the permission granted left menu for each user */
	function load_left_menu_for_given_permissions($logged_user_permissions){

		global $connection;
		global $site_config;
		$this->db = new DBFunctions();
		$html_menu = array();
		$left_menu_html = array();
		$params = array("menu_id", "menu_text", "menu_url");
		// Read the menus from the menu table
		$left_menu = $this->db->result_to_array_for_few_fields($this->db->execute_query("SELECT * FROM pahro__menu_system WHERE status = 1 ORDER BY menu_id"), $params);
		$i=0;
		// Puttiing all menus internal items with collecting their relevant permission ids
		foreach($left_menu as $menu){
			$left_menu_html[$i]['menu_text'] = $menu['menu_text'];
			$left_menu_html[$i]['menu_url'] = $menu['menu_url'];
			$left_menu_html[$i]['permissions'] = $this->retrieve_all_permissions_for_the_menu($menu['menu_id']);
			$i++;
		}
		// Finally filtering those menu items against logged user permission ids
		$n=0;	
		foreach($left_menu_html as $each_left_menu){
	
			for($i=0; $i<count($logged_user_permissions); $i++){
				if (in_array($each_left_menu['permissions'][$i], $logged_user_permissions)){
					$html_menu[$n]['menu_text'] = $each_left_menu['menu_text'];
					$html_menu[$n]['menu_url'] = $each_left_menu['menu_url'];
				}
			}
			// This is for Displaying the 'System' text and for the Logout menu
			if ($each_left_menu['permissions'][0] == 0){

				$html_menu[$n]['menu_text'] = $each_left_menu['menu_text'];
				$html_menu[$n]['menu_url'] = $each_left_menu['menu_url'];
			} 	
			$n++;
		}		
		return $html_menu;
	}
	/* End of the function */
	
	/* This function will load all permissions related to given menu id */
	function retrieve_all_permissions_for_the_menu($menu_id){

		global $connection;	
		$this->db = new DBFunctions();
		return $this->db->result_to_single_array_of_data($this->db->execute_query("SELECT permission_id FROM pahro__menu_system_permissions WHERE menu_id = {$menu_id}"), "permission_id");
	}
	/* End of the function */
	
	/* This function will change the left menu slected style agasinst the visited url */
	function is_current_url($cur_url, $loaded_menu){

		$cur_url = explode("/", $_SERVER['REQUEST_URI']);
		$cur_url_to_check = $cur_url[1].DS.$cur_url[2];
		if ($cur_url_to_check != "/") $visited = (strstr($loaded_menu, $cur_url_to_check)) ? true : false;
		if (!$visited){
		
			switch($cur_url_to_check){
				case "case/show": case "case/edit": case "case/notes/?mode=edit-notes": case "case/search": $visited = (strstr($loaded_menu, "case/view")) ? true : false; break;				
				case "case-category/show": case "case-category/edit": case "case-category/search": $visited = (strstr($loaded_menu, "case-category/view")) ? true : false; break;				
				case "client/show": case "client/edit": case "client/notes/?mode=edit-notes": case "client/search": $visited = (strstr($loaded_menu, "client/view")) ? true : false; break;
				case "counter-party/show": case "counter-party/edit": case "counter-party/notes/?mode=edit-notes": case "counter-party/search": $visited = (strstr($loaded_menu, "counter-party/view")) ? true : false; break;
				case "pahro/show": case "pahro/edit": case "pahro/notes": case "pahro/search": $visited = (strstr($loaded_menu, "pahro/view")) ? true : false; break;
				case "system/search": case "pahros/search": $visited = (strstr($loaded_menu, "system/activity-log")) ? true : false; break;				
				case "client/notes": $visited = (strstr($loaded_menu, "client/view")) ? true : false; break;								
				case "case/notes": $visited = (strstr($loaded_menu, "case/view")) ? true : false; break;								
				case "counter-party/notes": $visited = (strstr($loaded_menu, "counter-party/view")) ? true : false; break;																
				default: $visited  = false;
			}
		}
		return $visited;
	} 
	/* End of the function */
	
	/* This function load the permissions regrading to the currently logging user */
	function retrieve_all_permissions_for_the_logged_user($user_id){
	
		global $connection;
		$sql = "SELECT pahro__user_permission_rel.permission_id 
				FROM pahro__user_permission_rel 
				WHERE pahro__user_permission_rel.user_id = {$user_id}";	
		return AppModel::grab_db_function_class()->result_to_single_array_of_data(AppModel::grab_db_function_class()->execute_query($sql), 'permission_id');				
	}
	/* End of the function */

	/* This fucntion will grab the full details for the logged in user */
	function grab_users_countires_for_the_loggedin_user($id){
	
		global $connection;
		return AppModel::grab_db_function_class()->result_to_single_array_of_data(AppModel::grab_db_function_class()->execute_query("SELECT country_id FROM pahro__users_countries WHERE pahro__users_countries.user_id = {$id}"), "country_id");							
	}
	/* End of the fucntion */

	/* This fucntion will grab the full details for the logged in user */
	function grab_users_counties_names_for_loggedin_users($id){
	
		global $connection;
		$countries = array('country_id', 'country_name');
		$countries = AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query("SELECT pahro__country.country_id, pahro__country.country_name FROM pahro__users_countries JOIN pahro__country ON pahro__users_countries.country_id = pahro__country.country_id WHERE pahro__users_countries.user_id = {$id}"), $countries);							
		if (count($countries) == 1){
			$_SESSION['curr_country']['country_code'] = $countries[0]['country_id'];
			$_SESSION['curr_country']['country_name'] = $countries[0]['country_name'];
		}
		return $countries;
	}
	/* End of the fucntion */
	
	/* This function will check the search layout for what to load according to the requested uri */
	function check_what_panel_to_load_in_search($controller){ return (((strstr($_SERVER['REQUEST_URI'], "file-manager")) && ($controller == "search")) || ($controller == "case")) ? true : false; }
	/* End of the function */	

	/* This function will check the search layout for what to load according to the requested uri */
	function check_exceptional_panel_to_load_in_search($controller){ return $case_category_related_search = ($controller == "case-category") ? true : false; }
	/* End of the function */	
	
	/* This function will display the header text as according to the url in the main find form */
	function display_the_main_find_form_header($url){
	
		$exploded_url = explode("/", $url);
		switch($exploded_url[1]){
		
			case "client": $headerText = "Find Client"; break;
			case "case-category": $headerText = "Find Case Category"; break;
			case "counter-party": $headerText = "Counter Party"; break;
			case "pahro": $headerText = "System Users"; break;
			case "file-manager": $headerText = "Find Case"; break;						
			case "system": $headerText = "Activities"; break;									
		}
		return $headerText;
	}
	/* End of the function */
	
	/* This function will grab all case ids for search suggestions */
	static function grab_case_ids_for_search_suggestions($user_countries_params, $curr_country){

		global $connection;
		$searchStr = "";
		$params = array('case_owned_country_id', 'reference_number');
		$sql = "SELECT case_owned_country_id, reference_number FROM case__main WHERE case_owned_country_id = {$curr_country}";		
		$case_ids = AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $params);				
		// Refine to display only country included users
		foreach($case_ids as $each_id){
		
			if (in_array($each_id['case_owned_country_id'], $user_countries_params)){
				$refined_cases[] = $each_id;
			}
		}		
		// Concat each case id to display in the search suggestion		
		foreach($refined_cases as $each_id){
			$searchStr .= $each_id['reference_number'] . "|";
		}		
		return substr($searchStr, 0, -1);
	}
	/* End of the function */

	/* This function will grab all case ids for search suggestions */
	static function grab_case_categeries_for_search_suggestions(){

		global $connection;
		$searchStr = "";		
		$params = array('case_cat_id', 'case_cat_name', 'case_cat_description');
		$case_cats = AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query("SELECT case_cat_id, case_cat_name, case_cat_description	FROM case__category"), $params);				
		// Concat each case id to display in the search suggestion		
		foreach($case_cats as $each_cat){
			$searchStr .= $each_cat['case_cat_name'] . "|";
		}		
		return substr($searchStr, 0, -1);
	}
	/* End of the function */

	/* This function will grab all names for search suggestions */
	static function grab_names_for_search_suggestions($controller, $name_type, $user_countries_params, $curr_country){
	
		global $connection;
		$final_result = array();		
		$searchStr = "";
		$which_field = ($name_type == "first_name") ? "first_name" : "last_name";		
		switch($controller){
		
			case "client": 
				$main_field = "client_owned_country_id";
				$sql = "SELECT {$main_field}, {$which_field} FROM client__main WHERE {$main_field} = {$curr_country}";				
				$params = array('client_owned_country_id', $which_field);				
			break;	
			case "counter-party": 
				$main_field = "cp_owned_country_id";			
				$sql = "SELECT {$main_field}, {$which_field} FROM counter_party__main WHERE {$main_field} = {$curr_country}";								
				$params = array('cp_owned_country_id', $which_field);								
			break;	
			case "pahro": 
				$main_field = "country_id";						
				$sql = "SELECT pahro__user.{$which_field} AS name, pahro__users_countries.{$main_field} 
					   	FROM pahro__users_countries 
					    LEFT JOIN pahro__user ON pahro__user.id = pahro__users_countries.user_id 
						WHERE pahro__users_countries.country_id = {$curr_country}";
				$params = array('country_id', 'name');														
			break;
			case "system": 
				$main_field = "country_id";									
				$sql = "SELECT pahro__user.first_name AS name, pahro__users_countries.country_id 
						FROM pahro__users_countries 
						LEFT JOIN pahro__user ON pahro__user.id = pahro__users_countries.user_id	
						WHERE pahro__users_countries.country_id = {$curr_country}";
				$params = array('country_id', 'name');														
			break;					
			default: 
				$sql = "SELECT client_owned_country_id, {$which_field} FROM client__main WHERE client_owned_country_id = {$curr_country}";				
				$params = array('client_owned_country_id', $which_field);								
			break;							
		}
		$names = AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $params);				
		if (($controller == "system") || ($controller == "pahro")){
			$final_result = CommonFunctions::array_multi_unique($names, 'name');		
		}else{		
			$final_result = CommonFunctions::array_multi_unique($names, $which_field);		
		}	
		
		// Refine to display only country included users
		foreach($final_result as $each_name){
		
			if (in_array($each_name[$main_field], $user_countries_params)){
				$refined_names[] = $each_name;
			}
		}		
		// Concat each name to display in the search suggestion
		if (($controller == "system") || ($controller == "pahro")){
			$which_field = "name";
		}
		foreach($refined_names as $each_name){
			$searchStr .= $each_name[$which_field] . "|";
		}		
		return substr($searchStr, 0, -1);
	}
	/* End of the function */
	
	/* This function will check the permissions and then apply the welcome link for each user's account edting */
	function display_welcome_link(){
	
		if (
			((isset($_SESSION['logged_user']['user_type_id'])) && ($_SESSION['logged_user']['user_type_id'] == 2)) || 
			(isset($_SESSION['logged_user']['permissions'])) && (!in_array(20, $_SESSION['logged_user']['permissions']))
		   ){
				$allow_pahro_access = false;
		}else{
				$allow_pahro_access = true;			
		}
		return $allow_pahro_access;
	}
	/* End of the fucntion */
}
?>