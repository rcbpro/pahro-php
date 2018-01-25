<?php

class SearchController extends AppController{

	/* This function will check the correct sub view provided else redirect to the home page */
	function correct_sub_view_gate_keeper($subView, $controller){

		global $site_config;
		$modes_array = array("sname", "fname", "page", "by", "sort", "case_id", "cat_name");
		foreach($subView as $key => $value){
			if (!in_array($key, $modes_array)){
				AppController::redirect_to($site_config['base_url']."{$controller}/view/");
				break;
			}
		}
	} 
	/* End of the fucntion */

	/* This function will process the correct model for the given view */
	function process_the_correct_model($view){

		switch($view) {
		
			case "view":
				// All Global variables
				global $site_config;
				global $all_search_results;
				global $all_search_results_count;
				global $pagination;
				global $tot_page_count;
				global $cur_page;
				global $img;															
				global $searchQ;
				global $breadcrumb;	
				global $splittedUrl;
				global $action_panel_menu;
				global $have_permissions;
				global $caseModel;
				$sortBy = "";															
				$query = "";														
				$searchQ = "";
				$breadcrumb = "";
				$sortPath = "";
				$divInternalMessage = "";
				// Object Instantiation
				$searchModel = new SearchModel();
				$pagination_obj = new Pagination();				
				// Viewing all contacts in the table
				$cur_page = ((isset($_GET['page'])) && ($_GET['page'] != "") && ($_GET['page'] != 0)) ? $_GET['page'] : 1; 												
				// Providing the neccessary message as for the given controller
				switch($_SESSION['Controller_to_search']){
				
					case "case":
						include MODEL_PATH.$_SESSION['Controller_to_search'].'.php'; 
						$divInternalMessage = "Case";  
						$caseModel = new CaseModel(); 
						$action_panel = array(
												array(
														"menu_id" => 1,
														"menu_text" => "Add / Edit Note",
														"menu_url" => $site_config['base_url']."case/edit/?mode=notes",
														"menu_img" => "<img src=\"../../public/images/notepadicon.png\" border=\"0\" alt=\"New / Edit Note\" />",
														"menu_permissions" => array(1)
													),	
												array(
														"menu_id" => 2,
														"menu_text" => "Details",
														"menu_url" => $site_config['base_url']."case/show/",
														"menu_img" => " <img src=\"../../public/images/b_browse.png\" border=\"0\" alt=\"Browse\" />",
														"menu_permissions" => array(1)
													),	
												array(
														"menu_id" => 3,
														"menu_text" => "Edit",
														"menu_url" => $site_config['base_url']."case/edit/?mode=main",
														"menu_img" => " <img src=\"../../public/images/b_edit.png\" border=\"0\" alt=\"Edit\" />",
														"menu_permissions" => array(3)
													),
												array(
														"menu_id" => 4,
														"menu_text" => "Delete",
														"menu_url" => $site_config['base_url']."case/delete/",
														"menu_img" => " <img src=\"../../public/images/b_drop.png\" border=\"0\" alt=\"Delete\" />",
														"menu_permissions" => array(4)
													)														
												);	
					break;					
					case "case-category":
						include MODEL_PATH.$_SESSION['Controller_to_search'].'.php'; 
						$divInternalMessage = "Case Category";  
						$CaseCategoryModel = new CaseCategoryModel(); 
						$action_panel = array(
												array(
														"menu_id" => 2,
														"menu_text" => "Details",
														"menu_url" => $site_config['base_url']."case-category/show/",
														"menu_img" => " <img src=\"../../public/images/b_browse.png\" border=\"0\" alt=\"Browse\" />",
														"menu_permissions" => array(5)
													),	
												array(
														"menu_id" => 3,
														"menu_text" => "Edit",
														"menu_url" => $site_config['base_url']."case-category/edit/?mode=main",
														"menu_img" => " <img src=\"../../public/images/b_edit.png\" border=\"0\" alt=\"Edit\" />",
														"menu_permissions" => array(7)
													),
												array(
														"menu_id" => 4,
														"menu_text" => "Delete",
														"menu_url" => $site_config['base_url']."case-category/delete/",
														"menu_img" => " <img src=\"../../public/images/b_drop.png\" border=\"0\" alt=\"Delete\" />",
														"menu_permissions" => array(8)
													)														
												);	
					break;					
					case "client": 
						$divInternalMessage = "Client"; 
						// Configuring the action panel against user permissions
						$action_panel = array(
												array(
														"menu_id" => 1,
														"menu_text" => "Add / Edit Note",
														"menu_url" => $site_config['base_url']."client/edit/?mode=notes",
														"menu_img" => "<img src=\"../../public/images/notepadicon.png\" border=\"0\" alt=\"New / Edit Note\" />",
														"menu_permissions" => array(9)
													),	
												array(
														"menu_id" => 2,
														"menu_text" => "Details",
														"menu_url" => $site_config['base_url']."client/show/",
														"menu_img" => " <img src=\"../../public/images/b_browse.png\" border=\"0\" alt=\"Browse\" />",
														"menu_permissions" => array(9)
													),	
												array(
														"menu_id" => 3,
														"menu_text" => "Edit",
														"menu_url" => $site_config['base_url']."client/edit/?mode=main",
														"menu_img" => " <img src=\"../../public/images/b_edit.png\" border=\"0\" alt=\"Edit\" />",
														"menu_permissions" => array(11)
													),
												array(
														"menu_id" => 3,
														"menu_text" => "Delete",
														"menu_url" => $site_config['base_url']."client/drop/",
														"menu_img" => " <img src=\"../../public/images/b_drop.png\" border=\"0\" alt=\"Drop\" />",
														"menu_permissions" => array(12)
													)	
												);	
					break;
					case "counter-party": 
						$divInternalMessage = "Counter Party"; 
						// Configuring the action panel against user permissions
						$action_panel = array(
												array(
														"menu_id" => 1,
														"menu_text" => "Add / Edit Note",
														"menu_url" => $site_config['base_url']."counter-party/edit/?mode=notes",
														"menu_img" => "<img src=\"../../public/images/notepadicon.png\" border=\"0\" alt=\"New / Edit Note\" />",
														"menu_permissions" => array(13)
													),	
												array(
														"menu_id" => 2,
														"menu_text" => "Details",
														"menu_url" => $site_config['base_url']."counter-party/show/",
														"menu_img" => " <img src=\"../../public/images/b_browse.png\" border=\"0\" alt=\"Browse\" />",
														"menu_permissions" => array(13)
													),	
												array(
														"menu_id" => 3,
														"menu_text" => "Edit",
														"menu_url" => $site_config['base_url']."counter-party/edit/?mode=main",
														"menu_img" => " <img src=\"../../public/images/b_edit.png\" border=\"0\" alt=\"Edit\" />",
														"menu_permissions" => array(15)
													),
												array(
														"menu_id" => 3,
														"menu_text" => "Delete",
														"menu_url" => $site_config['base_url']."counter-party/drop/",
														"menu_img" => " <img src=\"../../public/images/b_drop.png\" border=\"0\" alt=\"Drop\" />",
														"menu_permissions" => array(16)
													)	
												);	
					break;
					case "pahro": 
						$divInternalMessage = "System user"; 
						// Configuring the action panel against user permissions
						$action_panel = array(
												array(
														"menu_id" => 1,
														"menu_text" => "Add / Edit Note",
														"menu_url" => $site_config['base_url']."pahro/edit/?mode=notes&",
														"menu_img" => "<img src=\"../../public/images/notepadicon.png\" border=\"0\" alt=\"New / Edit Note\" />",
														"menu_permissions" => array(18)
													),	
												array(
														"menu_id" => 2,
														"menu_text" => "Details",
														"menu_url" => $site_config['base_url']."pahro/show/?",
														"menu_img" => " <img src=\"../../public/images/b_browse.png\" border=\"0\" alt=\"Browse\" />",
														"menu_permissions" => array(18)
													),	
												array(
														"menu_id" => 3,
														"menu_text" => "Edit",
														"menu_url" => $site_config['base_url']."pahro/edit/?",
														"menu_img" => " <img src=\"../../public/images/b_edit.png\" border=\"0\" alt=\"Edit\" />",
														"menu_permissions" => array(20)
													),	
												array(
														"menu_id" => 4,
														"menu_text" => "Drop",
														"menu_url" => $site_config['base_url']."pahro/delete/?",
														"menu_img" => " <img src=\"../../public/images/b_drop.png\" border=\"0\" alt=\"Drop\" />",
														"menu_permissions" => array(21)
													)	
												);	
					break;
					case "system": $divInternalMessage = "Users Activities"; break;					
				}
				// Bredcrmb to the pfa section
				$breadcrumb .= "<div class=\"breadcrumbMessageDiv defaultFont boldText\"><a href=\"".$site_config['base_url']."\" class=\"headerLink\">Home</a> &rsaquo; {$divInternalMessage} Search Results</div>";
				// Configuring the action panel against user permissions
				$no_of_permissions = 0;
				for($n=0; $n<count($action_panel); $n++){
				
					for($i=0; $i<count($_SESSION['logged_user']['permissions']); $i++){
		
						if (in_array($action_panel[$n]['menu_permissions'][$i], $_SESSION['logged_user']['permissions'])){
							$action_panel_menu[$n]['menu_text'] = $action_panel[$n]['menu_text'];
							$action_panel_menu[$n]['menu_url'] = $action_panel[$n]['menu_url'];
							$action_panel_menu[$n]['menu_img'] = $action_panel[$n]['menu_img'];	
							$have_permissions = true;
							$no_of_permissions++;
						}
					}
				}
				// Load the neccassary table according to the suitable controller								
				$controllers = array(
									 	"case" => "case__main",
										"case-category" => "case__category",
									 	"client" => "client__main",											
										"counter-party" => "counter_party__main",
										"pahro" => "pahro__user",										
										"system" => "pahro__log"
									);
				foreach($controllers as $controller => $table){
					if ($controller == $_SESSION['Controller_to_search']){
						$searchTable[] = $table;
					}
				}					
				// Creating the search query
				if ((isset($_GET['fname'])) && (isset($_GET['sname']))){
					$query = array("fname" => $_GET['fname'], "sname" => $_GET['sname']);				
					$searchQ = "?fname=".$_GET['fname']."&sname=".$_GET['sname'];
				}elseif (isset($_GET['fname'])){
					$query = array("fname" => $_GET['fname']);
					$searchQ = "?fname=".$_GET['fname'];					
				}elseif (isset($_GET['sname'])){
					$query = array("sname" => $_GET['sname']);			
					$searchQ = "?sname=".$_GET['sname'];												
				}elseif (isset($_GET['case_id'])){
					$query = array("case_id" => $_GET['case_id']);			
					$searchQ = "?case_id=".$_GET['case_id'];												
				}elseif (isset($_GET['cat_name'])){
					$query = array("cat_name" => $_GET['cat_name']);			
					$searchQ = "?cat_name=".$_GET['cat_name'];												
				}
				
				// Providing the param array as neccessary to the given controller
				switch($_SESSION['Controller_to_search']){
				
					case "case": $param_array = array('reference_number', 'case_owned_country_id', 'country_name', 'case_name', 'description', 'case_cat_name', 'status', 'opend_date', 'upcoming_date', 'username', 'staff_responsible', 'created_date'); break;

					case "case-category": $param_array = array('case_cat_name', 'case_cat_description', 'case_cat_id'); break;
					
					case "client": $param_array = array('client_id', 'title', 'first_name', 'client_owned_country_id', 'country_name', 'last_name', 'martial_status', 'resident_address', 'land_phone', 'country', 'address_of_employment', 'email'); break;

					case "counter-party": $param_array = array('counter_party_id', 'cp_owned_country_id', 'country_name', 'title', 'first_name', 'last_name', 'resident_address', 'postal_address', 'land_phone', 'mobile_phone', 'email', 'company_name'); break;

					case "pahro": $param_array = array('id', 'status', 'user_type', 'username', 'country_id', 'country_name', 'first_name', 'last_name', 'email', 'created_at', 'last_login'); break;
					
					case "system": $param_array = array('id', 'username', 'action_type_desc', 'date_time', 'country_id', 'country_name'); break;
				}				
				// if SORTED then generate the neccessary sorting param array														
				$jumpPath .= $site_config['base_url']."search/view/";								
				if (isset($_GET['sort'])){	
				
					$imgDefault = "<a href=\"".$site_config['base_url']."{$_SESSION['Controller_to_search']}/search/".$searchQ."&sort=".$_GET['sort']."&by=asc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\"><img src=\"../../public/images/s_asc.png\" border=\"0\" /></a>";			
					$imgAsc = "<a href=\"".$site_config['base_url']."{$_SESSION['Controller_to_search']}/search/".$searchQ."&sort=".$_GET['sort']."&by=asc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\"><img src=\"../../public/images/s_asc.png\" border=\"0\" /></a>";			
					$imgDesc = "<a href=\"".$site_config['base_url']."{$_SESSION['Controller_to_search']}/search/".$searchQ."&sort=".$_GET['sort']."&by=desc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\"><img src=\"../../public/images/s_desc.png\" border=\"0\" /></a>";								
					$img = (!isset($_GET['by'])) ? ($imgDefault) : (($_GET['by'] == "asc") ? $imgDesc : $imgAsc);

					switch($_SESSION['Controller_to_search']){
					
						case "case":
							$sort_param_array = array(
													  "ref_no" => "reference_number", "case" => "case_name", "desc" => "description", "own_country" => "country_name",
													  "case_cat" => "case_cat_name", "status" => "status", "op_date" => "opend_date", "up_date" => "upcoming_date",
													  "res_staff" => "staff_responsible", "cre_date" => "created_date", "cre_by" => "username"
													  );
							$param_array = array('reference_number', 'case_owned_country_id', 'case_name', 'description', 'case_cat_name', 'status', 'opend_date', 'upcoming_date', 'username', 'staff_responsible', 'created_date', 'country_name');							
						break;

						case "case-category":
							$sort_param_array = array("cat_name" => "case_cat_name", "cat_desc" => "case_cat_description");
							$param_array = array('case_cat_name', 'case_cat_description', 'case_cat_id');
						break;
					
						
						case "client":
							$sort_param_array = array(
													  "f_name" => "first_name", "l_name" => "last_name", "own_country" => "country_name",
													  "mar_stat" => "martial_status", "res" => "resident_address", "l_phone" => "land_phone", "coun" => "country",
													  "emp_add" => "address_of_employment", "email" => "email"
													  );
						
							$param_array = array('client_id', 'title', 'first_name', 'last_name', 'client_owned_country_id', 'martial_status', 'resident_address', 'land_phone', 'country', 'address_of_employment', 'email', 'country_name');												 
						break;

						case "counter-party":
							$sort_param_array = array(
													  "f_name" => "first_name", "l_name" => "last_name", "pos_add" => "postal_address", "mob" => "mobile_phone",
													  "res" => "resident_address", "l_phone" => "land_phone", "comp" => "company_name", "email" => "email", "own_country" => "country_name"
													  );
													
							$param_array = array('counter_party_id', 'title', 'first_name', 'last_name', 'cp_owned_country_id', 'resident_address', 'postal_address', 'land_phone', 'mobile_phone', 'email', 'company_name', 'country_name');													 
						break;

						case "pahro":
							$sort_param_array = array(
													  "u_type" => "user_type", "u_name" => "username", "f_name" => "first_name", "l_name" => "last_name", "email" => "email", 
													  "created_at" => "created_at", "last_login" => "last_login", "con" => "country_name", "own_country" => "country_name"
													 );
							$param_array = array('id', 'status', 'user_type', 'username', 'country_name', 'first_name', 'last_name', 'email', 'created_at', 'last_login', 'country_id', 'country_name'); 													 
						break;
						
						case "system":
							$sort_param_array = array("past" => "id", "u_name" => "username", "act_desc" => "action_type_desc", "time" => "date_time", "own_country" => "country_name");
							$param_array = array('id', 'username', 'action_type_desc', 'date_time', 'country_id', 'country_name');							
						break;
					}
	
					foreach($sort_param_array as $key => $value) {
						if ($key == $_GET['sort']) {
							$sortBy = $value;
						}
					}					 
				}
				// Creating the path for pagintion
				$cur_path = $_SERVER['REQUEST_URI']; 
				// Display all the records
				$all_search_results = $searchModel->display_all_search_results($_SESSION['Controller_to_search'], $searchTable, $query, $param_array, $cur_page, $sortBy, @$_GET['by'], $_SESSION['logged_user']['countries'], $_SESSION['curr_country']['country_code']);
				$all_search_results_count = $searchModel->display_count_on_all_search_results($_SESSION['Controller_to_search'], $searchTable, $query, $param_array, $cur_page, $sortBy, @$_GET['by'], $_SESSION['logged_user']['countries'], $_SESSION['curr_country']['country_code']);
				// Pagination load
				$pagination = $pagination_obj->generate_pagination($all_search_results_count, $cur_path, (($_SESSION['Controller_to_search'] == "system") ? 100 : NO_OF_RECORDS_PER_PAGE_FOR_LOG));				
				$tot_page_count = ceil($all_search_results_count/(($_SESSION['Controller_to_search'] == "system") ? 100 : NO_OF_RECORDS_PER_PAGE_FOR_LOG));	
				// Log keeping
				$log_params = array(
									"user_id" => $_SESSION['logged_user']['id'], 
									"action_desc" => "Searched for a {$_SESSION['Controller_to_search']} contact",
									"date_crated" => date("Y-m-d-H:i:s")
									);
				$searchModel->keep_track_of_activity_log_in_search($log_params);
				// Unset all used variables
				unset($searchModel);
				unset($pagination_obj);
				unset($site_config);
				unset($all_search_results);
				unset($all_search_results_count);
				unset($pagination);
				unset($tot_page_count);
				unset($cur_page);
				unset($img);															
				unset($searchQ);
				unset($pathToJump);
				unset($breadcrumb);	
				unset($splittedUrl);
				unset($jumpPath);
				unset($pagePath);																			
			break;					
		}	
	}
}
?>