<?php

class CaseCategoryController extends AppController{	

	/* This function will process the correct model for the given view */
	function process_the_correct_model($view, $controller){

		switch($view) {
		
			case "add":
				// All Global variables
				global $headerDivMsg;
				global $site_config;
				global $printHtml;
				global $breadcrumb;
				$breadcrumb = "";
				// Generate the top header menu
				$printHtml = "Main Details";
				// Bredcrmb to the pfa section
				$breadcrumb .= "<div class=\"breadcrumbMessageDiv defaultFont boldText\"><a href=\"".$site_config['base_url']."\" class=\"headerLink\">Home</a> &rsaquo; Add New Case Category</div>";
				// Object Instantiation
				$caseCatModel = new CaseCategoryModel(); 
				if (isset($_SESSION['new_case_category_added'])){
					// Display the error message						
					$headerDivMsg = "<div class=\"headerMessageDivInSuccess defaultFont\">New Case Category was added successfully.</div>";
					unset($_SESSION['new_case_category_added']);
				}
				// If post has been submitted
				if ("POST" == $_SERVER['REQUEST_METHOD']){
					$errors = AppModel::validate($_POST['case_category_main_reqired']);
					$_SESSION['case_category_main_reqired'] = $_POST['case_category_main_reqired'];
					$_SESSION['case_category_main_non_reqired'] = $_POST['case_category_main_non_reqired'];
					// Data Grabbing and validating for the required in contact view										
					if (isset($_POST['case_cat_main_submit'])){	
						// If errors found in the adding section
						if ($errors){
							$_SESSION['case_category_reqired_errors'] = $errors;					
							// Display the error message						
							$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Required fields cannot be blank !</div>";
						}else{		
							$case_cat_details = array(
														"case_cat_name" => $_POST['case_category_main_reqired']['case_category_name'],
														"case_cat_desc" => $_POST['case_category_main_non_reqired']['category_description'],
											   		 );
							// Insert case details				   
							$_SESSION['newly_inserted_case_cat_name'] = $caseCatModel->insert_case_cat_details($case_cat_details);
							// Log keeping
							$log_params = array(
												"user_id" => $_SESSION['logged_user']['id'], 
												"action_desc" => "New CASE Category was saved. Name : ".trim($_SESSION['newly_inserted_case_cat_name']),
												"date_crated" => date("Y-m-d-H:i:s")
												);
							$caseCatModel->keep_track_of_activity_log_in_case_category($log_params);
							$_SESSION['new_case_category_added'] = "true";
							// Unset all used data
							unset($_SESSION['case_category_reqired_errors']);						
							unset($_SESSION['case_category_main_reqired']);
							unset($_SESSION['case_category_main_non_reqired']);
							unset($_SESSION['newly_inserted_case_cat_name']);
							unset($caseCatModel);	
							AppController::redirect_to($site_config['base_url']."case-category/add/");												
						}
					}		
				}	
			break;
			
			case "view":
				// All Global variables
				global $site_config;
				global $all_case_categories;
				global $all_case_categories_count;
				global $pagination;
				global $tot_page_count;
				global $img;
				global $breadcrumb;
				global $invalidPage;								
				global $headerDivMsg;
				global $action_panel_menu;
				global $cur_page;
				$breadcrumb = "";
				$sortBy = "";
				// Bredcrmb to the pfa section
				$breadcrumb .= "<div class=\"breadcrumbMessageDiv defaultFont boldText\"><a href=\"".$site_config['base_url']."\" class=\"headerLink\">Home</a> &rsaquo; Manage Case Categories</div>";
				// Object Instantiation
				$caseCatModel = new CaseCategoryModel(); 
				$pagination_obj = new Pagination();								
				// Display the error message if the given case cat id not exist
				if ($_SESSION['is_assigned_for_case'] == "true"){
					$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Case Category cannot be deleted : Has been assigned to case.&nbsp;&nbsp;<a class='' href='".
							$site_config['base_url']."case-category/show/?cat_id=".$_SESSION['temp_case_cat_id']."'>See cases.</a></div>";																																			
				}
				unset($_SESSION['is_assigned_for_case']);
				unset($_SESSION['temp_case_cat_id']);
				// Display the error message if the given case cat id not exist
				if ($_SESSION['is_not_exist_case_cat_id'] == "true"){
					$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Selected Case Category the not exist !</div>";										
				}
				unset($_SESSION['is_not_exist_case_cat_id']);
				// Display the success message 
				if ($_SESSION['deleted_case_cat_id'] == "true"){
					$headerDivMsg = "<div class=\"headerMessageDivInNotice defaultFont\">Successfully deleted the case category.</div>";										
				}
				unset($_SESSION['deleted_case_cat_id']);
				// Configuring the action panel against user permissions
				$action_panel = array(
										array(
												"menu_id" => 5,
												"menu_text" => "Details",
												"menu_url" => $site_config['base_url']."case-category/show/?",
												"menu_img" => " <img src=\"../../public/images/b_browse.png\" border=\"0\" alt=\"Browse\" />",
												"menu_permissions" => array(5)
											),	
										array(
												"menu_id" => 2,
												"menu_text" => "Edit",
												"menu_url" => $site_config['base_url']."case-category/edit/?",
												"menu_img" => " <img src=\"../../public/images/b_edit.png\" border=\"0\" alt=\"Edit\" />",
												"menu_permissions" => array(7)
											),
										array(
												"menu_id" => 3,
												"menu_text" => "Delete",
												"menu_url" => $site_config['base_url']."case-category/delete/?",
												"menu_img" => " <img src=\"../../public/images/b_drop.png\" border=\"0\" alt=\"Delete\" />",
												"menu_permissions" => array(8)
											)	
										);	
				// Filtering the action panel which menu to be displayed against the user permissions
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
				// Viewing all contacts in the table
				$cur_page = ((isset($_GET['page'])) && ($_GET['page'] != "") && ($_GET['page'] != 0)) ? $_GET['page'] : 1; 												
				$param_array = array('case_cat_id', 'case_cat_name', 'case_cat_description');
				// Display all pfac_records				
				if (isset($_GET['sort'])){	
				
					$imgDefault = "<a href=\"".$site_config['base_url']."case-category/view/?sort=".$_GET['sort']."&by=asc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\"><img src=\"../../public/images/s_asc.png\" border=\"0\" /></a>";			
					$imgAsc = "<a href=\"".$site_config['base_url']."case-category/view/?sort=".$_GET['sort']."&by=asc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\"><img src=\"../../public/images/s_asc.png\" border=\"0\" /></a>";			
					$imgDesc = "<a href=\"".$site_config['base_url']."case-category/view/?sort=".$_GET['sort']."&by=desc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\"><img src=\"../../public/images/s_desc.png\" border=\"0\" /></a>";								
					$img = (!isset($_GET['by'])) ? ($imgDefault) : (($_GET['by'] == "asc") ? $imgDesc : $imgAsc);

					$sort_param_array = array("case_cat" => "case_cat_name", "case_cat_desc" => "case_cat_description");
					foreach($sort_param_array as $key => $value) {
						if ($key == $_GET['sort']) {
							$sortBy = $value;
						}
					}
					$sortPath = "sort=".$_GET['sort'];					 
					if (isset($_GET['by'])) $sortPath .= "&by=".$_GET['by']."&";										 
				}
				$curPath = $_SERVER['REQUEST_URI'];
				$cur_page = ((isset($_GET['page'])) && ($_GET['page'] != "") && ($_GET['page'] != 0)) ? $_GET['page'] : 1; 																				
				// Load all data from the address book
				$all_case_categories = $caseCatModel->display_all_case_categories($param_array, $cur_page, $sortBy, ((isset($_GET['by'])) ? $_GET['by'] : ""));
				$all_case_categories_count = $caseCatModel->display_cases_cats_all_count();
				// Pagination load
				$pagination = $pagination_obj->generate_pagination($all_case_categories_count, $_SERVER['REQUEST_URI'], NO_OF_RECORDS_PER_PAGE_DEFAULT);				
				$tot_page_count = ceil($all_case_categories_count/NO_OF_RECORDS_PER_PAGE_DEFAULT);				
				// If no records found or no pages found
				$page = (isset($_GET['page'])) ? $_GET['page'] : 1;
				if (($page > $tot_page_count) || ($page == 0)){
					$invalidPage = true;	
				}
				// Unset all used variables
				unset($caseCatModel);
				unset($pagination_obj);
			break;			
		
			case "edit":			
				// All Global variables
				global $full_details;
				global $case_cats;
				global $headerDivMsg;
				global $site_config;
				global $breadcrumb;
				global $printHtml;
				$breadcrumb = "";
				// Bredcrmb to the pfa section				
				$breadcrumb .= "<div class=\"breadcrumbMessageDiv defaultFont boldText\"><a href=\"".$site_config['base_url']."\" class=\"headerLink\">Home</a> &rsaquo; Edit Case Category</div>";
				// Generate the top header menu
				$printHtml = "Main Details";
				// Object Instantiation
				$caseCatModel = new CaseCategoryModel(); 
				// Display the success message after success update
				if ($_SESSION['case_category_updated'] == "true"){
					$headerDivMsg = "<div class=\"headerMessageDivInSuccess defaultFont\">Successfully updated the case category.&nbsp;&nbsp;<a class='edtingMenusLink_in_view' href='".
							$site_config['base_url']."case-category/show/?cat_id=".trim($_GET['cat_id'])."'>View details.</a></div>";																																			
				}
				unset($_SESSION['case_category_updated']);
				// Check whether the case id is exist in the database		
				if ($caseCatModel->check_case_category_id_exist(trim($_GET['cat_id']))){
					$full_details = $caseCatModel->retrieve_full_details_per_each_case(trim($_GET['cat_id']));	
					$case_cat_id = $full_details[0]['case_cat_id'];
					// If post has been submitted
					if ("POST" == $_SERVER['REQUEST_METHOD']){
						$errors = AppModel::validate($_POST['case_category_main_reqired']);
						$_SESSION['case_category_main_reqired'] = $_POST['case_category_main_reqired'];
						$_SESSION['case_category_main_non_reqired'] = $_POST['case_category_main_non_reqired'];
						// Data Grabbing and validating for the required in contact view										
						if (isset($_POST['case_cat_main_submit'])){	
							// If errors found in the adding section
							if ($errors){
								$_SESSION['case_category_reqired_errors'] = $errors;					
								// Display the error message						
								$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Required fields cannot be blank !</div>";
							}else{		
								$case_cat_details = array(
															"case_cat_id" => trim($_GET['cat_id']),
															"case_cat_name" => $_POST['case_category_main_reqired']['case_category_name'],
															"case_cat_desc" => $_POST['case_category_main_non_reqired']['category_description']
														 );
								// Insert case details				   
								$caseCatModel->update_case_category_details($case_cat_details);
								// get the case category name for the upaded case category
								$case_cat_name = $caseCatModel->retrieve_case_category_name_by_id(trim($_GET['cat_id']));
								// Log keeping
								$log_params = array(
													"user_id" => $_SESSION['logged_user']['id'], 
													"action_desc" => "New CASE Category was saved. Name : ".$case_cat_name,
													"date_crated" => date("Y-m-d-H:i:s")
													);
								$caseCatModel->keep_track_of_activity_log_in_case_category($log_params);
								$_SESSION['case_category_updated'] = "true";
								// Unset all used data
								unset($_SESSION['case_category_reqired_errors']);						
								unset($_SESSION['case_category_main_reqired']);
								unset($_SESSION['case_category_main_non_reqired']);
								unset($caseCatModel);	
								AppController::redirect_to($site_config['base_url']."case-category/edit/?cat_id=".trim($_GET['cat_id']));												
							}
						}		
					}	
				// If wrong id	
				}else{
					AppController::redirect_to($site_config['base_url'] ."case/view/");
				}
			break;
			
			case "show":
				// All global variables		
				global $fullDetails;
				global $breadcrumb;
				global $site_config;
				global $caseCatModel;
				global $cases_owned;				
				$breadcrumb = "";
				// objects instantiation
				$caseCatModel = new CaseCategoryModel(); 
				// Bredcrmb to the pfa section
				$breadcrumb .= "<div class=\"breadcrumbMessageDiv defaultFont boldText\"><a href=\"".$site_config['base_url']
								."\" class=\"headerLink\">Home</a> &rsaquo; "."<a class=\"headerLink\" href=\"".$site_config['base_url']."case-category/view/\">All Case Categories</a> &rsaquo; Details</div>";
				// Check whether the CASE id is exist in the database		
				if ($caseCatModel->check_case_category_id_exist(trim($_GET['cat_id']))){
					$valid_access = true;
				}else{
					$valid_access = false;
				}
				if ($valid_access){
					$fullDetails = $caseCatModel->grab_full_details_for_the_single_view_in_case_category(trim($_GET['cat_id']));		
					$cases_owned = $caseCatModel->retrieve_cases_owned_given_case_category(trim($_GET['cat_id']));		
					// Log keeping
					$log_params = array(
										"user_id" => $_SESSION['logged_user']['id'], 
										"action_desc" => "Viewed details of CASE Category ID : ".trim($_GET['cat_id']),
										"date_crated" => date("Y-m-d-H:i:s")
										);
					$caseCatModel->keep_track_of_activity_log_in_case_category($log_params);
				}else{
					AppController::redirect_to($site_config['base_url'] ."case-category/view/");												
				}	
				unset($caseCatModel);
				unset($fullDetails);
			break;						

			case "delete":
				// All global variables		
				global $site_config;
				// objects instantiation
				$caseCatModel = new CaseCategoryModel(); 
				// Check whether the user id is exist in the database						
				if (!$caseCatModel->check_case_category_id_exist(trim($_GET['cat_id']))){
					$_SESSION['is_not_exist_case_cat_id'] = "true";				
					$rest_of_deletion = false;					
				}elseif (!$caseCatModel->check_case_cat_has_been_assigned_with_any_cases(trim($_GET['cat_id']))){
					$_SESSION['is_assigned_for_case'] = "true";				
					$_SESSION['temp_case_cat_id'] = trim($_GET['cat_id']);
					$rest_of_deletion = false;					
				}else{
					$rest_of_deletion = true;
				}	
				
				if ($rest_of_deletion){
					// Deleting the clients notes
					$caseCatModel->delete_selected_case_category(trim($_GET['cat_id']));
					// Log keeping
					$log_params = array(
										"user_id" => $_SESSION['logged_user']['id'], 
										"action_desc" => "New CASE Category was saved. Name : ".$case_cat_name,
										"date_crated" => date("Y-m-d-H:i:s")
										);
					$caseCatModel->keep_track_of_activity_log_in_case_category($log_params);
					$_SESSION['deleted_case_cat_id'] = "true";
				}
				// Unset all used variables	
				unset($caseCatModel);
				AppController::redirect_to($site_config['base_url'] ."case-category/view/");												
			break;
		}			
	}
}
?>