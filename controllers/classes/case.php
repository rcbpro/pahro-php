<?php

class CaseController extends AppController{	

	/* This function will check the correct sub view provided else redirect to the home page */
	function correct_sub_view_gate_keeper($subView){
	
		if (isset($subView)){
			$modes_array = array("main", "edit-notes", "add-notes");
			if (!in_array($subView, $modes_array)){
				global $site_config;
				AppController::redirect_to($site_config['base_url']."case/view/");
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
	function process_the_correct_model($view, $controller, $mode = ""){

		switch($view) {
		
			case "add":
				// All Global variables
				global $case_cats;
				global $staff_names;
				global $refined_volunteers;
				global $all_notes_to_this_client;				
				global $nationalityArray;
				global $countryListArray;
				global $languageListArray;
				global $headerDivMsg;
				global $site_config;
				global $printHtml;
				global $breadcrumb;
				global $case_statuses;
				global $all_countries;
				$breadcrumb = "";
				// Generate the top header menu
				$printHtml = "";
				$printHtml .= ((isset($_GET['mode'])) && ($_GET['mode'] == "main")) ? "<span class=\"specializedTexts\">Main Details</span>" : "<span class=\"disabledHrefLinks\">Main Details</span>";					
				$printHtml .= "&nbsp;&nbsp;<span class=\"ar\">&rsaquo;</span>&nbsp;&nbsp;";
				$printHtml .= ((isset($_GET['mode'])) && ($_GET['mode'] == "notes")) ? "<span class=\"specializedTexts\">Notes</span>" : "<span class=\"disabledHrefLinks\">Notes</span>";					
				// Bredcrmb to the pfa section
				$breadcrumb .= "<div class=\"breadcrumbMessageDiv defaultFont boldText\"><img />`<a href=\"".$site_config['base_url']."\" class=\"headerLink\">Home</a> &rsaquo; Add New Case</div>";
				// Object Instantiation
				$caseModel = new CaseModel(); 
				$case_cats = $caseModel->retrieve_case_categories();
				$staff_names = $caseModel->retrieve_all_staff_memebers($_SESSION['curr_country']['country_code']);
				$volunteers = $caseModel->retrieve_all_volunteers_to_assign_case($_SESSION['curr_country']['country_code']);
				// Refine to display only country included users
				foreach($volunteers as $each_vola){
				
					if (in_array($each_vola['country_id'], $_SESSION['logged_user']['countries'])){
						$refined_volunteers[] = $each_vola;
					}
				}		
				$case_statuses = array("Active", "Inactive", "Closed");
				// If the correct post back not submitted then redirect the user to the correct page
				if (($mode == "add-notes") && (!isset($_SESSION['newly_inserted_case_id']))){
					AppController::redirect_to($site_config['base_url'] ."case/add/?mode=main");
				}
				// Switch to the correct sub view
				switch($mode){
					
					case "main": 
						$whichFormToInclude = "main"; 
						unset($_SESSION['newly_inserted_case_id']);
						if (count($_SESSION['logged_user']['countries']) > 1){
							// Retieeve all counties
							$countires_params = array("country_id", "country_name");
							$all_countries = $caseModel->retrieve_all_countires_for_users($countires_params);
						}
					break;
				}
				// If post has been submitted
				if ("POST" == $_SERVER['REQUEST_METHOD']){
					$errors = AppModel::validate($_POST['case_main_reqired']);
					$_SESSION['case_main_reqired'] = $_POST['case_main_reqired'];
					$_SESSION['case_main_non_reqired'] = $_POST['case_main_non_reqired'];
					$_SESSION['case_status'] = $_POST['case_status'];					
					// Data Grabbing and validating for the required in contact view										
					if ((!empty($_POST['case_main_open_non_reqired']['month'])) || (!empty($_POST['case_main_open_non_reqired']['day'])) || (!empty($_POST['case_main_open_non_reqired']['year']))){
						$_SESSION['case_main_open_non_reqired'] = $_POST['case_main_open_non_reqired'];					
						// Check data is valid
						if (!@checkdate($_SESSION['case_main_open_non_reqired']['month'], $_SESSION['case_main_open_non_reqired']['day'], $_SESSION['case_main_open_non_reqired']['year'])){
							$_SESSION['date_input_error'] = true;
						}else{
							$_SESSION['date_input_error'] = false;							
						}
					}elseif (
							(empty($_POST['case_main_open_non_reqired']['month'])) && 
							(empty($_POST['case_main_open_non_reqired']['day'])) && 
							(empty($_POST['case_main_open_non_reqired']['year']))								
							){
								unset($_SESSION['case_main_open_non_reqired']);
								$_SESSION['date_input_error'] = false;															
					}
					// Data Grabbing and validating for the required in contact view										
					if ((!empty($_POST['case_main_upcoming_non_reqired']['month'])) || (!empty($_POST['case_main_upcoming_non_reqired']['day'])) || (!empty($_POST['case_main_upcoming_non_reqired']['year']))){					
						$_SESSION['case_main_upcoming_non_reqired'] = $_POST['case_main_upcoming_non_reqired'];					
						// Check data is valid
						if (!@checkdate($_SESSION['case_main_upcoming_non_reqired']['month'], $_SESSION['case_main_upcoming_non_reqired']['day'], $_SESSION['case_main_upcoming_non_reqired']['year'])){
							$_SESSION['date_input_error_2'] = true;
						}else{
							$_SESSION['date_input_error_2'] = false;							
						}
					}elseif (
							(empty($_POST['case_main_upcoming_non_reqired']['month'])) && 
							(empty($_POST['case_main_upcoming_non_reqired']['day'])) && 
							(empty($_POST['case_main_upcoming_non_reqired']['year']))													
							){
								unset($_SESSION['case_main_upcoming_non_reqired']);
								$_SESSION['date_input_error_2'] = false;															
					}
					// Data Grabbing and validating for the required in contact view										
					if ((!empty($_POST['case_main_close_non_reqired']['month'])) || (!empty($_POST['case_main_close_non_reqired']['day'])) || (!empty($_POST['case_main_close_non_reqired']['year']))){										
						$_SESSION['case_main_close_non_reqired'] = $_POST['case_main_close_non_reqired'];					
						// Check data is valid
						if (!@checkdate($_SESSION['case_main_close_non_reqired']['month'], $_SESSION['case_main_close_non_reqired']['day'], $_SESSION['case_main_close_non_reqired']['year'])){
							$_SESSION['date_input_error_3'] = true;
						}else{
							$_SESSION['date_input_error_3'] = false;							
						}
					}
					// If notes form has been submitted
					if (isset($_POST['case_main_submit'])){	
						// If errors found in the adding section
						if ($errors){
							$_SESSION['case_reqired_errors'] = $errors;					
							// Display the error message						
							$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Required fields cannot be blank !</div>";
					   		$need_to_be_submit = false;																
						}elseif ($caseModel->check_case_ref_no_is_unique($_POST['case_main_reqired']['reference_number'])){
							$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please use different Reference no for Case ID : This no is already been used.</div>";																		
					   		$need_to_be_submit = false;																
						}elseif ($_SESSION['case_status'] == ""){
							$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please select the Case Current Status.</div>";																		
					   		$need_to_be_submit = false;																
						}elseif ((isset($_SESSION['date_input_error'])) && ($_SESSION['date_input_error'] == 1)){
							$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Invalid date for Case Opened date.</div>";																		
					   		$need_to_be_submit = false;																
						}elseif ((isset($_SESSION['date_input_error_2'])) && ($_SESSION['date_input_error_2'] == 1)){
							$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Invalid date for Case Review Date.</div>";																		
					   		$need_to_be_submit = false;																
						}elseif ((isset($_SESSION['date_input_error_3'])) && ($_SESSION['date_input_error_3'] == 1)){
							$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Invalid date for Case Closed date.</div>";																		
					   		$need_to_be_submit = false;																
						}elseif ($_SESSION['case_status'] == "Closed"){

							if (
							    (empty($_POST['case_main_close_non_reqired']['month'])) && 
								(empty($_POST['case_main_close_non_reqired']['day'])) && 
								(empty($_POST['case_main_close_non_reqired']['year']))
							   ){
									$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please select the Case Closed date.</div>";																											   		
							   		$need_to_be_submit = false;																		
							   }elseif (empty($_SESSION['case_main_non_reqired']['reasone_for_close'])){
									$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please prvoide the reason for the case to be closed.</div>";																											   									   	
							   		$need_to_be_submit = false;									
							   }else{
							   		$need_to_be_submit = true;
							   }
						}else{						
							$need_to_be_submit = true;
						}		

						if ($need_to_be_submit){

							if (($_SESSION['case_status'] == "Active") || ($_SESSION['case_status'] == "Inactive")){
								unset($_SESSION['case_main_non_reqired']['reasone_for_close']);
								unset($_SESSION['case_main_close_non_reqired']);						
							}	
							$case_dates = array(
												"case_open_date" => ((isset($_POST['case_main_open_non_reqired'])) ? $_POST['case_main_open_non_reqired'] : ""),
												"case_upcoming_date" => ((isset($_POST['case_main_upcoming_non_reqired'])) ? $_POST['case_main_upcoming_non_reqired'] : ""),
												"case_close_date" => ((isset($_POST['case_main_close_non_reqired'])) ? $_POST['case_main_close_non_reqired'] : "")
											   );
							// If users' owned country count is equal to one then add user's session country else give the user to select one of country from the country list
							$country = (count($_SESSION['logged_user']['countries']) == 1) ? $_SESSION['logged_user']['countries'][0] : $_SESSION['case_main_reqired']['case_owned_country_id'];
							// Insert case details				   
							$_SESSION['newly_inserted_case_id'] = $caseModel->insert_case_details(array_merge($_SESSION['case_main_reqired'], $_SESSION['case_main_non_reqired']), 
																										$case_dates, $_SESSION['logged_user']['id'], date("Y-m-d-H:i:s"),
																										$_SESSION['case_status'], $_SESSION['curr_country']['country_code']);
							// Create the template folder
							if(count($_SESSION['case_main']['template_name']) > 0){
								$upload_folder_path = $_SERVER['DOCUMENT_ROOT'].DS."user-uploads/cases";
								$case_folder_path = $upload_folder_path.DS.$_SESSION['case_main_reqired']['reference_number'];
								if(!is_dir($case_folder_path)){
									mkdir($case_folder_path);
								}
								// Insert case templates details
								$case_templates_details = array(
																"name" => $_SESSION['case_main']['template_name'], 
																"case_id" => $_SESSION['newly_inserted_case_id']
																);
								$caseModel->insert_case_templates_details($case_templates_details, "add");
							}
							
							if(!isset($_POST['case_main_submit'])){
							
								$ary_for_select_box = array_diff($ary_vols,$ary_assined_vols);
								$ary_for_added_box = array();
								$ary_for_assined_box = $ary_assined_vols;
								//echo count($ary_vols);
							}
							else{//after hit save buttom
								$chk_ary_added = @$_POST['chkad'];
								if(!is_array($chk_ary_added)){$chk_ary_added = array();}
								$ary_for_select_box = @array_diff($ary_vols,$ary_assined_vols,$chk_ary_added);
								$ary_for_added_box = $chk_ary_added;
								$ary_for_assined_box = $ary_assined_vols;
								//////////////////////////////////////////
								$ary_db_ad = @$_POST['chkad'];
								$ary_db_as = @$_POST['chkas'];
								if(!is_array($ary_db_ad)){$ary_db_ad = array();}
								if(!is_array($ary_db_as)){$ary_db_as = array();}
								$ary_for_db = array_unique(array_merge($ary_db_ad,$ary_db_as));
							}
							
																										
							if (!empty($ary_for_db)){									
								$caseModel->insert_involved_volunteers($_SESSION['newly_inserted_case_id'], $ary_for_db);
							}
							// Log keeping
							$log_params = array(
												"user_id" => $_SESSION['logged_user']['id'], 
												"action_desc" => "New CASE was saved. Reference No. : ".trim(urldecode($_SESSION['newly_inserted_case_id'])),
												"date_crated" => date("Y-m-d-H:i:s")
												);
							$caseModel->keep_track_of_activity_log_in_case($log_params);
							$_SESSION['new_case_added'] = "true";
							// Unset all used data
							unset($_SESSION['case_reqired_errors']);						
							unset($_SESSION['case_main_reqired']);
							unset($_SESSION['case_main_non_reqired']);
							unset($_SESSION['case_status']);
							unset($_SESSION['case_main_close_non_reqired']['month']);
							unset($_SESSION['case_main_close_non_reqired']['day']);
							unset($_SESSION['case_main_close_non_reqired']['year']);
							unset($_SESSION['case_main_upcoming_non_reqired']['month']);
							unset($_SESSION['case_main_upcoming_non_reqired']['day']);
							unset($_SESSION['case_main_upcoming_non_reqired']['year']);
							unset($_SESSION['case_main_open_non_reqired']['month']);
							unset($_SESSION['case_main_open_non_reqired']['day']);
							unset($_SESSION['case_main_open_non_reqired']['year']);
							unset($_SESSION['date_input_error']);								
							unset($_SESSION['date_input_error_2']);								
							unset($_SESSION['date_input_error_3']);
							/* This is for the case templates uploading */							
							unset($_SESSION['case_main']);
							unset($caseModel);	
							AppController::redirect_to($site_config['base_url']."case/notes/?mode=add-notes");												
						}
					}		
						// If notes form has been submitted
						if (isset($_POST['case_notes_submit'])){
							// If no value has been submitted regarding the notes section						
							if (empty($_POST['case_note_categories_required'])){
								$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please select at least one category.</div>";		
							// If no text has been submitted								
							}elseif (empty($_POST['case_note_required']['note_text'])){
								$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please fill the note text box.</div>";		
							// If values has been submitted	
							}else{
								$_SESSION['case_note_required']['note_text'] = $_POST['case_note_required']['note_text'];							
								$notes_inputting_array = array(
																"case_id" => $_SESSION['newly_inserted_case_id'],
																"note" => $_SESSION['case_note_required']['note_text'],
																"added_by" => $_SESSION['logged_user']['id'],
																"added_date" => date("Y-m-d-H:i:s")
															  );
								$last_inserted_note_id = $caseModel->insert_new_note($notes_inputting_array);
								// Inserting the notes related categories
								$notes_categories_params = array(
																	"note_id" => $last_inserted_note_id,
																	"notes_categories" => $_POST['case_note_categories_required'],
																	"note_owner_section" => "CASE"
																);	
								$caseModel->insert_notes_categories($notes_categories_params);
								$_SESSION['new_note_has_created'] = "true";		
								// Log keeping
								$log_params = array(
													"user_id" => $_SESSION['logged_user']['id'], 
													"action_desc" => "Created a new note to CASE ID ".trim(urlencode($_SESSION['newly_inserted_case_id'])),
													"date_crated" => date("Y-m-d-H:i:s")
													);
								$caseModel->keep_track_of_activity_log_in_case($log_params);
								$notes_array = array('note_id', 'case_id', 'note');					
								$all_notes_to_this_client = $caseModel->retrieve_all_notes_owned_by_this_client($_SESSION['newly_inserted_case_id'], $notes_array);																																																	
								unset($_POST);									
								unset($_SESSION['case_note_required']['note_text']);
								AppController::redirect_to($site_config['base_url'] ."case/add/?mode=notes");									
							}
							$notes_array = array('note_id', 'case_id', 'note');					
							$all_notes_to_this_client = $caseModel->retrieve_all_notes_owned_by_this_client($_SESSION['newly_inserted_case_id'], $notes_array);																																																	
						}	
					}else{						
						unset($_SESSION['case_reqired_errors']);						
						unset($_SESSION['case_main_reqired']);
						unset($_SESSION['case_main_non_reqired']);
						unset($_SESSION['case_status']);
						unset($_SESSION['case_main_close_non_reqired']['month']);
						unset($_SESSION['case_main_close_non_reqired']['day']);
						unset($_SESSION['case_main_close_non_reqired']['year']);
						unset($_SESSION['case_main_upcoming_non_reqired']['month']);
						unset($_SESSION['case_main_upcoming_non_reqired']['day']);
						unset($_SESSION['case_main_upcoming_non_reqired']['year']);
						unset($_SESSION['case_main_open_non_reqired']['month']);
						unset($_SESSION['case_main_open_non_reqired']['day']);
						unset($_SESSION['case_main_open_non_reqired']['year']);
						unset($_SESSION['date_input_error']);								
						unset($_SESSION['date_input_error_2']);								
						unset($_SESSION['date_input_error_3']);
						unset($caseModel);	
						if (!isset($_SESSION['case_main_reqired'])) unset($_SESSION['case_main']);						
					}	
			break;
			
			case "view":
				// Unset all frist step session data
				unset($_SESSION['case_reqired_errors']);						
				unset($_SESSION['case_main_reqired']);
				unset($_SESSION['case_main_non_reqired']);
				unset($_SESSION['case_status']);
				unset($_SESSION['case_main_close_non_reqired']['month']);
				unset($_SESSION['case_main_close_non_reqired']['day']);
				unset($_SESSION['case_main_close_non_reqired']['year']);
				unset($_SESSION['case_main_upcoming_non_reqired']['month']);
				unset($_SESSION['case_main_upcoming_non_reqired']['day']);
				unset($_SESSION['case_main_upcoming_non_reqired']['year']);
				unset($_SESSION['case_main_open_non_reqired']['month']);
				unset($_SESSION['case_main_open_non_reqired']['day']);
				unset($_SESSION['case_main_open_non_reqired']['year']);
				unset($_SESSION['date_input_error']);								
				unset($_SESSION['date_input_error_2']);								
				unset($_SESSION['date_input_error_3']);
				unset($_SESSION['newly_inserted_case_id']);
				unset($_SESSION['case_main']);
				
				// All Global variables
				global $site_config;
				global $cur_page;				
				global $all_cases;
				global $all_cases_count;
				global $pagination;
				global $tot_page_count;
				global $img;
				global $breadcrumb;
				global $invalidPage;								
				global $headerDivMsg;
				global $action_panel_menu;
				$breadcrumb = "";
				$sortBy = "";
				// Object Instantiati0on
				$caseModel = new CaseModel(); 
				// Bredcrmb to the pfa section
				$breadcrumb .= "<div class=\"breadcrumbMessageDiv defaultFont boldText\"><a href=\"".$site_config['base_url']."\" class=\"headerLink\">Home</a> &rsaquo; Manage Cases</div>";
				$pagination_obj = new Pagination();				
				// Display the error message 
				if ($_SESSION['is_not_exist_case_id'] == "true"){
					$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Cannot perform deletion : Case ID not exist !.</div>";										
				}
				unset($_SESSION['is_not_exist_case_id']);
				// Display the success message 
				if ($_SESSION['deleted_case_id'] == "true"){
					$headerDivMsg = "<div class=\"headerMessageDivInNotice defaultFont\">Successfully deleted the case.</div>";										
				}
				unset($_SESSION['deleted_case_id']);
				// Configuring the action panel against user permissions
				$action_panel = array(
										array(
												"menu_id" => 1,
												"menu_text" => "Add / Edit Note",
												"menu_url" => $site_config['base_url']."case/notes/?mode=edit-notes&",
												"menu_img" => "<img src=\"../../public/images/notepadicon.png\" border=\"0\" alt=\"New / Edit Note\" />",
												"menu_permissions" => array(62, 63, 64, 66)
											),	
										array(
												"menu_id" => 2,
												"menu_text" => "Details",
												"menu_url" => $site_config['base_url']."case/show/?",
												"menu_img" => " <img src=\"../../public/images/b_browse.png\" border=\"0\" alt=\"Browse\" />",
												"menu_permissions" => array(1)
											),	
										array(
												"menu_id" => 3,
												"menu_text" => "Edit",
												"menu_url" => $site_config['base_url']."case/edit/?mode=main&",
												"menu_img" => " <img src=\"../../public/images/b_edit.png\" border=\"0\" alt=\"Edit\" />",
												"menu_permissions" => array(3)
											),
										array(
												"menu_id" => 4,
												"menu_text" => "Delete",
												"menu_url" => $site_config['base_url']."case/delete/?",
												"menu_img" => " <img src=\"../../public/images/b_drop.png\" border=\"0\" alt=\"Delete\" />",
												"menu_permissions" => array(4)
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
				$param_array = array('reference_number', 'case_name', 'case_owned_country_id', 'country_name', 'description', 'case_cat_name', 'status', 'opend_date', 'upcoming_date', 'username', 'staff_responsible', 'created_date');
				// Display all pfac_records				
				if (isset($_GET['sort'])){	
				
					$imgDefault = "<a href=\"".$site_config['base_url']."case/view/?sort=".$_GET['sort']."&by=asc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\"><img src=\"../../public/images/s_asc.png\" border=\"0\" /></a>";			
					$imgAsc = "<a href=\"".$site_config['base_url']."case/view/?sort=".$_GET['sort']."&by=asc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\"><img src=\"../../public/images/s_asc.png\" border=\"0\" /></a>";			
					$imgDesc = "<a href=\"".$site_config['base_url']."case/view/?sort=".$_GET['sort']."&by=desc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\"><img src=\"../../public/images/s_desc.png\" border=\"0\" /></a>";								
					$img = (!isset($_GET['by'])) ? ($imgDefault) : (($_GET['by'] == "asc") ? $imgDesc : $imgAsc);

					$sort_param_array = array(
											  "ref_no" => "reference_number", "case" => "case_name", "desc" => "description", "own_country" => "country_name", 
											  "case_cat" => "case_cat_name", "status" => "status", "op_date" => "opend_date", "up_date" => "upcoming_date",
											  "res_staff" => "staff_responsible", "cre_date" => "created_date", "cre_by" => "username"
											  );
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
				$all_cases = $caseModel->display_all_cases($param_array, $cur_page, $sortBy, ((isset($_GET['by'])) ? $_GET['by'] : ""), $_SESSION['logged_user']['countries'], $_SESSION['curr_country']['country_code']);
				$all_cases_count = $caseModel->display_cases_all_count($param_array, $_SESSION['logged_user']['countries'], $_SESSION['curr_country']['country_code']);
				// Pagination load
				$pagination = $pagination_obj->generate_pagination($all_cases_count, $curPath, NO_OF_RECORDS_PER_PAGE_FOR_CASES);				
				$tot_page_count = ceil($all_cases_count/NO_OF_RECORDS_PER_PAGE_FOR_CASES);				
				// If no records found or no pages found
				$page = (isset($_GET['page'])) ? $_GET['page'] : 1;
				if (($page > $tot_page_count) || ($page == 0)){
					$invalidPage = true;	
				}
				// Unset all used variables
				unset($caseModel);
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
				global $all_notes_to_this_client_in_edit;
				global $all_notes_count_to_this_client_in_edit;
				global $case_id;
				global $note_full_details;
				global $pagination;
				global $tot_page_count;
				global $img;
				global $staff_names;
				global $volunteers;
				global $refined_volunteers;
				global $case_statuses;
				global $case_handlling_vols;
				global $caseModel;
				global $pre_case_templates;
				global $all_countries;
				global $volunteers_currently_working;				
				global $ary_for_db;											
				$breadcrumb = "";
				// Bredcrmb to the pfa section				
				$case_id = trim(urlencode($_GET['ref_no']));
				$breadcrumb .= "<div class=\"breadcrumbMessageDiv defaultFont boldText\"><a href=\"".$site_config['base_url']
								."\" class=\"headerLink\">Home</a> ";
				if (isset($_GET['opt'])){
					$breadcrumb .= "&rsaquo; <a class=\"headerLink\" href=\"".$site_config['base_url']."case/view/\">All Cases</a> &rsaquo; <a class=\"headerLink\" href=\"".
										$site_config['base_url']."case/edit/?mode=main&ref_no=".urlencode($case_id)."&page=1"."\">Edit Case</a> &rsaquo; ".
										"<a class=\"headerLink\" href=\"".$site_config['base_url']."case/edit/?mode=notes&ref_no=".urlencode($case_id)."&page=1&notes_page=1"."\">Your all notes</a>";
					if ($_GET['opt'] == "view"){
						$breadcrumb	.= "&rsaquo; View single note";				
					}else{
						$breadcrumb	.= "&rsaquo; Edit single note";				
					}										
				}else{
					$breadcrumb .= "&rsaquo; <a class=\"headerLink\" href=\"".$site_config['base_url']."case/view/\">All Cases</a> &rsaquo; Edit Case";					
				}
				$breadcrumb .= "</div>";												
				// Generate the top header menu
				$printHtml = "";
				$printHtml .= ((isset($_GET['mode'])) && ($_GET['mode'] == "main")) ? "<span class=\"headerTopicSelected\">Main Details</span>" : "<span><a href=\"?mode=main&ref_no=".trim(urlencode($_GET['ref_no'])).((isset($_GET['page'])) ? "&page=".$_GET['page'] : "")."\">Main Details</a></span>";					
				$printHtml .= "&nbsp;&nbsp;<span class=\"ar\">&rsaquo;</span>&nbsp;&nbsp;";
				$printHtml .= ((isset($_GET['mode'])) && ($_GET['mode'] == "notes")) ? "<span class=\"headerTopicSelected\">Notes</span>" : "<span><a href=\"".$site_config['base_url']."case/notes/?mode=edit-notes&ref_no=".trim(urlencode($_GET['ref_no'])).((isset($_GET['page'])) ? "&page=".$_GET['page'] : "").((isset($_GET['notes_page'])) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\">Notes</a></span>";					
				// Object Instantiation
				$caseModel = new CaseModel(); 
				// Display the success message after success case updation
				if ($_SESSION['case_details_updated'] == "true"){
					$headerDivMsg = "<div class=\"headerMessageDivInSuccess defaultFont\">Successfully updated the case.&nbsp;&nbsp;<a class='edtingMenusLink_in_view' href='".
							$site_config['base_url']."case/show/?ref_no=".urlencode($case_id)."'>View details.</a></div>";																																			
					
				}
				unset($_SESSION['case_details_updated']);
				// Switch to the correct sub view
				switch($mode){
					
					case "main": 
						$whichFormToInclude = "main"; 
						// Load the case main variables
						$case_cats = $caseModel->retrieve_case_categories();
						$staff_names = $caseModel->retrieve_all_staff_memebers($_SESSION['curr_country']['country_code']);
						$volunteers = $caseModel->retrieve_all_volunteers_to_assign_case($_SESSION['curr_country']['country_code']);
						$volunteers_currently_working = $caseModel->retrieve_volunteers_currently_involved_with_cases($case_id);						
						// Refine to display only country included users
						foreach($volunteers as $each_vola){
						
							if (in_array($each_vola['country_id'], $_SESSION['logged_user']['countries'])){
								$refined_volunteers[] = $each_vola;
							}
						}	
						
						$pre_case_templates = $caseModel->retrieve_all_case_templates_for_this_case(trim(urlencode($_GET['ref_no'])));
						$case_statuses = array("Active", "Inactive", "Closed");
						if (count($_SESSION['logged_user']['countries']) > 1){
							// Retieeve all counties
							$countires_params = array("country_id", "country_name");
							$all_countries = $caseModel->retrieve_all_countires_for_users($countires_params);
						}
					break;
					case "notes":						 
						$whichFormToInclude = "notes"; 
						$pagination_obj = new Pagination();
						// Display the success message in new note adding
						if ($_SESSION['new_note_has_created'] == "true"){					
							$headerDivMsg = "<div class=\"headerMessageDivInSuccess defaultFont\">New note has been created.</div>";																																			
						}
						unset($_SESSION['new_note_has_created']);	
						// Display the notice message after deletion ogf the selected note
						if ($_SESSION['deleted_selected_note'] == "true"){					
							$headerDivMsg = "<div class=\"headerMessageDivInNotice defaultFont\">Deleted the selected note.</div>";																																			
						}
						unset($_SESSION['deleted_selected_note']);							
						// Display the success message after succesful updation of contact details						
						if ($_SESSION['case_details_updated'] == "true"){
							$headerDivMsg = "<div class=\"headerMessageDivInSuccess defaultFont\">Case details was updated successfully.&nbsp;&nbsp;<a class='edtingMenusLink_in_view' href='".
									$site_config['base_url']."case/show/?ref_no=".trim(urlencode($_GET['ref_no']))."'>View details.</a></div>";																																			
						}					
						unset($_SESSION['case_details_updated']);
						// Display the success message after succesful updation of contact details						
						if ($_SESSION['notes_section_got_updated'] == "true"){
							$headerDivMsg = "<div class=\"headerMessageDivInSuccess defaultFont\">The above note was updated successfully.</div>";																																			
						}					
						unset($_SESSION['notes_section_got_updated']);
						// Sorting of the notes section
						if (!isset($_GET['notes_page'])){ 
							$cur_path = "?".(isset($_GET['sort'])) ? "sort=".@$_GET['sort'].((isset($_GET['by'])) ? "&by=".$_GET['by'] : "") : ""; 
						}else{ 
							$cur_path = $_SERVER['REQUEST_URI']; 						
						}
						// Load all notes regarding to this client						
						$notes_array = array('note_id', 'note', 'username', 'added_date', 'modified_date', 'modified_by', "");						
						// Grab the current page
						$cur_page = ((isset($_GET['notes_page'])) && ($_GET['notes_page'] != "") && ($_GET['notes_page'] != 0)) ? $_GET['notes_page'] : 1; 												
						// Grab the pfac id
						if (isset($_GET['sort'])){	

							$imgDefault = "<a href=\"".$site_config['base_url']."case/edit/?mode=notes&ref_no=".$case_id."&opt=sorting&sort=".$_GET['sort']."&by=asc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\"><img src=\"../../public/images/s_asc.png\" border=\"0\" /></a>";			
							$imgAsc = "<a href=\"".$site_config['base_url']."case/edit/?mode=notes&ref_no=".$case_id."&opt=sorting&sort=".$_GET['sort']."&by=asc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\"><img src=\"../../public/images/s_asc.png\" border=\"0\" /></a>";			
							$imgDesc = "<a href=\"".$site_config['base_url']."case/edit/?mode=notes&ref_no=".$case_id."&opt=sorting&sort=".$_GET['sort']."&by=desc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\"><img src=\"../../public/images/s_desc.png\" border=\"0\" /></a>";								
							$img = (!isset($_GET['by'])) ? ($imgDefault) : (($_GET['by'] == "asc") ? $imgDesc : $imgAsc);
		
							$notes_array = array('note_id' => 'note_id', 'note' => 'note', 'add_by' => 'username', 'mod_date' => 'modified_date', 'mod_by' => 'modified_by', 'add_date' => 'added_date');						
							foreach($notes_array as $key => $value) {
								if ($key == $_GET['sort']) {
									$sortBy = $value;
								}
							}
						}
						// If the notes filtering button has been clicked
						if (isset($_POST['notes_filter'])){
							// If notes categories are empty then display the error message
							if (empty($_POST['case_note_categories_required'])){
								$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please select at least one category to filter.</div>";										
							}else{
								$filtering = true;
							}
						}
						// retrieve all notes and all notes couunt to this client
						$all_notes_to_this_client_in_edit = $caseModel->retrieve_all_notes_owned_by_this_client_only_for_the_edit_view($notes_array, $cur_page, $sortBy, ((isset($_GET['by'])) ? $_GET['by'] : ""), trim(urlencode($_GET['ref_no'])), $filtering, $_POST['case_note_categories_required']);
						$all_notes_count_to_this_client_in_edit = $caseModel->retrieve_all_notes_count_owned_by_this_client_only_for_the_edit_view(trim(urlencode($_GET['ref_no'])), $notes_array, $filtering, $_POST['case_note_categories_required']);
						$pagination = $pagination_obj->generate_pagination($all_notes_count_to_this_client_in_edit, $cur_path, $cur_page, NO_OF_RECORDS_PER_PAGE_DEFAULT);				
						$tot_page_count = ceil($all_notes_count_to_this_client_in_edit/NO_OF_RECORDS_PER_PAGE_DEFAULT);				
						// Delete the selected note	
						if ((isset($_GET['opt'])) && ($_GET['opt'] == "drop")){
							$caseModel->delete_selected_note($_GET['note_id'], $case_id);
							// Delete notes category relation as well regarding the note deletion
							$caseModel->remove_previous_notes_categories_relation(trim($_GET['note_id']));							
							$_SESSION['deleted_selected_note'] = "true";
							// Log keeping
							$log_params = array(
												"user_id" => $_SESSION['logged_user']['id'], 
												"action_desc" => "Deleted the note of CASE ID {$case_id}",
												"date_crated" => date("Y-m-d-H:i:s")
												);
							$caseModel->keep_track_of_activity_log_in_case($log_params);
							AppController::redirect_to($site_config['base_url']."case/edit/?mode=notes&ref_no=".$case_id.((isset($_GET['page'])) ? "&page=".$_GET['page'] : "").((isset($_GET['notes_page'])) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1"));							
						}
						// Show full details of the above client selected note
						if ((isset($_GET['opt'])) && (($_GET['opt'] == "view") || ($_GET['opt'] == "edit"))){						
							// Check the note id exist in the db
							if ($caseModel->check_note_id_exist(trim($_GET['note_id']))){						
								if ($caseModel->check_note_id_owned_by_the_correct_case(trim($_GET['ref_no']), trim(urlencode($_GET['note_id'])))){
									$note_param = array("note_id", "note", "added_date", "username", "modified_by", "modified_date");
									$note_full_details = $caseModel->retrieve_full_details_of_selected_note($_GET['note_id'], $note_param);				
								}else{
									AppController::redirect_to($site_config['base_url'] ."case/view/");	
								}	
							}else{
								AppController::redirect_to($site_config['base_url'] ."case/view/");																						
							}	
						}	
					break;										
				}
				// Check whether the case id is exist in the database		
				if (
					($caseModel->check_case_id_exist(trim(urlencode($_GET['ref_no'])))) &&
					($caseModel->check_case_id_can_be_visble_to_requested_user_by_country($_SESSION['logged_user']['countries'], trim(urlencode($_GET['ref_no'])))) &&
					($caseModel->check_case_country_with_curr_selected_country($_SESSION['curr_country']['country_code'], trim(urlencode($_GET['ref_no']))))
				   )				   
					{
					$full_details = $caseModel->retrieve_full_details_per_each_case(trim(urlencode($_GET['ref_no'])));	
					$case_id = urlencode($full_details[0]['reference_number']);
					// Start to validate and other main functions				
					if ("POST" == $_SERVER['REQUEST_METHOD']){
						$errors = AppModel::validate($_POST['case_main_reqired']);
						$_SESSION['case_main_reqired'] = $_POST['case_main_reqired'];
						$_SESSION['case_main_non_reqired'] = $_POST['case_main_non_reqired'];
						$_SESSION['case_status'] = $_POST['case_status'];					
						// Data Grabbing and validating for the required in contact view										
						if ((!empty($_POST['case_main_open_non_reqired']['month'])) || (!empty($_POST['case_main_open_non_reqired']['day'])) || (!empty($_POST['case_main_open_non_reqired']['year']))){
							$_SESSION['case_main_open_non_reqired'] = $_POST['case_main_open_non_reqired'];					
							// Check data is valid
							if (!@checkdate($_POST['case_main_open_non_reqired']['month'], $_POST['case_main_open_non_reqired']['day'], $_POST['case_main_open_non_reqired']['year'])){
								$_SESSION['date_input_error'] = true;
							}else{
								$_SESSION['date_input_error'] = false;							
							}
						}elseif (
								(empty($_POST['case_main_open_non_reqired']['month'])) && 
								(empty($_POST['case_main_open_non_reqired']['day'])) && 
								(empty($_POST['case_main_open_non_reqired']['year']))								
								){
									unset($_SESSION['case_main_open_non_reqired']);
									$_SESSION['date_input_error'] = false;															
						}
						// Data Grabbing and validating for the required in contact view										
						if ((!empty($_POST['case_main_upcoming_non_reqired']['month'])) || (!empty($_POST['case_main_upcoming_non_reqired']['day'])) || (!empty($_POST['case_main_upcoming_non_reqired']['year']))){					
							$_SESSION['case_main_upcoming_non_reqired'] = $_POST['case_main_upcoming_non_reqired'];					
							// Check data is valid
							if (!@checkdate($_POST['case_main_upcoming_non_reqired']['month'], $_POST['case_main_upcoming_non_reqired']['day'], $_POST['case_main_upcoming_non_reqired']['year'])){
								$_SESSION['date_input_error_2'] = true;
							}else{
								$_SESSION['date_input_error_2'] = false;							
							}
						}elseif (
								(empty($_POST['case_main_upcoming_non_reqired']['month'])) && 
								(empty($_POST['case_main_upcoming_non_reqired']['day'])) && 
								(empty($_POST['case_main_upcoming_non_reqired']['year']))													
								){
									unset($_SESSION['case_main_upcoming_non_reqired']);
									$_SESSION['date_input_error_2'] = false;															
						}
						// Data Grabbing and validating for the required in contact view										
						if ((!empty($_POST['case_main_close_non_reqired']['month'])) || (!empty($_POST['case_main_close_non_reqired']['day'])) || (!empty($_POST['case_main_close_non_reqired']['year']))){										
							$_SESSION['case_main_close_non_reqired'] = $_POST['case_main_close_non_reqired'];					
							// Check data is valid
							if (!@checkdate($_POST['case_main_close_non_reqired']['month'], $_POST['case_main_close_non_reqired']['day'], $_POST['case_main_close_non_reqired']['year'])){
								$_SESSION['date_input_error_3'] = true;
							}else{
								$_SESSION['date_input_error_3'] = false;							
							}
						}
					// If notes form has been submitted
					if (isset($_POST['case_main_submit'])){	
						// If errors found in the adding section
						if ($errors){
							$_SESSION['case_reqired_errors'] = $errors;					
					   		$need_to_be_submit = false;																
							// Display the error message						
							$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Required fields cannot be blank !</div>";
						}elseif ($_SESSION['case_status'] == ""){
					   		$need_to_be_submit = false;															
							$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please select the Case Current Status.</div>";																		
						}elseif ((isset($_SESSION['date_input_error'])) && ($_SESSION['date_input_error'] == 1)){
					   		$need_to_be_submit = false;																					
							$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Invalid date for Case Opened date.</div>";																		
						}elseif ((isset($_SESSION['date_input_error_2'])) && ($_SESSION['date_input_error_2'] == 1)){
					   		$need_to_be_submit = false;																											
							$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Invalid date for Case Review Date.</div>";																		
						}elseif ((isset($_SESSION['date_input_error_3'])) && ($_SESSION['date_input_error_3'] == 1)){
					   		$need_to_be_submit = false;																											
							$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Invalid date for Case Closed date.</div>";																		
						}elseif ($_SESSION['case_status'] == "Closed"){
							if (
							    (empty($_POST['case_main_close_non_reqired']['month'])) && 
								(empty($_POST['case_main_close_non_reqired']['day'])) && 
								(empty($_POST['case_main_close_non_reqired']['year']))
							   ){
							   		$need_to_be_submit = false;																												   		
									$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please select the Case Closed date.</div>";																											   		
							   }elseif (empty($_SESSION['case_main_non_reqired']['reasone_for_close'])){
					   				$need_to_be_submit = false;																												   
									$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please prvoide the reason for the case to be closed.</div>";																											   									   	
							   }else{
					   				$need_to_be_submit = true;																												   	
							   }
						}else{					
				   				$need_to_be_submit = true;																												   							
						}
						
						if ($need_to_be_submit){
						
							if (($_SESSION['case_status'] == "Active") || ($_SESSION['case_status'] == "Inactive")){
								unset($_SESSION['case_main_non_reqired']['reasone_for_close']);
								unset($_SESSION['case_main_close_non_reqired']);						
							}	
							$case_dates = array(
												"case_open_date" => ((isset($_POST['case_main_open_non_reqired'])) ? $_POST['case_main_open_non_reqired'] : ""),
												"case_upcoming_date" => ((isset($_POST['case_main_upcoming_non_reqired'])) ? $_POST['case_main_upcoming_non_reqired'] : ""),
												"case_close_date" => ((isset($_POST['case_main_close_non_reqired'])) ? $_POST['case_main_close_non_reqired'] : "")
											   );
							$user_details_param = array(
															"user_id" => $_SESSION['logged_user']['id'],
															"edited_date" => date("Y-m-d-H:i:s")
														);				   
							// If users' owned country count is equal to one then add user's session country else give the user to select one of country from the country list
							$country = (count($_SESSION['logged_user']['countries']) == 1) ? $_SESSION['logged_user']['countries'][0] : $_SESSION['case_main_reqired']['case_owned_country_id'];
														
							// Update case details												   
							$caseModel->update_case_details(array_merge($_SESSION['case_main_reqired'], $_SESSION['case_main_non_reqired']), 
																										$case_dates, $case_id, $user_details_param, $_SESSION['case_status']);
							//if (isset($_SESSION['case_main_non_reqired']['vols_working'])){									
							//}


							if(!isset($_POST['case_main_submit'])){
							
								$ary_for_select_box = array_diff($ary_vols,$ary_assined_vols);
								$ary_for_added_box = array();
								$ary_for_assined_box = $ary_assined_vols;
								//echo count($ary_vols);
							}
							else{//after hit save buttom
								$chk_ary_added = @$_POST['chkad'];
								if(!is_array($chk_ary_added)){$chk_ary_added = array();}
								$ary_for_select_box = @array_diff($ary_vols,$ary_assined_vols,$chk_ary_added);
								$ary_for_added_box = $chk_ary_added;
								$ary_for_assined_box = $ary_assined_vols;
								//////////////////////////////////////////
								$ary_db_ad = @$_POST['chkad'];
								$ary_db_as = @$_POST['chkas'];
								if(!is_array($ary_db_ad)){$ary_db_ad = array();}
								if(!is_array($ary_db_as)){$ary_db_as = array();}
								$ary_for_db = array_unique(array_merge($ary_db_ad,$ary_db_as));
								
								$caseModel->clear_the_current_volunteers_assigned($case_id);
								$caseModel->insert_involved_volunteers($case_id, $ary_for_db);
								
							}
							

							/* This is the start of the templates saving - at the edit part */	
							// Create the template folder
							if(count($_SESSION['case_main']['template_name']) > 0){
								$upload_folder_path = $_SERVER['DOCUMENT_ROOT'].DS."user-uploads/cases";
								$case_folder_path = $upload_folder_path.DS.$case_id;
								if(!is_dir($case_folder_path)){
									mkdir($case_folder_path);
								}					
								// Insert case templates details
								$case_templates_details = array(
																	"name" => $_SESSION['case_main']['template_name'], 
																	"case_id" => $case_id
																);
								$caseModel->insert_case_templates_details($case_templates_details, "edit");
							}	
							/* This is the end of the templates saving */
							
							// Log keeping
							$log_params = array(
												"user_id" => $_SESSION['logged_user']['id'], 
												"action_desc" => "CASE was updated. Reference No. : ".trim(urldecode($case_id)),
												"date_crated" => date("Y-m-d-H:i:s")
												);
							$caseModel->keep_track_of_activity_log_in_case($log_params);
							// Unset all used data
							unset($_SESSION['case_reqired_errors']);						
							unset($_SESSION['case_main_reqired']);
							unset($_SESSION['case_main_non_reqired']);
							unset($_SESSION['case_status']);
							unset($_SESSION['case_main_close_non_reqired']['month']);
							unset($_SESSION['case_main_close_non_reqired']['day']);
							unset($_SESSION['case_main_close_non_reqired']['year']);
							unset($_SESSION['case_main_upcoming_non_reqired']['month']);
							unset($_SESSION['case_main_upcoming_non_reqired']['day']);
							unset($_SESSION['case_main_upcoming_non_reqired']['year']);
							unset($_SESSION['case_main_open_non_reqired']['month']);
							unset($_SESSION['case_main_open_non_reqired']['day']);
							unset($_SESSION['case_main_open_non_reqired']['year']);
							unset($_SESSION['date_input_error']);								
							unset($_SESSION['date_input_error_2']);								
							unset($_SESSION['date_input_error_3']);
							$_SESSION['case_details_updated'] = "true";
							unset($_SESSION['case_main']);							
							unset($caseModel);	
							unset($full_details);								
							AppController::redirect_to($site_config['base_url']."case/edit/?mode=main&ref_no=".urlencode($_GET['ref_no']).((isset($_GET['page'])) ? "&page=".$_GET['page'] : ""));												
						}		
					}
						// If notes form has been submitted
						if (isset($_POST['case_notes_submit'])){
							// If no check boxes have been selected regarding the notes section
							if (empty($_POST['case_note_categories_required'])){
								$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please select at least one category.</div>";		
							// If no value has been submitted regarding the notes section 
							}elseif (empty($_POST['case_note_required']['note_text'])){
								$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please fill the note text box.</div>";		
							// If values has been submitted	
							}else{
								$_SESSION['case_note_required']['note_text'] = $_POST['case_note_required']['note_text'];							
								$notes_inputting_array = array(
																"case_id" => $case_id,
																"note" => $_SESSION['case_note_required']['note_text'],
																"added_by" => $_SESSION['logged_user']['id'],
																"added_date" => date("Y-m-d-H:i:s")
															  );
								$last_inserted_note_id = $caseModel->insert_new_note($notes_inputting_array);
								// Inserting the notes related categories
								$notes_categories_params = array(
																	"note_id" => $last_inserted_note_id,
																	"notes_categories" => $_POST['case_note_categories_required'],
																	"note_owner_section" => "CASE"
																);	
								$caseModel->insert_notes_categories($notes_categories_params);
								$_SESSION['new_note_has_created'] = "true";		
								// Log keeping
								$log_params = array(
													"user_id" => $_SESSION['logged_user']['id'], 
													"action_desc" => "Created a new note to CASE ID ".trim(urlencode($case_id)),
													"date_crated" => date("Y-m-d-H:i:s")
													);
								$caseModel->keep_track_of_activity_log_in_case($log_params);
								$notes_array = array('note_id', 'note', 'date_modified', 'username', 'added_date', 'modified_date');						
								$all_notes_to_this_client = $caseModel->retrieve_all_notes_owned_by_this_client($case_id, $notes_array);																																																	
								unset($_POST);									
								unset($_SESSION['case_note_required']['note_text']);
								AppController::redirect_to($site_config['base_url']."case/edit/?mode=notes&ref_no=".$case_id.((isset($_GET['page'])) ? "&page=".$_GET['page'] : "").((isset($_GET['notes_page'])) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1"));							
							}
							$notes_array = array('note_id', 'note', 'date_modified', 'username', 'added_date', 'modified_date');						
							$all_notes_to_this_client = $caseModel->retrieve_all_notes_owned_by_this_client($case_id, $notes_array);
						}
						// If notes form has been submitted in edit note section
						if (isset($_POST['case_notes_update_submit'])){
							// Check the note id exist in the db
							if ($caseModel->check_note_id_exist(trim($_GET['note_id']))){
								// Check the note is owned by him self
								if ($caseModel->check_note_id_owned_by_the_correct_case(trim(urlencode($_GET['ref_no'])), trim($_GET['note_id']))){								
									// If no check boxes have been selected regarding the notes section
									if (empty($_POST['case_note_categories_required'])){
										$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please select at least one category.</div>";		
									// If no value has been submitted regarding the notes section
									}elseif (empty($_POST['case_note_required']['note_text'])){
										$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please fill the note text box.</div>";		
									// If both values has been submitted	
									}else{
										$_SESSION['case_note_required']['note_text'] = $_POST['case_note_required']['note_text'];							
										$notes_inputting_array = array(
															"case_id" => $case_id,													
															"note" => $_SESSION['case_note_required']['note_text'],
															"modified_date" => date("Y-m-d-H:i:s"),
															"modified_by" => $_SESSION['logged_user']['id']
															);
										$caseModel->update_the_exsiting_note($notes_inputting_array, trim($_GET['note_id']));
										// Remove previous notes categories relation
										$caseModel->remove_previous_notes_categories_relation(trim($_GET['note_id']));										
										// Inserting the notes related categories
										$notes_categories_params = array(
																			"note_id" => trim($_GET['note_id']),
																			"notes_categories" => $_POST['case_note_categories_required'],
																			"note_owner_section" => "CASE"
																		);	
										$caseModel->insert_notes_categories($notes_categories_params);
										// Log keeping
										$log_params = array(
															"user_id" => $_SESSION['logged_user']['id'], 
															"action_desc" => "Updated a note of CASE ID ".trim(urlencode($case_id))."'",
															"date_crated" => date("Y-m-d-H:i:s")
															);
										$caseModel->keep_track_of_activity_log_in_case($log_params);
										$notes_array = array('note_id', 'note', 'username', 'added_date', 'modified_date');						
										$note_full_details = $caseModel->retrieve_full_details_of_selected_note($_GET['note_id'], $note_param);				
										unset($_POST);
										unset($_SESSION['case_note_required']['note_text']);
										$_SESSION['notes_section_got_updated'] = "true";
										AppController::redirect_to($site_config['base_url']."case/edit/?mode=notes&ref_no=".$case_id.((isset($_GET['page'])) ? "&page=".$_GET['page'] : "").((isset($_GET['notes_page'])) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1"));												
									}
								// If note id is not owned by him self																				
								}else{
									unset($_SESSION['pfac_note_required']['note_text']);
									AppController::redirect_to($site_config['base_url'] ."case/view/");	
								}
							// If wrong note id										
							}else{
								unset($_SESSION['pfac_note_required']['note_text']);
								AppController::redirect_to($site_config['base_url'] ."case/view/");	
							}	
						}
					}else{
						unset($_SESSION['case_reqired_errors']);						
						unset($_SESSION['case_main_reqired']);
						unset($_SESSION['case_main_non_reqired']);
						unset($_SESSION['case_status']);
						unset($_SESSION['case_main_close_non_reqired']['month']);
						unset($_SESSION['case_main_close_non_reqired']['day']);
						unset($_SESSION['case_main_close_non_reqired']['year']);
						unset($_SESSION['case_main_upcoming_non_reqired']['month']);
						unset($_SESSION['case_main_upcoming_non_reqired']['day']);
						unset($_SESSION['case_main_upcoming_non_reqired']['year']);
						unset($_SESSION['case_main_open_non_reqired']['month']);
						unset($_SESSION['case_main_open_non_reqired']['day']);
						unset($_SESSION['case_main_open_non_reqired']['year']);
						unset($_SESSION['date_input_error']);								
						unset($_SESSION['date_input_error_2']);								
						unset($_SESSION['date_input_error_3']);
						unset($caseModel);	
						unset($full_details);
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
				global $caseModel;				
				global $vols_assigned;
				global $clients_assigned;
				global $cp_assigned;
				$breadcrumb = "";
				// objects instantiation
				$caseModel = new CaseModel(); 
				// Bredcrmb to the pfa section
				$breadcrumb .= "<div class=\"breadcrumbMessageDiv defaultFont boldText\"><a href=\"".$site_config['base_url']
								."\" class=\"headerLink\">Home</a> &rsaquo; "."<a class=\"headerLink\" href=\"".$site_config['base_url']."case/view/\">All Cases</a> &rsaquo; View Case</div>";
				// Check whether the CASE id is exist in the database		
				if (
					($caseModel->check_case_id_exist(trim(urlencode($_GET['ref_no'])))) &&
				    ($caseModel->check_case_id_can_be_visble_to_requested_user_by_country($_SESSION['logged_user']['countries'], trim(urlencode($_GET['ref_no'])))) &&
					($caseModel->check_case_country_with_curr_selected_country($_SESSION['curr_country']['country_code'], trim($_GET['ref_no'])))					
				   )
				{
					$valid_access = true;
				}else{
					$valid_access = false;
				}
				if ($valid_access){
					$fullDetails = $caseModel->grab_full_details_for_the_single_view_in_case(trim(urlencode($_GET['ref_no'])));		
					$vols_assigned = $caseModel->retrieve_volunteers_for_the_given_case(trim(urlencode($_GET['ref_no'])));
					$clients_assigned = $caseModel->retrieve_clients_for_the_given_case(trim(urlencode($_GET['ref_no'])));
					$cp_assigned = $caseModel->retrieve_cp_for_the_given_case(trim(urlencode($_GET['ref_no'])));
					// Log keeping
					$log_params = array(
										"user_id" => $_SESSION['logged_user']['id'], 
										"action_desc" => "Viewed details of CASE Reference : ".trim(urldecode($_GET['ref_no'])),
										"date_crated" => date("Y-m-d-H:i:s")
										);
					$caseModel->keep_track_of_activity_log_in_case($log_params);
				}else{
					AppController::redirect_to($site_config['base_url'] ."case/view/");												
				}	
				unset($caseModel);
				unset($fullDetails);
				unset($vols_assigned);
			break;						

			case "delete":
				// All global variables		
				global $site_config;
				$case_folder_path = "";								
				// objects instantiation
				$caseModel = new CaseModel();
				// Check whether the user id is exist in the database						
				if (
					(!$caseModel->check_case_id_exist(trim(urlencode($_GET['ref_no'])))) &&
					(!$caseModel->check_case_id_can_be_visble_to_requested_user_by_country($_SESSION['logged_user']['countries'], trim(urlencode($_GET['ref_no']))))					
				   )
					{
					$_SESSION['is_not_exist_case_id'] = "true";				
					$rest_of_deletion = false;					
				// Check whether the case have been assigned by any volunteers
				}elseif ($caseModel->is_any_volunteers_invloved(trim(urlencode($_GET['ref_no'])))){
					$caseModel->delete_volunteer_details_from_case_volunteers(trim(urlencode($_GET['ref_no'])));
					$rest_of_deletion = true;					
				// If no volunteers assigned
				}else{
					$rest_of_deletion = true;
				}	
				
				if ($rest_of_deletion){

					// Deleting the counter-partys notes
					$caseModel->delete_owned_notes(trim(urlencode($_GET['ref_no'])));					
					// Deleting the clients notes
					$caseModel->delete_selected_case(trim(urlencode($_GET['ref_no'])));
					// Delete the case templates names
					$caseModel->delete_case_templates_names(trim(urlencode($_GET['ref_no'])));
					// Delete involved clients
					$caseModel->delete_involved_clients_with_cases(trim(urlencode($_GET['ref_no'])));
					// Delete involved counter parties
					$caseModel->delete_involved_counter_parties_with_cases(trim(urlencode($_GET['ref_no'])));
					// Remove case templates
					$case_folder_path = SERVER_ROOT.DS.'user-uploads'.DS.'cases'.DS.trim($_GET['ref_no']);
					if(is_dir($case_folder_path)) { CommonFunctions::delete_entries($case_folder_path); }
					// Log keeping
					$log_params = array(
										"user_id" => $_SESSION['logged_user']['id'], 
										"action_desc" => "CASE deleted. ID : ".trim(urlencode($_GET['ref_no'])),
										"date_crated" => date("Y-m-d-H:i:s")
										);
					$caseModel->keep_track_of_activity_log_in_case($log_params);
					$_SESSION['deleted_case_id'] = "true";
				}
				// Unset all used variables	
				unset($caseModel);
				AppController::redirect_to($site_config['base_url'] ."case/view/");																										
			break;
			
			case "notes":
			
				// All Global variables
				global $headerDivMsg;
				global $site_config;
				global $breadcrumb;
				global $printHtml;
				global $all_notes_to_this_client;
				global $all_notes_to_this_client_in_edit;
				global $all_notes_count_to_this_client_in_edit;
				global $case_id;
				global $note_full_details;
				global $pagination;
				global $tot_page_count;
				global $img;
				global $caseModel;
				global $case_details;
				$breadcrumb = "";
				// Object Instantiation
				$caseModel = new CaseModel(); 				
				$case_id = trim(urlencode($_GET['ref_no']));
				// Check the case owned is equal to the  selected country
				if ($caseModel->check_case_country_with_curr_selected_country($_SESSION['curr_country']['country_code'], $case_id)){
				// Bredcrmb to the pfa section								
				$breadcrumb .= "<div class=\"breadcrumbMessageDiv defaultFont boldText\"><a href=\"".$site_config['base_url']
								."\" class=\"headerLink\">Home</a> ";
				if (isset($_GET['opt'])){
					$breadcrumb .= "&rsaquo; <a class=\"headerLink\" href=\"".$site_config['base_url']."case/view/\">All Cases</a> &rsaquo; <a class=\"headerLink\" href=\"".
										$site_config['base_url']."case/edit/?mode=main&ref_no=".urlencode($case_id)."&page=1"."\">Edit Case</a> &rsaquo; ".
										"<a class=\"headerLink\" href=\"".$site_config['base_url']."case/notes/?mode=edit-notes&ref_no=".urlencode($case_id)."&page=1&notes_page=1"."\">Your all notes</a>";
					if ($_GET['opt'] == "view"){
						$breadcrumb	.= "&rsaquo; View single note";				
					}else{
						$breadcrumb	.= "&rsaquo; Edit single note";				
					}										
				}else{
					$breadcrumb .= "&rsaquo; <a class=\"headerLink\" href=\"".$site_config['base_url']."case/view/\">All Cases</a> &rsaquo; Edit Case";					
				}
				$breadcrumb .= "</div>";												
				// If the correct post back not submitted then redirect the user to the correct page
				if (($mode == "add-notes") && (!isset($_SESSION['newly_inserted_case_id']))){
					AppController::redirect_to($site_config['base_url'] ."case/add/?mode=main");
				}

				// Generate the top header menu
				if ($_GET['mode'] == "edit-notes"){
					$printHtml = "";
					if (in_array("3", $_SESSION['logged_user']['permissions'])){
						$printHtml .= ((isset($_GET['mode'])) && ($_GET['mode'] == "main")) ? "<span class=\"headerTopicSelected\">Main Details</span>" : "<span><a href=\"".$site_config['base_url']."case/edit/?mode=main&ref_no=".trim(urlencode($_GET['ref_no'])).((isset($_GET['page'])) ? "&page=".$_GET['page'] : "")."\">Main Details</a></span>";					
						$printHtml .= "&nbsp;&nbsp;<span class=\"ar\">&rsaquo;</span>&nbsp;&nbsp;";
					}
					$printHtml .= ((isset($_GET['mode'])) && ($_GET['mode'] == "edit-notes")) ? "<span class=\"headerTopicSelected\">Notes</span>" : "<span><a href=\"".$site_config['base_url']."case/notes/?mode=edit-notes&ref_no=".trim(urlencode($_GET['ref_no'])).((isset($_GET['page'])) ? "&page=".$_GET['page'] : "").((isset($_GET['notes_page'])) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\">Notes</a></span>";					
				}else{
					$printHtml = "Notes";
				}					
				// Switch to the correct sub view
				switch($mode){
					
					case "add-notes":
						$whichFormToInclude = "notes"; 						
						// Display the success message in new contact adding
						if ($_SESSION['new_case_added'] == "true"){					
							$headerDivMsg = "<div class=\"headerMessageDivInSuccess defaultFont\">New case was added successfully.&nbsp;&nbsp;<a class='edtingMenusLink_in_view' href='".
									$site_config['base_url']."case/show/?ref_no=".urlencode($_SESSION['newly_inserted_case_id'])."'>View details.</a></div>";																																			
						}
						unset($_SESSION['new_case_added']);	
						// Display the success message in new note adding in the last inserted contact	
						if ($_SESSION['new_note_has_created'] == "true"){					
							$headerDivMsg = "<div class=\"headerMessageDivInSuccess defaultFont\">New note has been created.</div>";																																			
						}
						unset($_SESSION['new_note_has_created']);	
						// Display the notice message after deletion ogf the selected note
						if ($_SESSION['deleted_selected_note'] == "true"){					
							$headerDivMsg = "<div class=\"headerMessageDivInNotice defaultFont\">Deleted the selected note.</div>";																																			
						}
						unset($_SESSION['deleted_selected_note']);							
						$case_id = trim(urlencode($_SESSION['newly_inserted_case_id']));
						// Load last inserted notes by last inserted user
						$notes_array = array('note_id', 'note');					
						$all_notes_to_this_client = $caseModel->retrieve_all_notes_owned_by_this_client(trim(urlencode($_SESSION['newly_inserted_case_id'])), $notes_array);
						// Delete the selected note	
						if ((isset($_GET['opt'])) && ($_GET['opt'] == "drop")){
							$caseModel->delete_selected_note(trim($_GET['note_id']), $case_id);
							$_SESSION['deleted_selected_note'] = "true";
							// Log keeping
							$log_params = array(
												"user_id" => $_SESSION['logged_user']['id'], 
												"action_desc" => "Deleted a note of CASE ID '".trim(urlencode($case_id))."'",
												"date_crated" => date("Y-m-d-H:i:s")
												);
							$caseModel->keep_track_of_activity_log_in_case($log_params);
							AppController::redirect_to($site_config['base_url']."case/notes/?mode=add-notes");
						}
						// Delete the selected note	
						if ((isset($_GET['opt'])) && ($_GET['opt'] == "drop")){
							$caseModel->delete_selected_note($_GET['note_id'], $case_id);
							// Delete notes category relation as well regarding the note deletion
							$caseModel->remove_previous_notes_categories_relation(trim($_GET['note_id']));							
							$_SESSION['deleted_selected_note'] = "true";
							// Log keeping
							$log_params = array(
												"user_id" => $_SESSION['logged_user']['id'], 
												"action_desc" => "Deleted the note of CASE ID {$case_id}",
												"date_crated" => date("Y-m-d-H:i:s")
												);
							$caseModel->keep_track_of_activity_log_in_case($log_params);
							AppController::redirect_to($site_config['base_url']."case/notes/?mode=add-notes");							
						}
						// Start to validate and other main functions				
						if ("POST" == $_SERVER['REQUEST_METHOD']){
							// If notes form has been submitted
							if (isset($_POST['case_notes_submit'])){
								// If no check boxes have been selected regarding the notes section
								if (empty($_POST['case_note_categories_required'])){
									$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please select at least one category.</div>";		
								// If no value has been submitted regarding the notes section 
								}elseif (empty($_POST['case_note_required']['note_text'])){
									$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please fill the note text box.</div>";		
								// If values has been submitted	
								}else{
									$_SESSION['case_note_required']['note_text'] = $_POST['case_note_required']['note_text'];							
									$notes_inputting_array = array(
																	"case_id" => $case_id,
																	"note" => $_SESSION['case_note_required']['note_text'],
																	"added_by" => $_SESSION['logged_user']['id'],
																	"added_date" => date("Y-m-d-H:i:s")
																  );
									$last_inserted_note_id = $caseModel->insert_new_note($notes_inputting_array);
									// Inserting the notes related categories
									$notes_categories_params = array(
																		"note_id" => $last_inserted_note_id,
																		"notes_categories" => $_POST['case_note_categories_required'],
																		"note_owner_section" => "CASE"
																	);	
									$caseModel->insert_notes_categories($notes_categories_params);
									$_SESSION['new_note_has_created'] = "true";		
									// Log keeping
									$log_params = array(
														"user_id" => $_SESSION['logged_user']['id'], 
														"action_desc" => "Created a new note to CASE ID ".trim(urlencode($case_id)),
														"date_crated" => date("Y-m-d-H:i:s")
														);
									$caseModel->keep_track_of_activity_log_in_case($log_params);
									$notes_array = array('note_id', 'note', 'date_modified', 'username', 'added_date', 'modified_date');						
									$all_notes_to_this_client = $caseModel->retrieve_all_notes_owned_by_this_client($case_id, $notes_array);																																																	
									unset($_POST);									
									unset($_SESSION['case_note_required']['note_text']);
									AppController::redirect_to($site_config['base_url']."case/notes/?mode=add-notes");							
								}
								$notes_array = array('note_id', 'note', 'date_modified', 'username', 'added_date', 'modified_date');						
								$all_notes_to_this_client = $caseModel->retrieve_all_notes_owned_by_this_client($case_id, $notes_array);
							}
						}	
					break;										
					case "edit-notes":						 
						$whichFormToInclude = "notes"; 
						$pagination_obj = new Pagination();
						// Display the success message in new note adding
						if ($_SESSION['new_note_has_created'] == "true"){					
							$headerDivMsg = "<div class=\"headerMessageDivInSuccess defaultFont\">New note has been created.</div>";																																			
						}
						unset($_SESSION['new_note_has_created']);	
						// Display the notice message after deletion ogf the selected note
						if ($_SESSION['deleted_selected_note'] == "true"){					
							$headerDivMsg = "<div class=\"headerMessageDivInNotice defaultFont\">Deleted the selected note.</div>";																																			
						}
						unset($_SESSION['deleted_selected_note']);							
						// Display the success message after succesful updation of contact details						
						if ($_SESSION['notes_section_got_updated'] == "true"){
							$headerDivMsg = "<div class=\"headerMessageDivInSuccess defaultFont\">The above note was updated successfully.</div>";																																			
						}					
						unset($_SESSION['notes_section_got_updated']);
						// Sorting of the notes section
						if (!isset($_GET['notes_page'])){ 
							$cur_path = "?".(isset($_GET['sort'])) ? "sort=".@$_GET['sort'].((isset($_GET['by'])) ? "&by=".$_GET['by'] : "") : ""; 
						}else{ 
							$cur_path = $_SERVER['REQUEST_URI']; 						
						}
						// Load all notes regarding to this client						
						$notes_array = array('note_id', 'note', 'username', 'added_date', 'modified_date', 'modified_by', "");						
						// Grab the current page
						$cur_page = ((isset($_GET['notes_page'])) && ($_GET['notes_page'] != "") && ($_GET['notes_page'] != 0)) ? $_GET['notes_page'] : 1; 												
						// Grab the pfac id
						if (isset($_GET['sort'])){	

							$imgDefault = "<a href=\"".$site_config['base_url']."case/notes/?mode=edit-notes&ref_no=".$case_id."&opt=sorting&sort=".$_GET['sort']."&by=asc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\"><img src=\"../../public/images/s_asc.png\" border=\"0\" /></a>";			
							$imgAsc = "<a href=\"".$site_config['base_url']."case/edit/?mode=edit-notes&ref_no=".$case_id."&opt=sorting&sort=".$_GET['sort']."&by=asc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\"><img src=\"../../public/images/s_asc.png\" border=\"0\" /></a>";			
							$imgDesc = "<a href=\"".$site_config['base_url']."case/edit/?mode=edit-notes&ref_no=".$case_id."&opt=sorting&sort=".$_GET['sort']."&by=desc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\"><img src=\"../../public/images/s_desc.png\" border=\"0\" /></a>";								
							$img = (!isset($_GET['by'])) ? ($imgDefault) : (($_GET['by'] == "asc") ? $imgDesc : $imgAsc);
		
							$notes_array = array('note_id' => 'note_id', 'note' => 'note', 'add_by' => 'username', 'mod_date' => 'modified_date', 'mod_by' => 'modified_by', 'add_date' => 'added_date');						
							foreach($notes_array as $key => $value) {
								if ($key == $_GET['sort']) {
									$sortBy = $value;
								}
							}
						}
						// If the notes filtering button has been clicked
						if (isset($_POST['notes_filter'])){
							// If notes categories are empty then display the error message
							if (empty($_POST['case_note_categories_required'])){
								$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please select at least one category to filter.</div>";										
							}else{
								$filtering = true;
							}
						}
						// retrieve all notes and all notes couunt to this client
						$all_notes_to_this_client_in_edit = $caseModel->retrieve_all_notes_owned_by_this_client_only_for_the_edit_view($notes_array, $cur_page, $sortBy, ((isset($_GET['by'])) ? $_GET['by'] : ""), trim(urlencode($_GET['ref_no'])), $filtering, $_POST['case_note_categories_required']);
						$all_notes_count_to_this_client_in_edit = $caseModel->retrieve_all_notes_count_owned_by_this_client_only_for_the_edit_view(trim(urlencode($_GET['ref_no'])), $notes_array, $filtering, $_POST['case_note_categories_required']);
						$pagination = $pagination_obj->generate_pagination($all_notes_count_to_this_client_in_edit, $cur_path, $cur_page, NO_OF_RECORDS_PER_PAGE_DEFAULT);				
						$tot_page_count = ceil($all_notes_count_to_this_client_in_edit/NO_OF_RECORDS_PER_PAGE_DEFAULT);				
						// Delete the selected note	
						if ((isset($_GET['opt'])) && ($_GET['opt'] == "drop")){
							$caseModel->delete_selected_note($_GET['note_id'], $case_id);
							// Delete notes category relation as well regarding the note deletion
							$caseModel->remove_previous_notes_categories_relation(trim($_GET['note_id']));							
							$_SESSION['deleted_selected_note'] = "true";
							// Log keeping
							$log_params = array(
												"user_id" => $_SESSION['logged_user']['id'], 
												"action_desc" => "Deleted the note of CASE ID {$case_id}",
												"date_crated" => date("Y-m-d-H:i:s")
												);
							$caseModel->keep_track_of_activity_log_in_case($log_params);
							AppController::redirect_to($site_config['base_url']."case/notes/?mode=edit-notes&ref_no=".$case_id.((isset($_GET['page'])) ? "&page=".$_GET['page'] : "").((isset($_GET['notes_page'])) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1"));							
						}
						// Show full details of the above client selected note
						if ((isset($_GET['opt'])) && (($_GET['opt'] == "view") || ($_GET['opt'] == "edit"))){						
							// Check the note id exist in the db
							if ($caseModel->check_note_id_exist(trim($_GET['note_id']))){						
								if ($caseModel->check_note_id_owned_by_the_correct_case(trim($_GET['ref_no']), trim(urlencode($_GET['note_id'])))){
									$note_param = array("note_id", "note", "added_date", "username", "modified_by", "modified_date");
									$note_full_details = $caseModel->retrieve_full_details_of_selected_note($_GET['note_id'], $note_param);				
								}else{
									AppController::redirect_to($site_config['base_url'] ."case/view/");	
								}	
							}else{
								AppController::redirect_to($site_config['base_url'] ."case/view/");																						
							}	
						}
						// Check whether the case id is exist in the database		
						if (
							($caseModel->check_case_id_exist(trim(urlencode($_GET['ref_no'])))) &&
							($caseModel->check_case_id_can_be_visble_to_requested_user_by_country($_SESSION['logged_user']['countries'], trim(urlencode($_GET['ref_no']))))
						   )
							{
							$full_details = $caseModel->retrieve_full_details_per_each_case(trim(urlencode($_GET['ref_no'])));	
							$case_id = urlencode($full_details[0]['reference_number']);
							// Start to validate and other main functions				
							if ("POST" == $_SERVER['REQUEST_METHOD']){
								// If notes form has been submitted
								if (isset($_POST['case_notes_submit'])){
									// If no check boxes have been selected regarding the notes section
									if (empty($_POST['case_note_categories_required'])){
										$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please select at least one category.</div>";		
									// If no value has been submitted regarding the notes section 
									}elseif (empty($_POST['case_note_required']['note_text'])){
										$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please fill the note text box.</div>";		
									// If values has been submitted	
									}else{
										$_SESSION['case_note_required']['note_text'] = $_POST['case_note_required']['note_text'];							
										$notes_inputting_array = array(
																		"case_id" => $case_id,
																		"note" => $_SESSION['case_note_required']['note_text'],
																		"added_by" => $_SESSION['logged_user']['id'],
																		"added_date" => date("Y-m-d-H:i:s")
																	  );
										$last_inserted_note_id = $caseModel->insert_new_note($notes_inputting_array);
										// Inserting the notes related categories
										$notes_categories_params = array(
																			"note_id" => $last_inserted_note_id,
																			"notes_categories" => $_POST['case_note_categories_required'],
																			"note_owner_section" => "CASE"
																		);	
										$caseModel->insert_notes_categories($notes_categories_params);
										$_SESSION['new_note_has_created'] = "true";		
										// Log keeping
										$log_params = array(
															"user_id" => $_SESSION['logged_user']['id'], 
															"action_desc" => "Created a new note to CASE ID ".trim(urlencode($case_id)),
															"date_crated" => date("Y-m-d-H:i:s")
															);
										$caseModel->keep_track_of_activity_log_in_case($log_params);
										$notes_array = array('note_id', 'note', 'date_modified', 'username', 'added_date', 'modified_date');						
										$all_notes_to_this_client = $caseModel->retrieve_all_notes_owned_by_this_client($case_id, $notes_array);																																																	
										unset($_POST);									
										unset($_SESSION['case_note_required']['note_text']);
										AppController::redirect_to($site_config['base_url']."case/notes/?mode=edit-notes&ref_no=".$case_id.((isset($_GET['page'])) ? "&page=".$_GET['page'] : "").((isset($_GET['notes_page'])) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1"));							
									}
									$notes_array = array('note_id', 'note', 'date_modified', 'username', 'added_date', 'modified_date');						
									$all_notes_to_this_client = $caseModel->retrieve_all_notes_owned_by_this_client($case_id, $notes_array);
								}
								// If notes form has been submitted in edit note section
								if (isset($_POST['case_notes_update_submit'])){
									// Check the note id exist in the db
									if ($caseModel->check_note_id_exist(trim($_GET['note_id']))){
										// Check the note is owned by him self
										if ($caseModel->check_note_id_owned_by_the_correct_case(trim(urlencode($_GET['ref_no'])), trim($_GET['note_id']))){								
											// If no check boxes have been selected regarding the notes section
											if (empty($_POST['case_note_categories_required'])){
												$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please select at least one category.</div>";		
											// If no value has been submitted regarding the notes section
											}elseif (empty($_POST['case_note_required']['note_text'])){
												$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please fill the note text box.</div>";		
											// If both values has been submitted	
											}else{
												$_SESSION['case_note_required']['note_text'] = $_POST['case_note_required']['note_text'];							
												$notes_inputting_array = array(
																	"case_id" => $case_id,													
																	"note" => $_SESSION['case_note_required']['note_text'],
																	"modified_date" => date("Y-m-d-H:i:s"),
																	"modified_by" => $_SESSION['logged_user']['id']
																	);
												$caseModel->update_the_exsiting_note($notes_inputting_array, trim($_GET['note_id']));
												// Remove previous notes categories relation
												$caseModel->remove_previous_notes_categories_relation(trim($_GET['note_id']));										
												// Inserting the notes related categories
												$notes_categories_params = array(
																					"note_id" => trim($_GET['note_id']),
																					"notes_categories" => $_POST['case_note_categories_required'],
																					"note_owner_section" => "CASE"
																				);	
												$caseModel->insert_notes_categories($notes_categories_params);
												// Log keeping
												$log_params = array(
																	"user_id" => $_SESSION['logged_user']['id'], 
																	"action_desc" => "Updated a note of CASE ID ".trim(urlencode($case_id))."'",
																	"date_crated" => date("Y-m-d-H:i:s")
																	);
												$caseModel->keep_track_of_activity_log_in_case($log_params);
												$notes_array = array('note_id', 'note', 'username', 'added_date', 'modified_date');						
												$note_full_details = $caseModel->retrieve_full_details_of_selected_note($_GET['note_id'], $note_param);				
												unset($_POST);
												unset($_SESSION['case_note_required']['note_text']);
												$_SESSION['notes_section_got_updated'] = "true";
												AppController::redirect_to($site_config['base_url']."case/notes/?mode=edit-notes&ref_no=".$case_id.((isset($_GET['page'])) ? "&page=".$_GET['page'] : "").((isset($_GET['notes_page'])) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1"));												
											}
										// If note id is not owned by him self																				
										}else{
											unset($_SESSION['pfac_note_required']['note_text']);
											AppController::redirect_to($site_config['base_url'] ."case/view/");	
										}
									// If wrong note id										
									}else{
										unset($_SESSION['pfac_note_required']['note_text']);
										AppController::redirect_to($site_config['base_url'] ."case/view/");	
									}	
								}
							}else{
								unset($caseModel);	
								unset($full_details);
							}
						// If wrong id	
						}else{
							AppController::redirect_to($site_config['base_url'] ."case/view/");
						}
					break;										
				}
				}else{
					AppController::redirect_to($site_config['base_url'] ."case/view/");					
				}
			break;
		}			
	}
}
?>