<?php

class PahroUseController extends AppController{

	/* This function will check the correct sub view provided else redirect to the home page */
	function correct_sub_view_gate_keeper($subView){
	
		if (isset($subView)){
			$modes_array = array("main", "notes", "permissions");
			if (!in_array($subView, $modes_array)){
				global $site_config;
				AppController::redirect_to($site_config['base_url']."pahro/view/");
			}else{
				$view_mode = $subView;
			}		
		}else{
			$view_mode = "";	
		}
		return $view_mode;
	} 
	/* End of the fucntion */

	/* This function will process the correct model for the given view */
	function process_the_correct_model($view, $controller){

		switch($view) {
		
			case "add":
				// All Global variables
				global $headerDivMsg;
				global $site_config;
				global $breadcrumb;
				global $user_types;
				global $user_permissions_for_staff;				
				global $user_permissions_for_vols;
				global $staff_permissions_before_html;
				global $vols_permissions_before_html;
				global $staff_permissions_after_html;
				global $vols_permissions_after_html;
				global $printHtml;
				global $all_countries;
				global $for_multiple_users_text;
				// Generate the top header menu
				$printHtml = "<span class=\"headerTopicSelected\">Main Details</span>";					
				$breadcrumb = "";
				// Bredcrmb to the pfa section
				$breadcrumb .= "<div class=\"breadcrumbMessageDiv defaultFont boldText\"><a href=\"".$site_config['base_url']."\" class=\"headerLink\">Home</a> &rsaquo; Add new user</div>";
				// Object Instantiation
				$pahro_users_model = new PahroUserModel();
				// Load all user types and permissions
				$user_types = $pahro_users_model->retrieve_all_user_types();								
				$user_permissions_for_staff = $pahro_users_model->retrieve_all_user_permissions_for_staff();				
				$user_permissions_for_vols = $pahro_users_model->retrieve_all_user_permissions_for_vols();					
				// Load the permission check boxes for each user types seperately in before post back
				$staff_permissions_before_html = $pahro_users_model->display_staff_permissions_before_postback_in_add_mode($user_permissions_for_staff, CommonFunctions::retrieve_permissions_list_for_staff());							
				$vols_permissions_before_html = $pahro_users_model->display_vols_permissions_before_postback_in_add_mode($user_permissions_for_vols, CommonFunctions::retrieve_permissions_list_for_volunteers());
				// Retieeve all counties
				$countires_params = array("country_id", "country_name");
				$all_countries = $pahro_users_model->retrieve_user_country_names($_SESSION['logged_user']['id'], $countires_params);		
				$for_multiple_users_text = "Hold CTRL to select multiple items";				
				// Display the success message after updating user details				
				if ($_SESSION['user_addedd'] == "true"){
					$headerDivMsg = "<div class=\"headerMessageDivInSuccess defaultFont\">New user has been created successfully.</div>";																																								
				}
				unset($_SESSION['user_addedd']);
				// If post has been submitted
				if ("POST" == $_SERVER['REQUEST_METHOD']){
					// Validation starts
					$errors = AppModel::validate($_POST['pahro_reqired']);
					$_SESSION['pahro_reqired'] = $_POST['pahro_reqired'];
					$_SESSION['pahro_not_reqired'] = $_POST['pahro_not_reqired'];
					$_SESSION['pahro_reqired_country'] = $_POST['pahro_reqired_country'];
					$_SESSION['pahro_user_staff_permission_reqired'] = $_POST['pahro_user_staff_permission_reqired'];
					$_SESSION['pahro_user_vols_permission_reqired'] = $_POST['pahro_user_vols_permission_reqired'];		
					// Load the permission check boxes for each user types seperately in after post back
					$staff_permissions_after_html = $pahro_users_model->display_staff_permissions_after_postback_in_add_mode($user_permissions_for_staff, $_SESSION['pahro_user_staff_permission_reqired'], CommonFunctions::retrieve_permissions_list_for_staff());
					$vols_permissions_after_html = $pahro_users_model->display_vols_permissions_after_postback_in_add_mode($user_permissions_for_vols, $_SESSION['pahro_user_vols_permission_reqired'], CommonFunctions::retrieve_permissions_list_for_volunteers());
					// Data grabbing from the post back			
					if (isset($_SESSION['pahro_user_staff_permission_reqired'])){
						foreach($_SESSION['pahro_user_staff_permission_reqired'] as $key => $value){							
							$_SESSION['user_permissions_staff'][] = $key;
						}
						unset($_SESSION['pahro_user_vols_permission_reqired']);
					}	
					if (isset($_SESSION['pahro_user_vols_permission_reqired'])){
						foreach($_SESSION['pahro_user_vols_permission_reqired'] as $key => $value){							
							$_SESSION['user_permissions_vols'][] = $key;
						}
						unset($_SESSION['pahro_user_staff_permission_reqired']);
					}	
					// If errors free in the form
					if ($errors){
						$_SESSION['pahro_reqired_errors'] = $errors;					
						// Display the error message						
						$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Required fields cannot be blank !</div>";											
					}elseif((empty($_POST['pahro_user_staff_permission_reqired'])) && (empty($_POST['pahro_user_vols_permission_reqired']))){
						unset($_SESSION['pahro_reqired_errors']);
						// Display the error message						
						$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please select the permission(s).</div>";											
					}elseif (empty($_POST['pahro_reqired']['user_type'])){
						// Display the error message						
						$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please select the user type.</div>";											
					}elseif (empty($_POST['pahro_reqired_country'])){
						// Display the error message			
						unset($_SESSION['pahro_reqired_errors']);									
						$_SESSION['pahro_reqired_errors'] = "country";
						$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please select at least one country.</div>";											
					}else{
						$fields_with_length = array($_POST['pahro_reqired']['password'] => 6);
						$errors = AppModel::check_max_field_length($fields_with_length, "password");
						if ($errors){
							// Display the error message
							$_SESSION['pahro_reqired_errors']['new_password'] = "new_password";						
							$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Password too short.</div>";																		
						// Check the username exist or not
						}elseif ($pahro_users_model->check_username_exist_in_db(trim($_SESSION['pahro_reqired']['username']))){
							if (isset($_SESSION['pahro_reqired_errors']['password'])) unset($_SESSION['pahro_reqired_errors']['password']);						
							if (isset($_SESSION['pahro_reqired_errors']['confirm_password'])) unset($_SESSION['pahro_reqired_errors']['confirm_password']);													
							// Display the error message
							$_SESSION['pahro_reqired_errors']['username'] = "username";						
							$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Username already exists.</div>";																		
						// Check the password and the confirm password are same							
						}elseif (($_POST['pahro_reqired']['password'] != $_POST['pahro_reqired']['confirm_password'])){							
							if (isset($_SESSION['pahro_reqired_errors']['username'])) unset($_SESSION['pahro_reqired_errors']['username']);
							// Display the error message
							$_SESSION['pahro_reqired_errors']['password'] = "password";						
							$_SESSION['pahro_reqired_errors']['password'] = "confirm_password";													
							$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">New password and current password not matching !</div>";																		
						// If no errors found							
						}else{	
							// Add the user to the users table						
							$pahroNewUserDetails = array(
														"first_name" => $_SESSION['pahro_reqired']['first_name'], "last_name" => $_SESSION['pahro_reqired']['last_name'],
														"email"	=> $_SESSION['pahro_not_reqired']['email'], "user_type_id" => $_SESSION['pahro_reqired']['user_type'],
														"username" => trim($_SESSION['pahro_reqired']['username']), "password" => md5(trim($_SESSION['pahro_reqired']['password'])),
														"created_at" => date('Y-m-d'), "last_login" => ""
														 );	
							$newly_inserted_pahro_user_id = $pahro_users_model->insert_new_pahro_user($pahroNewUserDetails);								
							// Insert user countries to the countries table
							$pahro_users_model->insert_new_pahro_user_countires($newly_inserted_pahro_user_id, $_SESSION['pahro_reqired_country']);
							// Add user permissions to the pagos_user_grups table regarding the newly added pahro user
							if ($_POST['pahro_reqired']['user_type'] == 1){
								foreach($_POST['pahro_user_staff_permission_reqired'] as $key => $value){
									$u_permissions[]['permission_id'] = $value;
								}
							}
							if ($_POST['pahro_reqired']['user_type'] == 2){
								foreach($_POST['pahro_user_vols_permission_reqired'] as $key => $value){
									$u_permissions[]['permission_id'] = $value;
								}
							}	
							$pahro_users_model->insert_user_permissions_to_user($newly_inserted_pahro_user_id, $u_permissions);							 						
							// Log keeping
							$log_params = array(
												"user_id" => $_SESSION['logged_user']['id'], 
												"action_desc" => "Added new PAHRO user.",
												"date_crated" => date("Y-m-d-H:i:s")
												);
							$pahro_users_model->keep_track_of_activity_log_in_pahro($log_params);
							// Unset all used data
							unset($_SESSION['pahro_reqired_errors']);						
							unset($_SESSION['pahro_reqired']);
							unset($_SESSION['pahro_not_reqired']);
							unset($_SESSION['pahro_user_group_reqired']);
							unset($_SESSION['user_permissions_staff']);
							unset($_SESSION['user_permissions_vols']);	
							unset($_SESSION['pahro_user_staff_permission_reqired']);
							unset($_SESSION['pahro_user_vols_permission_reqired']);						
							unset($_SESSION['pahro_reqired_country']);
							unset($pahro_users_model);
							$_SESSION['user_addedd'] = "true";
							AppController::redirect_to($site_config['base_url']."pahro/add/");																	
						}
					}
				}else{
					// Unset all used data
					unset($_SESSION['pahro_reqired_errors']);						
					unset($_SESSION['pahro_reqired']);
					unset($_SESSION['pahro_not_reqired']);
					unset($_SESSION['pahro_user_group_reqired']);
					unset($_SESSION['user_permissions_staff']);
					unset($_SESSION['user_permissions_vols']);	
					unset($_SESSION['pahro_user_staff_permission_reqired']);
					unset($_SESSION['pahro_user_vols_permission_reqired']);						
					unset($_SESSION['pahro_reqired_country']);
					unset($pahro_users_model);
				}
			break;
			
			case "view":
				// All Global variables
				global $site_config;
				global $all_pahro_users;
				global $all_pahro_users_count;
				global $pagination;
				global $tot_page_count;
				global $cur_page;
				global $img;
				global $breadcrumb;
				global $invalidPage;
				global $action_panel_menu;	
				global $headerDivMsg;
				global $have_permissions;
				global $no_of_permissions;			
				$sortBy = "";
				$breadcrumb = "";
				$have_permissions = false;				
				// Bredcrmb to the pfa section
				$breadcrumb .= "<div class=\"breadcrumbMessageDiv defaultFont boldText\"><a href=\"".$site_config['base_url']."\" class=\"headerLink\">Home</a> &rsaquo; Manage Users</div>";
				$action_panel_menu = array();
				// Success message after multiple selected users deletion
				if ($_SESSION['multiple_users_deleted']){
					$headerDivMsg = "<div class=\"headerMessageDivInNotice defaultFont\">Deleted selected volunteers.</div>";										
				}
				unset($_SESSION['multiple_users_deleted']);
				// If invalid user id found in the users delete section it is need to be meantioned
				if ($_SESSION['invalid_user_id_found']){
					$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Invalid volunteer id found in the multiple deletion.</div>";										
				}
				unset($_SESSION['invalid_user_id_found']);
				// Display success message on user deletion
				if ($_SESSION['user_deleted'] == "true"){
					$headerDivMsg = "<div class=\"headerMessageDivInNotice defaultFont\">Successfully Deleted the PAHRO user.</div>";					
				}
				unset($_SESSION['user_deleted']);
				
				// Display success message when user status changed
				if (isset($_SESSION['user_status'])){
					if($_SESSION['user_status'] == 1){
						$headerDivMsg = "<div class=\"headerMessageDivInNotice defaultFont\">Successfully activated the PAHRO user account.</div>";					
					}
					elseif($_SESSION['user_status'] == 0){
						$headerDivMsg = "<div class=\"headerMessageDivInNotice defaultFont\">User account successfully deactivated.</div>";					
					}
					else{
						unset($_SESSION['user_status']);
					}
				}
				unset($_SESSION['user_status']);
				
				// Display success message on user password resetting
				if ($_SESSION['password_reseted'] == "true"){
					unset($_SESSION['rand_pass']);
					$headerDivMsg = "<div class=\"headerMessageDivInNotice defaultFont\">Password reset success.</div>";					
				}
				unset($_SESSION['password_reseted']);
				// Display the error message in user deletion - condition -> user id not exist
				if ($_SESSION['is_not_exist_user_id'] == "true"){
					$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">User id not exist.</div>";
				}
				unset($_SESSION['is_not_exist_user_id']);
				// Display the error message in user deletion - condition -> user id not exist
				if ($_SESSION['is_assinged_currently_logged_user'] == "true"){
					$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Cannot delete this user : (Currently logged user).</div>";
				}
				unset($_SESSION['is_assinged_currently_logged_user']);
				// Display the error message in user deletion - condition -> user id not exist
				if ($_SESSION['is_responsible_for_case'] == "true"){
					$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Cannot delete this user : (Responsible for a Case).</div>";
				}
				unset($_SESSION['is_responsible_for_case']);
				// Display the error message in user deletion - condition -> user id not exist
				if ($_SESSION['is_assinged_to_a_case'] == "true"){
					$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Cannot delete this user : (working on a Case).</div>";
				}
				unset($_SESSION['is_assinged_to_a_case']);
				// Configuring the action panel against user permissions
				$action_panel = array(
										array(
												"menu_id" => 4,
												"menu_text" => "Drop",
												"menu_url" => $site_config['base_url']."pahro/drop/?",
												"menu_img" => " <img src=\"../../public/images/b_drop.png\" border=\"0\" alt=\"Drop\" />",
												"menu_permissions" => array(21)
											),	
										array(
												"menu_id" => 1,
												"menu_text" => "Add / Edit Note",
												"menu_url" => $site_config['base_url']."pahro/edit/?mode=notes&",
												"menu_img" => "<img src=\"../../public/images/notepadicon.png\" border=\"0\" alt=\"New / Edit Note\" />",
												"menu_permissions" => array(65)
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
				// Object Instantiation
				$pahro_users_model = new PahroUserModel();
				$pagination_obj = new Pagination();				
				// Viewing all contacts in the table
				$cur_page = ((isset($_GET['page'])) && ($_GET['page'] != "") && ($_GET['page'] != 0)) ? $_GET['page'] : 1; 												
				$param_array = array('id', 'status', 'user_type', 'username', 'first_name', 'last_name', 'email', 'created_at', 'last_login', 'country_id', 'country_name');
				// Display all pfac_records	with their sorting			
				if (isset($_GET['sort'])){	
				
					$imgDefault = "<a href=\"".$site_config['base_url']."pahro/view/?sort=".$_GET['sort']."&by=asc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\"><img src=\"../../public/images/s_asc.png\" border=\"0\" /></a>";			
					$imgAsc = "<a href=\"".$site_config['base_url']."pahro/view/?sort=".$_GET['sort']."&by=asc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\"><img src=\"../../public/images/s_asc.png\" border=\"0\" /></a>";			
					$imgDesc = "<a href=\"".$site_config['base_url']."pahro/view/?sort=".$_GET['sort']."&by=desc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\"><img src=\"../../public/images/s_desc.png\" border=\"0\" /></a>";								
					$img = (!isset($_GET['by'])) ? ($imgDefault) : (($_GET['by'] == "asc") ? $imgDesc : $imgAsc);

					$sort_param_array = array(
											  "u_type" => "user_type", "u_name" => "username", "f_name" => "first_name", "l_name" => "last_name", "email" => "email", 
											  "created_at" => "created_at", "last_login" => "last_login", "own_country" => "country_name"
											 );
					foreach($sort_param_array as $key => $value) {
						if ($key == $_GET['sort']) {
							$sortBy = $value;
						}
					}
				}
				// If the post value has been found
				if ("POST" == $_SERVER['REQUEST_METHOD']){

					// If the notes filtering button has been clicked
					if (isset($_POST['countries_filter'])){
						// If notes categories are empty then display the error message
						if (empty($_POST['filter_by_countries_required'])){
							if (isset($_SESSION['filter_by_countries_required'])) unset($_SESSION['filter_by_countries_required']);
							$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please select at least one country to filter.</div>";										
						}else{
							$filtering = true;
							$_SESSION['filter_by_countries_required'] = $_POST['filter_by_countries_required'];							
						}
					}
					// If the reset button has been clicked
					if (isset($_POST['countries_filter_reset'])){
						$filtering = false;
						unset($_SESSION['started_filtering']);
						unset($_SESSION['filter_by_countries_required']);		
						AppController::redirect_to($site_config['base_url'] ."pahro/view/");												
					}
				}
				if (((isset($_SESSION['started_filtering'])) && ($_SESSION['started_filtering'])) || (isset($_SESSION['filter_by_countries_required']))){
					$filtering = true;
				}	
				$filtiring_country = (isset($_SESSION['filter_by_countries_required'])) ? $_SESSION['filter_by_countries_required'][0] : $_SESSION['curr_country']['country_code'];
				// Display all pfac_records	without their sorting							
				$all_pahro_users = $pahro_users_model->display_all_pahro_users($param_array, $cur_page, $sortBy, (isset($_GET['by'])) ? $_GET['by'] : "", $_SESSION['logged_user']['countries'], 
																			   $filtering, $_SESSION['filter_by_countries_required'], $filtiring_country);
				$all_pahro_users_count = $pahro_users_model->display_count_on_all_pahros($_SESSION['logged_user']['countries'], $param_array, 
																			   $filtering, $_SESSION['filter_by_countries_required'], $filtiring_country);
				
				// Pagination load
				$pagination = $pagination_obj->generate_pagination($all_pahro_users_count, $_SERVER['REQUEST_URI'], NO_OF_RECORDS_PER_PAGE_DEFAULT);				
				$tot_page_count = ceil($all_pahro_users_count/NO_OF_RECORDS_PER_PAGE_DEFAULT);				
				// If no records found or no pages found
				$page = (isset($_GET['page'])) ? $_GET['page'] : 1;
				if (($page > $tot_page_count) || ($page == 0)){
					$invalidPage = true;	
				}
				// Unset all used variables
				unset($pahro_users_model);
				unset($pagination_obj);
			break;			
		
			case "edit":
				// All Global variables
				global $printHtml;
				global $full_details;
				global $headerDivMsg;
				global $site_config;
				global $breadcrumb;
				global $owned_permissions_ids;
				global $permission_names;
				global $user_types;
				global $user_permissions_for_staff;
				global $user_permissions_for_vols;
				global $user_type;
				global $user_type_name;
				global $rand_pass;
				global $staff_permissions_before_html;
				global $vols_permissions_before_html;				
				global $staff_permissions_after_html;
				global $vols_permissions_after_html;
				global $all_notes_to_this_client;
				global $all_notes_count_to_this_client;	
				global $pahro_id;
				global $note_full_details;
				global $tot_page_count;
				global $pagination;
				global $invalidPage;
				global $img;
				global $all_countries;
				global $pahro_users_model;
				global $user_countires;	
				global $for_multiple_users_text;		
				global $user_countires_names;
				global $need_to_hide_select_menu;
				$need_to_hide_select_menu = false;				
				$breadcrumb = "";
				$mode = "";
				// Object Instantiation
				$pahro_users_model = new PahroUserModel();
				$commonFuncs = new CommonFunctions();
				// Check the user owned country is equal to the currenctly selected country
				if ($pahro_users_model->check_pahro_country_with_curr_selected_country($_SESSION['curr_country']['country_code'], trim($_GET['pahro_id']))){
					// Load all user groups
					$user_types = $pahro_users_model->retrieve_all_user_types();								
					$countires_params = array("country_id", "country_name");
					// Grab the countires count for the currently logged user
					$logged_users_country_count = count($pahro_users_model->retrieve_user_country_names($_SESSION['logged_user']['id'], $countires_params));
					$for_multiple_users_text = "Hold CTRL to select multiple items";				
					// Load viewed user owned countries
					if ($pahro_users_model->check_pahro_id_exist(trim($_GET['pahro_id']))){																											
						$user_countires_names  = $pahro_users_model->retrieve_user_countries_by_their_names(trim($_GET['pahro_id']));					
					}
					if ($_SESSION['logged_user']['id'] == trim($_GET['pahro_id'])){
						$all_countries = $pahro_users_model->retrieve_user_country_names($_SESSION['logged_user']['id'], $countires_params);		
						// Load only the countries this viewed user not having	
						foreach($all_countries as $each_country){
							if (!in_array($each_country['country_name'], $user_countires_names)){
								$new_country_list[] = $each_country;
							}
						}
						$all_countries = $new_country_list;
						// If the user all countries have been shown in the label control and nothing to show in the smultiple select menu then we have to hide the above menu
						if (count($all_countries) == 0){
							$need_to_hide_select_menu = true;
						}
					}else{
						if ($logged_users_country_count > 1){
							$all_countries = $pahro_users_model->retrieve_all_countires_for_users($countires_params);												
							// Load only the countries this viewed user not having	
							foreach($all_countries as $each_country){
								if (!in_array($each_country['country_name'], $user_countires_names)){
									$new_country_list[] = $each_country;
								}
							}
							$all_countries = $new_country_list;
							// If the user all countries have been shown in the label control and nothing to show in the smultiple select menu then we have to hide the above menu
							if (count($all_countries) == 0){
								$need_to_hide_select_menu = true;
							}
						}else{
							$all_countries = $pahro_users_model->retrieve_user_country_names(trim($_GET['pahro_id']), $countires_params);							
							// Load only the countries this viewed user not having	
							foreach($all_countries as $each_country){
								if (!in_array($each_country['country_name'], $user_countires_names)){
									$new_country_list[] = $each_country;
								}
							}
							$all_countries = $new_country_list;
							// If the user all countries have been shown in the label control and nothing to show in the smultiple select menu then we have to hide the above menu
							if (count($all_countries) == 0){
								$need_to_hide_select_menu = true;
							}
						}	
					}	
					// Load all user permissions
					$user_permissions_for_staff = $pahro_users_model->retrieve_all_user_permissions_for_staff();				
					$user_permissions_for_vols = $pahro_users_model->retrieve_all_user_permissions_for_vols();								
					if ($pahro_users_model->check_pahro_id_exist(trim($_GET['pahro_id']))){																							
						$full_details = $pahro_users_model->retrieve_full_details_per_each_pahro_user(trim($_GET['pahro_id']));				
						$user_type = $full_details[0]['user_type_id'];
						if ($user_type == 2){
							$unavailable_permissions = array(4, 12, 16);
							foreach($user_permissions_for_vols as $each_permission_grp){
							
								if (
								   (!in_array($each_permission_grp['permission_id'], $unavailable_permissions)) || 
								   (!in_array($each_permission_grp['permission_id'], $unavailable_permissions)) || 
								   (!in_array($each_permission_grp['permission_id'], $unavailable_permissions))
								   ){
									$new_vol_permissions[] = $each_permission_grp;
								}
							}
							$user_permissions_for_vols = $new_vol_permissions;
						}
					}	
					// Grab the user owned permissions id and names
					$owned_permissions_ids = $pahro_users_model->grab_owned_user_permissions(trim($_GET['pahro_id']));
					$permission_names = $pahro_users_model->grab_owned_user_permission_names(trim($_GET['pahro_id']));
					// Load the html conetent which displays the permissions check boxes before post back
					$staff_permissions_before_html = $pahro_users_model->display_staff_permissions_before_postback_in_edit_mode($user_permissions_for_staff, $owned_permissions_ids, CommonFunctions::retrieve_permissions_list_for_staff());
					$vols_permissions_before_html = $pahro_users_model->display_vols_permissions_before_postback_in_edit_mode($user_permissions_for_vols, $owned_permissions_ids, CommonFunctions::retrieve_permissions_list_for_volunteers());
					// Genrate random password
					if (!isset($_SESSION['rand_pass'])) $_SESSION['rand_pass'] = CommonFunctions::createRandomPassword();
					// Check whether the address book id is exist in the database						
					if ($pahro_users_model->check_pahro_id_exist(trim($_GET['pahro_id']))){															
						// Load the pahro user details for the given 
						$full_details = $pahro_users_model->retrieve_full_details_per_each_pahro_user(trim($_GET['pahro_id']));
						$user_countires = $pahro_users_model->retrieve_user_countries($full_details[0]['id']);
						$pahro_id = $full_details[0]['id'];
						$user_type = $full_details[0]['user_type_id'];
						$user_type_name = $full_details[0]['user_type'];
						// Display the success message after updating user details
						if ($_SESSION['user_details_updated'] == "true"){
							$headerDivMsg = "<div class=\"headerMessageDivInSuccess defaultFont\">User details was updated successfully.&nbsp;&nbsp;<a class='edtingMenusLink_in_view' href='".
									$site_config['base_url']."pahro/show/?pahro_id=".trim($_GET['pahro_id'])."'>View details.</a></div>";																																			
						}
						unset($_SESSION['user_details_updated']);
						// Display the notes section data
						if ((isset($_GET['mode'])) && ($_GET['mode'] == "notes")){
							// Display the success message on new note creation
							if ($_SESSION['new_note_created'] == "true"){				
								$headerDivMsg = "<div class=\"headerMessageDivInSuccess defaultFont\">Note has been created successfully.</div>";																																								
							}
							unset($_SESSION['new_note_created']);
							// Display the success message on note update
							if ($_SESSION['notes_section_got_updated'] == "true"){				
								$headerDivMsg = "<div class=\"headerMessageDivInSuccess defaultFont\">Note has been updated successfully.</div>";																																								
							}
							unset($_SESSION['notes_section_got_updated']);
							// Display the success message on note update
							if ($_SESSION['notes_got_deleted'] == "true"){				
								$headerDivMsg = "<div class=\"headerMessageDivInNotice defaultFont\">Note has been deleted successfully.</div>";																																								
							}
							unset($_SESSION['notes_got_deleted']);
							// If sorting has been done
							if (isset($_GET['sort'])){	
							
								$imgDefault = "<a href=\"".$site_config['base_url']."pahro/edit/?mode=notes&pahro_id=".$pahro_id.(isset($_GET['page']) ? "&page=".$_GET['page'] : "").((isset($_GET['notes_page'])) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."&sort=".$_GET['sort']."&by=asc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\"><img src=\"../../public/images/s_asc.png\" border=\"0\" /></a>";			
								$imgAsc = "<a href=\"".$site_config['base_url']."pahro/edit/?mode=notes&pahro_id=".$pahro_id.(isset($_GET['page']) ? "&page=".$_GET['page'] : "").((isset($_GET['notes_page'])) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."&sort=".$_GET['sort']."&by=asc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\"><img src=\"../../public/images/s_asc.png\" border=\"0\" /></a>";			
								$imgDesc = "<a href=\"".$site_config['base_url']."pahro/edit/?mode=notes&pahro_id=".$pahro_id.(isset($_GET['page']) ? "&page=".$_GET['page'] : "").((isset($_GET['notes_page'])) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."&sort=".$_GET['sort']."&by=desc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\"><img src=\"../../public/images/s_desc.png\" border=\"0\" /></a>";								
								$img = (!isset($_GET['by'])) ? ($imgDefault) : (($_GET['by'] == "asc") ? $imgDesc : $imgAsc);
			
								$sort_param_array = array("desc" => "note", "mod_date" => "date_modified");
								foreach($sort_param_array as $key => $value) {
									if ($key == $_GET['sort']) {
										$sortBy = $value;
									}
								}
							}				
							// If the notes filtering button has been clicked
							if (isset($_POST['notes_filter'])){
								// If notes categories are empty then display the error message
								if (empty($_POST['pahro_note_categories_required'])){
									$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please select at least one category to filter.</div>";										
								}else{
									$filtering = true;
								}
							}
							// Load last inserted notes by last inserted user
							$cur_page = ((isset($_GET['notes_page'])) && ($_GET['notes_page'] != "") && ($_GET['notes_page'] != 0)) ? $_GET['notes_page'] : 1; 		
							$notes_array = array('note_id', 'note_owner_id', 'note', 'date_modified', 'added_date', 'added_by', 'modified_by', 'username');					
							$all_notes_to_this_client = $pahro_users_model->retrieve_all_notes_owned_by_this_client($pahro_id, $notes_array, strtoupper($user_type_name), $cur_page, $sortBy, ((isset($_GET['by'])) ? $_GET['by'] : ""), $filtering, $_POST['pahro_note_categories_required']);						
							$all_notes_count_to_this_client = $pahro_users_model->retrieve_all_notes_count_owned_by_this_client($pahro_id, $notes_array, strtoupper($user_type_name), $filtering, $_POST['pahro_note_categories_required']);						
							// Load the pagination
							$pagination_obj = new Pagination();
							$pagination = $pagination_obj->generate_pagination($all_notes_count_to_this_client, $_SERVER['REQUEST_URI'], NO_OF_RECORDS_PER_PAGE_DEFAULT);				
							$tot_page_count = ceil($all_notes_count_to_this_client/NO_OF_RECORDS_PER_PAGE_DEFAULT);				
							// If the page not exist
							if ((isset($_GET['notes_page'])) && ($_GET['notes_page'] > $tot_page_count)){
								$invalidPage = true;	
								if ($all_notes_count_to_this_client == 0){
									$invalidPage = false;	
								}else{					
									AppController::wait_then_redirect_to(1, $site_config['base_url']."pahro/view/");
								}	
							}
							// Retrieve notes description and other details
							if ((isset($_GET['opt'])) && (($_GET['opt'] == "edit") || ($_GET['opt'] == "view"))){
								if ($pahro_users_model->check_note_id_exist(trim($_GET['note_id']))){						
									if ($pahro_users_model->check_note_id_owned_by_the_correct_client(trim($_GET['pahro_id']), trim($_GET['note_id']))){								
										$note_param = array("note_id","note", "added_date", "username", "date_modified", "modified_by");
										$note_full_details = $pahro_users_model->retrieve_full_details_of_selected_note(trim($_GET['note_id']), $note_param);				
									}else{
										AppController::redirect_to($site_config['base_url'] ."pahro/view/");									
									}	
								}else{
									AppController::redirect_to($site_config['base_url'] ."pahro/view/");	
								}	
							}	
							// Delete the selected note	
							if ((isset($_GET['opt'])) && ($_GET['opt'] == "drop")){
								$pahro_users_model->delete_selected_note(trim($_GET['note_id']), $pahro_id);
								// Delete notes category relation as well regarding the note deletion
								$pahro_users_model->remove_previous_notes_categories_relation(trim($_GET['note_id']));							
								// Log keeping
								$log_params = array(
													"user_id" => $_SESSION['logged_user']['id'], 
													"action_desc" => "Deleted the last inserted note of User id {$pahro_id}",
													"date_crated" => date("Y-m-d-H:i:s")
													);
								$pahro_users_model->keep_track_of_activity_log_in_pahro($log_params);
								$_SESSION['notes_got_deleted'] = "true";
								AppController::redirect_to($site_config['base_url']."pahro/edit/?mode=notes&pahro_id=".$pahro_id."&notes_page=1");
							}								
						}
						// Bredcrmb to the pfa section				
						$breadcrumb .= "<div class=\"breadcrumbMessageDiv defaultFont boldText\"><a href=\"".$site_config['base_url']
										."\" class=\"headerLink\">Home</a> ";
						if (isset($_GET['opt'])){
							$breadcrumb .= "&rsaquo; <a class=\"headerLink\" href=\"".$site_config['base_url']."pahro/view/\">All Users</a> &rsaquo; <a class=\"headerLink\" href=\"".
												$site_config['base_url']."pahro/edit/?pahro_id=".$pahro_id.((isset($_GET['page'])) ? "&page=".$_GET['page'] : "")."\">Edit User</a> &rsaquo; ".
												"<a class=\"headerLink\" href=\"".$site_config['base_url']."pahro/edit/?mode=notes&pahro_id=".$pahro_id.((isset($_GET['page'])) ? "&page=".$_GET['page'] : "").((isset($_GET['notes_page'])) ? "&notes_page=".$_GET['notes_page'] : "notes_page=1")."\">Your all notes</a>";
							if ($_GET['opt'] == "view"){
								$breadcrumb	.= " &rsaquo;&nbsp;View single note";				
							}else{
								$breadcrumb	.= " &rsaquo;&nbsp;Edit single note";				
							}										
						}else{
							$breadcrumb .= "&rsaquo; <a class=\"headerLink\" href=\"".$site_config['base_url']."pahro/view/\">All Users</a> &rsaquo; ".(((isset($_GET['mode']) && $_GET['mode'] == "notes")) ? "Add / Edit Notes" : "Edit User");					
						}
						$breadcrumb .= "</div>";
						// Generate the top header menu
						$printHtml = "";
						$printHtml .= (!isset($_GET['mode'])) ? "<span class=\"headerTopicSelected\">Main Details</span>" : "<span><a href=\"?pahro_id=".trim($_GET['pahro_id']).((isset($_GET['page'])) ? "&page=".$_GET['page'] : "")."\">Main Details</a></span>";					
						$printHtml .= "&nbsp;&nbsp;<span class=\"ar\">&rsaquo;</span>&nbsp;&nbsp;";
						$printHtml .= ((isset($_GET['mode'])) && ($_GET['mode'] == "notes")) ? "<span class=\"headerTopicSelected\">Notes</span>" : "<span><a href=\"?mode=notes&pahro_id=".trim($_GET['pahro_id']).((isset($_GET['page'])) ? "&page=".$_GET['page'] : "").((isset($_GET['notes_page'])) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\">Notes</a></span>";					
						// Start to validate and other main functions				
						if ("POST" == $_SERVER['REQUEST_METHOD']){
							// If the add / save button has been clicked
							if (isset($_POST['pahro_submit'])){
								// Data validations						
								$errors = AppModel::validate($_POST['pahro_reqired']);				
								$_SESSION['pahro_reqired'] = $_POST['pahro_reqired'];
								$_SESSION['pahro_not_reqired'] = $_POST['pahro_not_reqired'];
								//$_SESSION['pahro_reqired_country'] = $_POST['pahro_reqired_country'];
								$_SESSION['pahro_user_staff_permission_reqired'] = $_POST['pahro_user_staff_permission_reqired'];
								$_SESSION['pahro_user_vols_permission_reqired'] = $_POST['pahro_user_vols_permission_reqired'];					
								// Load the html conetent which displays the permissions check boxes after post back
								$staff_permissions_after_html = $pahro_users_model->display_staff_permissions_after_postback_in_edit_mode($user_permissions_for_staff, $_SESSION['pahro_user_staff_permission_reqired'], CommonFunctions::retrieve_permissions_list_for_staff());
								$vols_permissions_after_html = $pahro_users_model->display_vols_permissions_after_postback_in_edit_mode($user_permissions_for_vols, $_SESSION['pahro_user_vols_permission_reqired'], CommonFunctions::retrieve_permissions_list_for_volunteers());
								// Data Grabbing from the post back
								if (isset($_SESSION['pahro_user_staff_permission_reqired'])){
									foreach($_SESSION['pahro_user_staff_permission_reqired'] as $key => $value){							
										$_SESSION['user_permissions_staff'][] = $key;
									}
									unset($_SESSION['pahro_user_vols_permission_reqired']);
								}	
								if (isset($_SESSION['pahro_user_vols_permission_reqired'])){
									foreach($_SESSION['pahro_user_vols_permission_reqired'] as $key => $value){							
										$_SESSION['user_permissions_vols'][] = $key;
									}
									unset($_SESSION['pahro_user_staff_permission_reqired']);
								}	
								// If the country has been submitted
								if (isset($_POST['pahro_non_reqired_country'])){
									$_SESSION['pahro_non_reqired_country'] = $_POST['pahro_non_reqired_country'];
								}
								// If not having errors proceed with the databsse saving					
								if ($errors){
									$_SESSION['pahro_reqired_errors'] = $errors;					
									// Display the error message						
									$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Required fields cannot be blank !</div>";											
								}elseif((empty($_POST['pahro_user_staff_permission_reqired'])) && (empty($_POST['pahro_user_vols_permission_reqired']))){
									unset($_SESSION['pahro_reqired_errors']);
									// Display the error message						
									$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please select the permissions(s).</div>";											
								//}elseif (empty($_POST['pahro_reqired_country'])){
									// Display the error message			
									//unset($_SESSION['pahro_reqired_errors']);									
									//$_SESSION['pahro_reqired_errors'] = "country";
									//$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please select at least one country.</div>";											
								}else{
									if (
									   ($_POST['pahro_not_reqired_spec']['new_password'] != "") ||
									   ($_POST['pahro_not_reqired_spec']['password'] != "") ||
									   ($_POST['pahro_not_reqired_spec']['confirm_password'] != "")
									   ){	
											// If the new password is being provided : then should be validated
											$errors = AppModel::validate($_POST['pahro_not_reqired_spec']);
											$_SESSION['pahro_not_reqired_spec'] = $_POST['pahro_not_reqired_spec'];
											if ($errors){
												// Display the error message
												$_SESSION['pahro_reqired_errors'] = $errors;						
												$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Current passowrd, confirmation of password and new passowrds required.</div>";																								
												$need_to_be_submit = "false";
											// If passwords are too short	
											}else{
												$fields_with_length = array($_POST['pahro_not_reqired_spec']['new_password'] => 6);
												$errors = AppModel::check_max_field_length($fields_with_length, "new_password");
												if ($errors){
													// Display the error message
													$_SESSION['pahro_reqired_errors']['new_password'] = "new_password";						
													$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Password too short.</div>";																		
													$need_to_be_submit = "false";										
												}elseif (!$pahro_users_model->check_previous_password_correct(trim($full_details[0]['username']), trim(md5($_SESSION['pahro_not_reqired_spec']['password'])))){
												// Check the previous password is correct																
													if (isset($_SESSION['pahro_reqired_errors']['new_password'])) unset($_SESSION['pahro_reqired_errors']['new_password']);								
													if (isset($_SESSION['pahro_reqired_errors']['confirm_password'])) unset($_SESSION['pahro_reqired_errors']['confirm_password']);															
													// Display the error message
													$_SESSION['pahro_reqired_errors']['password'] = "password";						
													$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Current Password is not correct !</div>";																								
													$need_to_be_submit = "false";																				
												// Check the password and the confirm password are same														
												}elseif ((trim($_POST['pahro_not_reqired_spec']['new_password']) != trim($_POST['pahro_not_reqired_spec']['confirm_password']))){	
													if (isset($_SESSION['pahro_reqired_errors']['password'])) unset($_SESSION['pahro_reqired_errors']['password']);								
													// Display the error message
													$_SESSION['pahro_reqired_errors']['new_password'] = "new_password";						
													$_SESSION['pahro_reqired_errors']['confirm_password'] = "confirm_password";													
													$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">New password and current password not matching !</div>";																		
													$need_to_be_submit = "false";																				
												}else{
													$need_to_be_submit = "true";
												}
											}											
										}else{
											$need_to_be_submit = "true";
										}
										if ($need_to_be_submit == "true"){		
											// Then update the users table
											$user_details_params = array(
																		"first_name" => $_SESSION['pahro_reqired']['first_name'], "last_name" => $_SESSION['pahro_reqired']['last_name'],														
																		"password" => ($_POST['pahro_not_reqired_spec']['new_password'] != "") ? trim(md5($_SESSION['pahro_not_reqired_spec']['new_password'])) : "", 
																		"email" => $_SESSION['pahro_not_reqired']['email']//, "country_id" => $_SESSION['pahro_reqired']['country']
																		);
											// First clear the user countries and add new countries owned by the user
											$pahro_users_model->clear_user_countries_owned($full_details[0]['id']);
											// Insert user countries to the countries table
											if (!isset($_POST['pahro_non_reqired_country'])){
												$user_countires_to_save = $user_countires;
											}else{
												$user_countires_to_save = array_merge($_SESSION['pahro_non_reqired_country'], $user_countires);										
											}
											$pahro_users_model->insert_new_pahro_user_countires($full_details[0]['id'], $user_countires_to_save);
											// First clear the user previous user group details
											$pahro_users_model->clear_user_previous_permission_details($full_details[0]['id']);
											// Add user permissions to the pagos_user_grups table regarding the newly added pahro user
											if (isset($_SESSION['pahro_user_staff_permission_reqired'])){
												foreach($_SESSION['pahro_user_staff_permission_reqired'] as $key => $value){
													$u_permissions[]['permission_id'] = $value;
												}
											}	
											if (isset($_SESSION['pahro_user_vols_permission_reqired'])){
												foreach($_SESSION['pahro_user_vols_permission_reqired'] as $key => $value){
													$u_permissions[]['permission_id'] = $value;
												}
											}	
											$pahro_users_model->insert_user_permissions_to_user($full_details[0]['id'], $u_permissions);							 						
											// Update user other details								
											$pahro_users_model->update_user_details($full_details[0]['id'], $user_details_params);
											// Log keeping
											$log_params = array(
																"user_id" => $_SESSION['logged_user']['id'], 
																"action_desc" => "Updated system user details of user id {$full_details[0]['id']}",
																"date_crated" => date("Y-m-d-H:i:s")
																);
											$pahro_users_model->keep_track_of_activity_log_in_pahro($log_params);
											// Unset all used variables	
											unset($_SESSION['pahro_reqired']);						
											unset($_SESSION['pahro_not_reqired']);
											unset($_SESSION['pahro_reqired_errors']);
											unset($_SESSION['pahro_not_reqired_spec']);
											unset($_SESSION['pahro_non_reqired_country']);
											unset($full_details);
											unset($pahro_users_model);	
											$_SESSION['user_details_updated'] = "true";
											AppController::redirect_to($site_config['base_url']."pahro/edit/?pahro_id=".trim($_GET['pahro_id']).((isset($_GET['page'])) ? "&page=".$_GET['page'] : ""));									
										}	
									}	
								}						
								// If notes form has been submitted
								if (isset($_POST['pahro_notes_submit'])){
									// If no value has been submitted regarding the notes section						
									if (empty($_POST['pahro_note_categories_required'])){
										$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please select at least one category.</div>";		
									// If no value has been submitted regarding the notes section
									}elseif (empty($_POST['pahro_note_required']['note_text'])){
										$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please fill the note text box.</div>";		
									// If both values has been submitted	
									}else{
										$_SESSION['pahro_note_required']['note_text'] = $_POST['pahro_note_required']['note_text'];							
										$notes_inputting_array = array(
																		"note_owner_id" => $pahro_id,
																		"note_owner_type" => strtoupper($user_type_name),
																		"note_text" => $_SESSION['pahro_note_required']['note_text'],
																		"added_date" => date("Y-m-d-H:i:s"),
																		"added_by" => $_SESSION['logged_user']['id']														
																		);
										$last_inserted_note_id = $pahro_users_model->insert_new_note($notes_inputting_array);
										// Inserting the notes related categories
										$notes_categories_params = array(
																			"note_id" => $last_inserted_note_id,
																			"notes_categories" => $_POST['pahro_note_categories_required'],
																			"note_owner_section" => "STAFF"
																		);	
										$pahro_users_model->insert_notes_categories($notes_categories_params);
										$_SESSION['new_note_has_created'] = "true";		
										// Log keeping
										$log_params = array(
															"user_id" => $_SESSION['logged_user']['id'], 
															"action_desc" => "Created a new note to User id {$pahro_id}",
															"date_crated" => date("Y-m-d-H:i:s")
															);
										$pahro_users_model->keep_track_of_activity_log_in_pahro($log_params);
										$notes_array = array('note_id', 'note_owner_id', 'note', 'date_modified', 'added_by');					
										$all_notes_to_this_client = $pahro_users_model->retrieve_all_notes_owned_by_this_client($pahro_id, $notes_array, strtoupper($user_type_name));																																																	
										unset($_POST);									
										unset($_SESSION['pahro_note_required']['note_text']);
										$_SESSION['new_note_created'] = "true";									
										AppController::redirect_to($site_config['base_url'] ."pahro/edit/?mode=notes&pahro_id=".$pahro_id.((isset($_GET['page'])) ? "&page=".$_GET['page'] : "").((isset($_GET['notes_page'])) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1"));									
									}
									$notes_array = array('note_id', 'note_owner_id', 'note', 'date_modified', 'added_by', 'added_date', 'modified_by');					
									$all_notes_to_this_client = $pahro_users_model->retrieve_all_notes_owned_by_this_client($pahro_id, $notes_array, strtoupper($user_type_name));
								}
								// If notes form has been submitted in edit note section
								if (isset($_POST['pahro_notes_update_submit'])){
									// Check the note id exist in the db
									if ($pahro_users_model->check_note_id_exist(trim($_GET['note_id'])) != ""){
										// Check the note is owned by him self
										if ($pahro_users_model->check_note_id_owned_by_the_correct_client($pahro_id, trim($_GET['note_id'])) != ""){								
											// If no value has been submitted regarding the notes section						
											if (empty($_POST['pahro_note_categories_required'])){
												$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please select at least one category.</div>";		
											// If no value has been submitted regarding the notes section
											}elseif (empty($_POST['pahro_note_required']['note_text'])){
												$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please fill the note text box.</div>";		
											// If both values has been submitted	
											}else{
												$_SESSION['pahro_note_required']['note_text'] = $_POST['pahro_note_required']['note_text'];							
												$notes_inputting_array = array(
																				"note_owner_id" => $pahro_id,
																				"note_owner_type" => strtoupper($user_type_name),
																				"note_text" => $_SESSION['pahro_note_required']['note_text'],
																				"date_modified" => date("Y-m-d-H:i:s"),
																				"modified_by" => $_SESSION['logged_user']['id']														
																				);
												$pahro_users_model->update_the_exsiting_note($notes_inputting_array, trim($_GET['note_id']), strtoupper($user_type_name));
												// Remove previous notes categories relation
												$pahro_users_model->remove_previous_notes_categories_relation(trim($_GET['note_id']));										
												// Inserting the notes related categories
												$notes_categories_params = array(
																					"note_id" => trim($_GET['note_id']),
																					"notes_categories" => $_POST['pahro_note_categories_required'],
																					"note_owner_section" => "STAFF"
																				);	
												$pahro_users_model->insert_notes_categories($notes_categories_params);
												// Log keeping
												$log_params = array(
																	"user_id" => $_SESSION['logged_user']['id'], 
																	"action_desc" => "Update one of existing notes of User id {$pahro_id}",
																	"date_crated" => date("Y-m-d-H:i:s")
																	);
												$pahro_users_model->keep_track_of_activity_log_in_pahro($log_params);
												unset($_POST);
												unset($_SESSION['pahro_note_required']['note_text']);
												$_SESSION['notes_section_got_updated'] = "true";
												AppController::redirect_to($site_config['base_url']."pahro/edit/?mode=notes&pahro_id=".$pahro_id.((isset($_GET['page'])) ? "&page=".$_GET['page'] : "").((isset($_GET['notes_page'])) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1"));												
											}
										// If note id is not owned by him self																				
										}else{
											unset($_SESSION['pahro_note_required']['note_text']);
											AppController::redirect_to($site_config['base_url'] ."pahro/view/");	
										}
									// If note not exist
									}else{
										unset($_SESSION['pahro_note_required']['note_text']);
										AppController::redirect_to($site_config['base_url'] ."pahro/view/");	
									}
								}		
								// End of the notes section submission
							}else{
								unset($_SESSION['pahro_reqired']);						
								unset($_SESSION['pahro_not_reqired']);
								unset($_SESSION['pahro_reqired_errors']);
								unset($_SESSION['pahro_not_reqired_spec']);
								unset($_SESSION['pahro_reqired_country']);
								// If the user wants to reset the password
								if (isset($_GET['action']) && ($_GET['action'] == "reset")){
									$pahro_users_model->reset_password(trim($_GET['pahro_id']), $_SESSION['rand_pass']);
									$_SESSION['password_reseted'] = "true";
									AppController::redirect_to($site_config['base_url']."pahro/view/".((isset($_GET['page'])) ? "?page=".$_GET['page'] : "?page=1"));
								}
							}
					// If wrong id	
					}else{
						AppController::redirect_to($site_config['base_url'] ."pahro/view/");																
					}
				}else{
					AppController::redirect_to($site_config['base_url'] ."pahro/view/");																					
				}	
			break;
			
			case "delete":
				// All global variables		
				global $site_config;		
				// objects instantiation
				$pahro_users_model = new PahroUserModel();
				// Check whether the user id is exist in the database						
				if ($pahro_users_model->check_pahro_id_exist(trim($_GET['pahro_id'])) == ""){
					$_SESSION['is_not_exist_user_id'] = "true";				
					AppController::redirect_to($site_config['base_url'] ."pahro/view/");			
				// Check whether the user id is equal to logged user
				}elseif (trim($_GET['pahro_id']) == $_SESSION['logged_user']['id']){
					$_SESSION['is_assinged_currently_logged_user'] = "true";				
					AppController::redirect_to($site_config['base_url'] ."pahro/view/");																										
				// Check whether the selected user have been responsible for any case
				}elseif ($pahro_users_model->is_responsible_for_case(trim($_GET['pahro_id']))){
					$_SESSION['is_responsible_for_case'] = "true";
					AppController::redirect_to($site_config['base_url'] ."pahro/view/");						
				// Check whether the selected user have been assigned in to a case
				}else{					

					if ($pahro_users_model->is_being_assgined_to_any_case(trim($_GET['pahro_id']))){					
						$pahro_users_model->unlink_assigned_cases_prior_to_delete(trim($_GET['pahro_id']));
					}	
				
					// Clear the user owned countries
					$pahro_users_model->clear_user_countries_owned($_GET['pahro_id']);
					// Actually deleting the PHARO user from the users table
					$pahro_users_model->delete_user_details_from_the_table($_GET['pahro_id']);
					// Log keeping
					$log_params = array(
										"user_id" => $_SESSION['logged_user']['id'], 
										"action_desc" => "Deleted system user id {$_GET['pahro_id']}",
										"date_crated" => date("Y-m-d-H:i:s")
										);
					$pahro_users_model->keep_track_of_activity_log_in_pahro($log_params);
					$_SESSION['user_deleted'] = "true";
					// Unset all used variables	
					unset($pahro_users_model);
					AppController::redirect_to($site_config['base_url'] ."pahro/view/");																										
				}	
			break;			
			
			case "delete-multiple":
				// All global variables		
				global $site_config;
				$_SESSION['invalid_user_id_found'] = false;				
				$deleted_users_ids = "";
				// objects instantiation
				$pahro_users_model = new PahroUserModel();						
				// Grab the vol ids from the url
				$qStrings = str_replace("pahro_ids=&", "", $_SERVER['QUERY_STRING']);
				$vol_ids = explode("&", $qStrings);
				// Check whether each vol id is exist in the database
				foreach($vol_ids as $each_id){
				
					if (!$pahro_users_model->check_pahro_id_exist($each_id)){
						$_SESSION['invalid_user_id_found'] = true;
						break;						
					}
				}
				// If invalid user id found the system should redirected to the view users section
				if ($_SESSION['invalid_user_id_found']){
					AppController::redirect_to($site_config['base_url'] ."pahro/view/");																															
				}else{
					// If invalid user id not found
					foreach($vol_ids as $each_id){
					
						if ($pahro_users_model->is_being_assgined_to_any_case($each_id)){					
							$pahro_users_model->unlink_assigned_cases_prior_to_delete($each_id);
						}	
					
						// Clear the user owned countries
						$pahro_users_model->clear_user_countries_owned($each_id);
						// Actually deleting the PHARO user from the users table
						$pahro_users_model->delete_user_details_from_the_table($each_id);
						$deleted_users_ids .= $each_id . " ";
					}
					// Log keeping
					$log_params = array(
										"user_id" => $_SESSION['logged_user']['id'], 
										"action_desc" => "Deleted selected volunteers : their ids are :".$deleted_users_ids,
										"date_crated" => date("Y-m-d-H:i:s")
										);
					$pahro_users_model->keep_track_of_activity_log_in_pahro($log_params);
					$_SESSION['multiple_users_deleted'] = true;
					AppController::redirect_to($site_config['base_url'] ."pahro/view/");																																				
				}			
			break;
			
			
			case "status":
				// All global variables		
				global $site_config;	
									
				$status = $_GET['status'];
				if(($status != 1) && ($status != 0)){
					AppController::redirect_to($site_config['base_url'] ."pahro/view/");
					exit();
				}

				// objects instantiation
				$pahro_users_model = new PahroUserModel();
				
				if($status == 1){
					$log_msg = 'Activated';
				}
				else{
					$log_msg = 'Deactivated';
				}
				$page = $_GET['pg'];
				if(trim($page) != ''){
					$page = '?page='.$page;
				}

				// Check whether the user id is exist in the database						
				if ($pahro_users_model->check_pahro_id_exist(trim($_GET['pahro_id'])) == ""){
					unset($_SESSION['is_not_exist_user_id']);
					$_SESSION['is_not_exist_user_id'] = "true";				
					AppController::redirect_to($site_config['base_url'] ."pahro/view/");
				// Check whether the user id is equal to logged user
				}elseif (trim($_GET['pahro_id']) == $_SESSION['logged_user']['id']){
					unset($_SESSION['is_assinged_currently_logged_user']);
					$_SESSION['is_assinged_currently_logged_user'] = "true";				
					AppController::redirect_to($site_config['base_url'] ."pahro/view/");																										
				// Check whether the selected user have been responsible for any case
				}else{
					//sets status = 0 PHARO user from the users table
					$pahro_users_model->set_user_status($_GET['pahro_id'],$status);
					// Log keeping
					$log_params = array(
										"user_id" => $_SESSION['logged_user']['id'], 
										"action_desc" => "$log_msg user account. ID - {$_GET['pahro_id']}",
										"date_crated" => date("Y-m-d-H:i:s")
										);
					$pahro_users_model->keep_track_of_activity_log_in_pahro($log_params);
					$_SESSION['user_status'] = $status;
					// Unset all used variables	
					unset($pahro_users_model);
					
					AppController::redirect_to($site_config['base_url'] ."pahro/view/$page");																										
				}	
			break;
						
			
			case "show":
				// All global variables		
				global $fullDetails;
				global $historyDetails;
				global $breadcrumb;
				global $site_config;				
				global $assigned_cases;
				global $responsible_cases;
				$breadcrumb = "";
				// Bredcrmb to the pfa section
				$breadcrumb .= "<div class=\"breadcrumbMessageDiv defaultFont boldText\"><a href=\"".$site_config['base_url']
								."\" class=\"headerLink\">Home</a> &rsaquo; "."<a class=\"headerLink\" href=\"".$site_config['base_url']."pahro/view/\">Manage Users</a> &rsaquo; Details</div>";
				// objects instantiation
				$pahro_users_model = new PahroUserModel();
				// Check whether the address book id is exist in the database						
				if (
					($pahro_users_model->check_pahro_id_exist(trim($_GET['pahro_id'])) != "") && 
				    ($pahro_users_model->check_pahro_country_with_curr_selected_country($_SESSION['curr_country']['country_code'], trim($_GET['pahro_id'])))
				   )				
					{										
					$fullDetails = $pahro_users_model->grab_full_details_for_the_single_view_in_pahro(trim($_GET['pahro_id']));		
					$assigned_cases = $pahro_users_model->rerieve_assigned_cases(trim($_GET['pahro_id']));
					$responsible_cases = $pahro_users_model->rerieve_responsible_cases_for_staff(trim($_GET['pahro_id']));					
					// Log keeping
					$log_params = array(
										"user_id" => $_SESSION['logged_user']['id'], 
										"action_desc" => "Viewed details of system user id {$_GET['pahro_id']}",
										"date_crated" => date("Y-m-d-H:i:s")
										);
					$pahro_users_model->keep_track_of_activity_log_in_pahro($log_params);
					unset($pahro_users_model);
					unset($fullDetails);
				// If wrong id	
				}else{
					AppController::redirect_to($site_config['base_url'] ."pahro/view/");																																
				}	
			break;
			
			case "download":

					$type = $_GET['type'];
					$where = $_GET['where'];
					$filename = $_GET['file'];
					$file_path = SERVER_ROOT.'/'.$type.'/'.$where.$filename;
					
					$ary_chars = array(';',' ');

					if($type == 'file-manager'){
						$ary_file = CommonFunctions::get_real_file_name($filename);
						$real_name = $ary_file['real_name'];
						$file_path = SERVER_ROOT.'/'.$type.'/'.$where.$filename;
						$filename = $real_name;
						
					}
					else{
						$ary = explode('_', $filename, 2);
						$filename = $ary[1];
					}
					
					if(is_file($file_path)){						
						// fix for IE catching or PHP bug issue
						header("Pragma: public");
						header("Expires: 0"); // set expiration time
						header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
						
						// force download dialog
						header("Content-Type: application/force-download");
						header("Content-Type: application/octet-stream");
						header("Content-Type: application/download");
						
						// use the Content-Disposition header to supply a recommended filename and
						// force the browser to display the save dialog.
						header("Content-Disposition: attachment; filename=".basename(str_replace($ary_chars,'_',$filename)).";");
						
						header("Content-Transfer-Encoding: binary");
						header("Content-Length: ".filesize($file_path));					
						readfile($file_path);
					}
					
			break;
			
			case "acc-edit":
				// All Global variables
				global $printHtml;
				global $full_details;
				global $headerDivMsg;
				global $site_config;
				global $breadcrumb;
				global $rand_pass;
				global $pahro_id;
				global $all_countries;
				global $pahro_users_model;
				global $for_multiple_users_text;				
				$breadcrumb = "";
				$mode = "";
				// Object Instantiation
				$pahro_users_model = new PahroUserModel();
				$commonFuncs = new CommonFunctions();
				// Retieeve all counties
				$countires_params = array("country_id", "country_name");
				$all_countries = $pahro_users_model->retrieve_user_country_names($_SESSION['logged_user']['id'], $countires_params);		
				$for_multiple_users_text = "Hold CTRL to select multiple items";				
				// Genrate random password
				if (!isset($_SESSION['rand_pass'])) $_SESSION['rand_pass'] = CommonFunctions::createRandomPassword();
				// Check whether the address book id is exist in the database						
				if ($pahro_users_model->check_pahro_id_exist(trim($_GET['pahro_id'])) != ""){															
					// Load the pahro user details for the given 
					$full_details = $pahro_users_model->retrieve_full_details_per_each_pahro_user(trim($_GET['pahro_id']));
					$pahro_id = $full_details[0]['id'];
					// Display the success message after updating user details
					if ($_SESSION['user_details_updated'] == "true"){
						$headerDivMsg = "<div class=\"headerMessageDivInSuccess defaultFont\">User details was updated successfully.</div>";																																			
					}
					unset($_SESSION['user_details_updated']);
					// Bredcrmb to the pfa section				
					$breadcrumb .= "<div class=\"breadcrumbMessageDiv defaultFont boldText\"><a href=\"".$site_config['base_url']."\" class=\"headerLink\">Home</a>";
					$breadcrumb .= "</div>";
					// Start to validate and other main functions				
					if ("POST" == $_SERVER['REQUEST_METHOD']){
						// If the add / save button has been clicked
						if (isset($_POST['pahro_submit'])){
							// Data validations						
							$errors = AppModel::validate($_POST['pahro_reqired']);				
							$_SESSION['pahro_reqired'] = $_POST['pahro_reqired'];
							$_SESSION['pahro_not_reqired'] = $_POST['pahro_not_reqired'];
							// If not having errors proceed with the databsse saving					
							if ($errors){
								$_SESSION['pahro_reqired_errors'] = $errors;					
								// Display the error message						
								$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Required fields cannot be blank !</div>";											
							}else{
								if (
								   ($_POST['pahro_not_reqired_spec']['new_password'] != "") ||
								   ($_POST['pahro_not_reqired_spec']['password'] != "") ||
								   ($_POST['pahro_not_reqired_spec']['confirm_password'] != "")
								   ){	
										// If the new password is being provided : then should be validated
										$errors = AppModel::validate($_POST['pahro_not_reqired_spec']);
										$_SESSION['pahro_not_reqired_spec'] = $_POST['pahro_not_reqired_spec'];
										if ($errors){
											// Display the error message
											$_SESSION['pahro_reqired_errors'] = $errors;						
											$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Current passowrd, confirmation of password and new passowrds required.</div>";																								
											$need_to_be_submit = "false";
										// If passwords are too short	
										}else{
											$fields_with_length = array($_POST['pahro_not_reqired_spec']['new_password'] => 6);
											$errors = AppModel::check_max_field_length($fields_with_length, "new_password");
											if ($errors){
												// Display the error message
												$_SESSION['pahro_reqired_errors']['new_password'] = "new_password";						
												$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Password too short.</div>";																		
												$need_to_be_submit = "false";										
											}elseif (!$pahro_users_model->check_previous_password_correct(trim($full_details[0]['username']), trim(md5($_SESSION['pahro_not_reqired_spec']['password'])))){
											// Check the previous password is correct																
												if (isset($_SESSION['pahro_reqired_errors']['new_password'])) unset($_SESSION['pahro_reqired_errors']['new_password']);								
												if (isset($_SESSION['pahro_reqired_errors']['confirm_password'])) unset($_SESSION['pahro_reqired_errors']['confirm_password']);															
												// Display the error message
												$_SESSION['pahro_reqired_errors']['password'] = "password";						
												$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Current Password is not correct !</div>";																								
												$need_to_be_submit = "false";																				
											// Check the password and the confirm password are same														
											}elseif ((trim($_POST['pahro_not_reqired_spec']['new_password']) != trim($_POST['pahro_not_reqired_spec']['confirm_password']))){	
												if (isset($_SESSION['pahro_reqired_errors']['password'])) unset($_SESSION['pahro_reqired_errors']['password']);								
												// Display the error message
												$_SESSION['pahro_reqired_errors']['new_password'] = "new_password";						
												$_SESSION['pahro_reqired_errors']['confirm_password'] = "confirm_password";													
												$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">New password and current password not matching !</div>";																		
												$need_to_be_submit = "false";																				
											}else{
												$need_to_be_submit = "true";
											}
										}											
									}else{
										$need_to_be_submit = "true";
									}
									if ($need_to_be_submit == "true"){		
										// Then update the users table
										$user_details_params = array(
																	"first_name" => $_SESSION['pahro_reqired']['first_name'], "last_name" => $_SESSION['pahro_reqired']['last_name'],														
																	"password" => ($_POST['pahro_not_reqired_spec']['new_password'] != "") ? trim(md5($_SESSION['pahro_not_reqired_spec']['new_password'])) : "", 
																	"email" => $_SESSION['pahro_not_reqired']['email']//, "country_id" => $_SESSION['pahro_reqired']['country']
																	);
										// Update user other details								
										$pahro_users_model->update_user_details($full_details[0]['id'], $user_details_params);
										// Log keeping
										$log_params = array(
															"user_id" => $_SESSION['logged_user']['id'], 
															"action_desc" => "Updated system user details of user id {$full_details[0]['id']}",
															"date_crated" => date("Y-m-d-H:i:s")
															);
										$pahro_users_model->keep_track_of_activity_log_in_pahro($log_params);
										// Unset all used variables	
										unset($_SESSION['pahro_reqired']);						
										unset($_SESSION['pahro_not_reqired']);
										unset($_SESSION['pahro_reqired_errors']);
										unset($_SESSION['pahro_not_reqired_spec']);
										unset($full_details);
										unset($pahro_users_model);	
										$_SESSION['user_details_updated'] = "true";
										AppController::redirect_to($site_config['base_url']."pahro/acc-edit/?pahro_id=".trim($_GET['pahro_id']));									
									}	
								}	
							}						
							// End of the notes section submission
						}else{
							unset($_SESSION['pahro_reqired']);						
							unset($_SESSION['pahro_not_reqired']);
							unset($_SESSION['pahro_reqired_errors']);
							unset($_SESSION['pahro_not_reqired_spec']);
							// If the user wants to reset the password
							if (isset($_GET['action']) && ($_GET['action'] == "reset")){
								$pahro_users_model->reset_password(trim($_GET['pahro_id']), $_SESSION['rand_pass']);
								$_SESSION['password_reseted'] = "true";
								AppController::redirect_to($site_config['base_url']."pahro/view/".((isset($_GET['page'])) ? "?page=".$_GET['page'] : "?page=1"));
							}
						}
				// If wrong id	
				}else{
					AppController::redirect_to($site_config['base_url'] ."pahro/view/");																
				}	
			break;
			
			case "import":
				// Global Variables
				global $refined_volunteers;
				global $site_config;
				global $breadcrumb;
				global $headerDivMsg;
				global $previously_imported_vols;	
				global $previously_imported_vols_count;
				global $tot_page_count;	
				global $pagination;	
				global $img;
				global $import_inter_log_for_sa;
				global $import_inter_log_for_gh;
				// Display the success message
				$headerDivMsg = "";
				if ($_SESSION['have_new_volunteers']){
					if ($_SESSION['volunteers_imported']){
						if ($_SESSION['south_africa_clicked']){
							$_SESSION['imported_country'] = "South Africa";
							unset($_SESSION['south_africa_clicked']);
						}elseif ($_SESSION['ghana_clicked']){
							$_SESSION['imported_country'] = "Ghana";
							unset($_SESSION['ghana_clicked']);							
						}
						$headerDivMsg = "<div class=\"headerMessageDivInSuccess defaultFont\">Successfully imported ". $_SESSION['no_of_new_vols']." new volunteers from ".$_SESSION['imported_country'].".</div>";										
						unset($_SESSION['no_of_new_vols']);						
						unset($_SESSION['volunteers_imported']);						
					}
				}
				if (isset($_SESSION['headerDivMsg'])) { $headerDivMsg = $_SESSION['headerDivMsg']; unset($_SESSION['headerDivMsg']); }
				// Object Instantiation
				$pahro_users_model = new PahroUserModel();
				$pagination_obj = new Pagination();				
				// Breadcumb
				$breadcrumb = "";
				$breadcrumb .= "<div class=\"breadcrumbMessageDiv defaultFont boldText\"><a href=\"".$site_config['base_url']."\" class=\"headerLink\">Home</a> &rsaquo; Import Users</div>";
				// Generate the parameters according to the user's country
				$u_permissions = array('1', '3', '4', '9', '11', '12', '13', '14', '15', '16', '22', '26', '27', '30', '34', '35', '37', '41', '45', '46', '49', '53', '58', '62', '63', '67', '68', '71', '72');
				// Display all vols	with their sorting			
				if (isset($_GET['sort'])){	
				
					$imgDefault = "<a href=\"".$site_config['base_url']."pahro/import/?sort=".$_GET['sort']."&by=asc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\"><img src=\"../../public/images/s_asc.png\" border=\"0\" /></a>";			
					$imgAsc = "<a href=\"".$site_config['base_url']."pahro/import/?sort=".$_GET['sort']."&by=asc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\"><img src=\"../../public/images/s_asc.png\" border=\"0\" /></a>";			
					$imgDesc = "<a href=\"".$site_config['base_url']."pahro/import/?sort=".$_GET['sort']."&by=desc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\"><img src=\"../../public/images/s_desc.png\" border=\"0\" /></a>";								
					$img = (!isset($_GET['by'])) ? ($imgDefault) : (($_GET['by'] == "asc") ? $imgDesc : $imgAsc);

					$sort_param_array = array(
											  "u_name" => "username", "f_name" => "first_name", "l_name" => "last_name", "email" => "email", 
											  "created_at" => "created_at", "last_login" => "last_login"
											 );
					foreach($sort_param_array as $key => $value) {
						if ($key == $_GET['sort']) {
							$sortBy = $value;
						}
					}
				}
				// Load previously imported volunteers
				$cur_page = ((isset($_GET['page'])) && ($_GET['page'] != "") && ($_GET['page'] != 0)) ? $_GET['page'] : 1; 										
				$previously_imported_vols = $pahro_users_model->retrieve_prevoulsly_imported_volunteers($cur_page, $sortBy, (isset($_GET['by'])) ? $_GET['by'] : "", $_SESSION['logged_user']['countries'], $_SESSION['curr_country']['country_code']);	
				$previously_imported_vols_count = $pahro_users_model->retrieve_count_of_prevoulsly_imported_volunteers($_SESSION['logged_user']['countries'], $_SESSION['curr_country']['country_code']);
				// Creating the path for pagintion
				$pagination = $pagination_obj->generate_pagination($previously_imported_vols_count, $_SERVER['REQUEST_URI'], NO_OF_RECORDS_PER_PAGE);				
				$tot_page_count = ceil($previously_imported_vols_count/NO_OF_RECORDS_PER_PAGE_DEFAULT);	
				// Grab the import interaction log
				$import_inter_log_for_sa = $pahro_users_model->retrieve_the_import_interaction_records("13");
				$import_inter_log_for_gh = $pahro_users_model->retrieve_the_import_interaction_records("4");				
				// If the post has been submitted				
				if ("POST" == $_SERVER['REQUEST_METHOD']){
				
					if (isset($_POST['mytpa_submit_from_sa'])){
						$_SESSION['south_africa_clicked'] = true;
						$country_params = 13;
						$from_which_country_imported = "13";
					}
					if (isset($_POST['mytpa_submit_from_ghana'])){
						$_SESSION['ghana_clicked'] = true;						
						$country_params = 4;				
						$from_which_country_imported = "4";								
					}					
					// Grab the volunteers who are not in the pahro database
					$refined_volunteers = $pahro_users_model->retrieve_volunteers_involved_with_hr_projects($country_params);
					if ($refined_volunteers){
						$_SESSION['have_new_volunteers'] = true;
					}else{
						$_SESSION['no_new_volunteers'] = true;						
					}
					// Grab the no of volunteers that are going to import
					$_SESSION['no_of_new_vols'] = count($refined_volunteers);	
					// Insert each mytpa vusers to the pahro with thier permissions and countries owned by them
					$nums = 0;
					foreach($refined_volunteers as $each_vol){
						
						if (isset($each_vol['mytpa_id'])){
							$volunteer_country_params = $pahro_users_model->retrieve_volunteers_country_list($each_vol['mytpa_id']);
							// Add the user to the users table						
							$pahroNewUserDetails = array(
														"first_name" => $each_vol['firstname'], "last_name" => $each_vol['surname'],
														"email"	=> $each_vol['email'], "user_type_id" => 2,
														"username" => $each_vol['user'], "password" => $each_vol['password'],
														"created_at" => date('Y-m-d'), "last_login" => ""
														 );
							$newly_inserted_pahro_user_id = $pahro_users_model->insert_new_pahro_user($pahroNewUserDetails, $each_vol['mytpa_id']);								
							// Insert user countries to the countries table
							$pahro_users_model->insert_new_pahro_user_countires($newly_inserted_pahro_user_id, $volunteer_country_params);
							// Add user permissions to the pagos_user_grups table regarding the newly added pahro user
							$pahro_users_model->insert_volunteers_permissions_in_import_process($newly_inserted_pahro_user_id, $u_permissions);
							$nums++;
						}	
					}
					// Keep the import volunteers interaction as a database record
					$interaction_params = array(
													"last_clicked_date" => date("Y-m-d-H:i:s"),
													"last_clicked_user" => $_SESSION['logged_user']['id'],
													"no_of_vols_imported" => $_SESSION['no_of_new_vols'],
													"interaction_by" => $from_which_country_imported
											   );
					$pahro_users_model->keep_the_track_for_import_interaction($interaction_params);					
					if($nums>0){
						$_SESSION['volunteers_imported'] = true;					
						// Log keeping
						$log_params = array(
											"user_id" => $_SESSION['logged_user']['id'], 
											"action_desc" => "Imported a list of volunteers.",
											"date_crated" => date("Y-m-d-H:i:s")
											);
						$pahro_users_model->keep_track_of_activity_log_in_pahro($log_params);
					}else{
						if ($_SESSION['no_new_volunteers']){
							if ($_SESSION['south_africa_clicked']){
								$_SESSION['imported_country'] = "South Africa";
								unset($_SESSION['south_africa_clicked']);
							}elseif ($_SESSION['ghana_clicked']){
								$_SESSION['imported_country'] = "Ghana";
								unset($_SESSION['ghana_clicked']);							
							}
							$_SESSION['headerDivMsg'] = "<div class=\"headerMessageDivInNotice defaultFont\">No new volunteers to import from ".$_SESSION['imported_country'].".</div>";															
							unset($_SESSION['imported_country']);					
							unset($_SESSION['no_new_volunteers']);	
						}
					}
					AppController::redirect_to($site_config['base_url']."pahro/import/");																														 																
				}
			break;
		}	
	}
}
?>