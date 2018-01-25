<?php

class ClientController extends AppController{	

	/* This function will check the correct sub view provided else redirect to the home page */
	function correct_sub_view_gate_keeper($subView){
	
		if (isset($subView)){
			$modes_array = array("main", "edit-notes", "add-notes");
			if (!in_array($subView, $modes_array)){
				global $site_config;
				AppController::redirect_to($site_config['base_url']."client/view/");
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
				global $all_notes_to_this_client;				
				global $headerDivMsg;
				global $site_config;
				global $printHtml;
				global $breadcrumb;
				global $martial_status;
				global $allTitles;
				global $countryListArray;
				global $all_countries;
				$breadcrumb = "";
				// Generate the top header menu
				$printHtml = "";
				$printHtml .= ((isset($_GET['mode'])) && ($_GET['mode'] == "main")) ? "<span class=\"specializedTexts\">Main Details</span>" : "<span class=\"disabledHrefLinks\">Main Details</span>";					
				$printHtml .= "&nbsp;&nbsp;<span class=\"ar\">&rsaquo;</span>&nbsp;&nbsp;";
				$printHtml .= ((isset($_GET['mode'])) && ($_GET['mode'] == "notes")) ? "<span class=\"specializedTexts\">Notes</span>" : "<span class=\"disabledHrefLinks\">Notes</span>";					
				// Bredcrmb to the pfa section
				$breadcrumb .= "<div class=\"breadcrumbMessageDiv defaultFont boldText\"><a href=\"".$site_config['base_url']."\" class=\"headerLink\">Home</a> &rsaquo; Add New Client</div>";
				// Object Instantiation
				$clientModel = new ClientModel(); 
				//$countryListArray = CommonFunctions::retrieve_country_list();				
				$countryListArray = CommonFunctions::retrieve_country_list();
				$allTitles = CommonFunctions::retrieve_titles_list();
				$martial_status = CommonFunctions::retrieve_martial_status_list();
				// If the correct post back not submitted then redirect the user to the correct page
				if (($mode == "add-notes") && (!isset($_SESSION['newly_inserted_client_id']))){
					AppController::redirect_to($site_config['base_url'] ."client/add/?mode=main");
				}
				// Switch to the correct sub view
				switch($mode){
					
					case "main": 
						$whichFormToInclude = "main"; 
						unset($_SESSION['newly_inserted_client_id']);																						
						if (count($_SESSION['logged_user']['countries']) > 1){
							// Retieeve all counties
							$countires_params = array("country_id", "country_name");
							$all_countries = $clientModel->retrieve_all_countires_for_users($countires_params);
						}
					break;
				}
				// If post has been submitted
				if ("POST" == $_SERVER['REQUEST_METHOD']){
					$errors = AppModel::validate($_POST['client_main_reqired']);
					$_SESSION['client_main_reqired'] = $_POST['client_main_reqired'];
					$_SESSION['client_main_not_reqired'] = $_POST['client_main_not_reqired'];
					$_SESSION['martial_status'] = $_POST['martial_status'];
					$_SESSION['client_main_dob_non_reqired'] = $_POST['client_main_dob_non_reqired'];		
					$temp_cids = $_SESSION['client_main_not_reqired']['case_ids'];
					if ($temp_cids != ""){
						if (substr($temp_cids, -1) != ','){
						  $_SESSION['client_main_not_reqired']['case_ids'] .= ',';					
						} 
					}	
					// Data Grabbing and validating for the required in contact view										
					if ((!empty($_POST['client_main_dob_non_reqired']['month'])) || (!empty($_POST['client_main_dob_non_reqired']['day'])) || (!empty($_POST['client_main_dob_non_reqired']['year']))){
						$_SESSION['client_main_dob_non_reqired'] = $_POST['client_main_dob_non_reqired'];					
						// Check data is valid
						if (!@checkdate($_POST['client_main_dob_non_reqired']['month'], $_POST['client_main_dob_non_reqired']['day'], $_POST['client_main_dob_non_reqired']['year'])){
							$_SESSION['date_input_error'] = true;
						}else{
							$_SESSION['date_input_error'] = false;							
						}
					}elseif (
							(empty($_POST['client_main_dob_non_reqired']['month'])) && 
							(empty($_POST['client_main_dob_non_reqired']['day'])) && 
							(empty($_POST['client_main_dob_non_reqired']['year']))													
							){
								unset($_SESSION['client_main_dob_non_reqired']);
								$_SESSION['date_input_error'] = false;															
					}
					// If notes form has been submitted
					if (isset($_POST['client_main_submit'])){	
						// If errors found in the adding section
						if ($errors){
							$_SESSION['client_reqired_errors'] = $errors;					
							// Display the error message				
							$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Required fields cannot be blank !</div>";
						}elseif ((isset($_SESSION['date_input_error'])) && ($_SESSION['date_input_error'])){
							$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Invalid date for Date of Birth.</div>";																		
						}else{	
								// If users' owned country count is equal to one then add user's session country else give the user to select one of country from the country list
								$country = (count($_SESSION['logged_user']['countries']) == 1) ? $_SESSION['logged_user']['countries'][0] : $_SESSION['client_main_reqired']['client_owned_country_id'];
						
								if (!empty($_SESSION['client_main_not_reqired']['case_ids'])){
									if (strstr($_SESSION['client_main_not_reqired']['case_ids'], ",")){
										$each_ids = explode(",", $_SESSION['client_main_not_reqired']['case_ids']);						
										$to_be_trimmed_each_ids = $each_id;
										foreach($to_be_trimmed_each_ids as $each_id){
										
											$each_ids[] = trim($each_id);
										}
										foreach ($each_ids as $key => $value) {
										  if (is_null($value) || $value==" ") {
											unset($each_ids[$key]);
										  }
										}
									}else{
										$each_ids = $_SESSION['client_main_not_reqired']['case_ids'];															
									}	
									if (is_array($each_ids)){	
										
										foreach($each_ids as $each_id){
											if (!empty($each_id)){
												if (!$clientModel->check_case_id_exist_in_system(trim($each_id))){
													$invalid_case_id = true;
													break;
												}	
												if (!$clientModel->check_case_id_does_belongs_to_logged_users_owned_country_for_client_in_add_mode(trim($each_id), $country)){
													$no_in_this_country = true;
													break;
												}
											}	
										}
									}else{
										if (!$clientModel->check_case_id_exist_in_system(trim($each_ids))){
											$invalid_case_id = true;
										}
										if (!$clientModel->check_case_id_does_belongs_to_logged_users_owned_country_for_client_in_add_mode(trim($each_ids[$i]), $country)){
											$no_in_this_country = true;
										}	
									}
								}		
								if ($invalid_case_id){
									$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Case ID does not exist.</div>";																											
									$_SESSION['client_reqired_errors'] = "case_ids";
								}elseif ($no_in_this_country){
									$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">This case is not matching with selected Client's country.</div>";																											
									$_SESSION['client_reqired_errors'] = "case_ids";
								}else{
									$client_details_other_params = array(
										"dob" => $_SESSION['client_main_dob_non_reqired'],
										"martial_status" => $_SESSION['martial_status']
									);
												
									// Insert case details				   
									$_SESSION['newly_inserted_client_id'] = $clientModel->insert_client_details(array_merge($_SESSION['client_main_reqired'], $_SESSION['client_main_not_reqired']), 
																												$client_details_other_params, $_SESSION['curr_country']['country_code']);
									/* Start of the attachments saving */	
									// Create the attachments folder
									if(count($_SESSION['client_main']['template_name']) > 0){
										$upload_folder_path = $_SERVER['DOCUMENT_ROOT'].DS."user-uploads/clients";
										$client_folder_path = $upload_folder_path.DS.$_SESSION['newly_inserted_client_id'];
										if(!is_dir($client_folder_path)){
											mkdir($client_folder_path);
										}
							
										// Insert case templates details
										$client_attach_details = array(
																			"name" => $_SESSION['client_main']['template_name'], 
																			"client_id" => $_SESSION['newly_inserted_client_id']
																		);
										$clientModel->insert_client_attachments_details($client_attach_details, "add");
									}	
									/* End of the attachments saving */
									if (is_array($each_ids)){
										if (count($each_ids) > 1){
											$each_ids = array_unique($each_ids);
										}	
									}	
									$clientModel->insert_owned_cases($_SESSION['newly_inserted_client_id'], $each_ids);
									// Log keeping
									$log_params = array(
														"user_id" => $_SESSION['logged_user']['id'], 
														"action_desc" => "New CLIENT was saved. ID : {$_SESSION['newly_inserted_client_id']}",
														"date_crated" => date("Y-m-d-H:i:s")
														);
									$clientModel->keep_track_of_activity_log_in_client($log_params);
									// Unset all used data
									unset($_SESSION['client_reqired_errors']);						
									unset($_SESSION['client_main_reqired']);
									unset($_SESSION['client_main_not_reqired']);
									unset($_SESSION['martial_status']);
									unset($_SESSION['client_main_dob_non_reqired']);
									unset($_SESSION['date_input_error']);								
									$_SESSION['new_client_added'] = "true";
									unset($_SESSION['client_main']);
									unset($clientModel);	
									AppController::redirect_to($site_config['base_url']."client/notes/?mode=add-notes");												
								}
							}
						}
						// If notes form has been submitted
						if (isset($_POST['client_notes_submit'])){
							// If no value has been submitted regarding the notes section						
							if (empty($_POST['client_note_categories_required'])){
								$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please select at least one category.</div>";		
							// If no value has been submitted regarding the notes section
							}elseif (empty($_POST['client_note_required']['note_text'])){
								$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please fill the note text box.</div>";		
							// If values has been submitted	
							}else{
								$_SESSION['client_note_required']['note_text'] = $_POST['client_note_required']['note_text'];							
								$notes_inputting_array = array(
																"client_id" => $_SESSION['newly_inserted_client_id'],
																"note_owner_type" => "CLIENT",
																"note" => $_SESSION['client_note_required']['note_text'],
																"added_by" => $_SESSION['logged_user']['id'],
																"added_date" => date("Y-m-d-H:i:s")
															  );
								$last_inserted_note_id = $clientModel->insert_new_note($notes_inputting_array);
								// Inserting the notes related categories
								$notes_categories_params = array(
																	"note_id" => $last_inserted_note_id,
																	"notes_categories" => $_POST['client_note_categories_required'],
																	"note_owner_section" => "CLIENT"
																);	
								$clientModel->insert_notes_categories($notes_categories_params);
								$_SESSION['new_note_has_created'] = "true";		
								// Log keeping
								$log_params = array(
													"user_id" => $_SESSION['logged_user']['id'], 
													"action_desc" => "Created a new note to CLIENT ID {$_SESSION['newly_inserted_client_id']}",
													"date_crated" => date("Y-m-d-H:i:s")
													);
								$clientModel->keep_track_of_activity_log_in_client($log_params);
								$notes_array = array('note_id', 'note');					
								$all_notes_to_this_client = $clientModel->retrieve_all_notes_owned_by_this_client($_SESSION['newly_inserted_client_id'], $notes_array, "CLIENT");																																																	
								unset($_POST);									
								unset($_SESSION['client_note_required']['note_text']);
								AppController::redirect_to($site_config['base_url'] ."client/add/?mode=main");									
							}
							$notes_array = array('note_id', 'note');					
							$all_notes_to_this_client = $clientModel->retrieve_all_notes_owned_by_this_client($_SESSION['newly_inserted_client_id'], $notes_array, "CLIENT");																																																	
						}	
					}else{
						unset($_SESSION['client_reqired_errors']);						
						unset($_SESSION['client_main_reqired']);
						unset($_SESSION['client_main_not_reqired']);
						unset($_SESSION['martial_status']);
						unset($_SESSION['client_main_dob_non_reqired']);
						unset($_SESSION['date_input_error']);
						unset($_SESSION['client_main']);						
						unset($clientModel);	
					}	
			break;
			
			case "view":
				// Unset all frist step session data
				unset($_SESSION['client_reqired_errors']);						
				unset($_SESSION['client_main_reqired']);
				unset($_SESSION['client_main_not_reqired']);
				unset($_SESSION['martial_status']);
				unset($_SESSION['client_main_dob_non_reqired']);
				unset($_SESSION['date_input_error']);								
				unset($_SESSION['newly_inserted_client_id']);	
				unset($_SESSION['client_main']);
				// All Global variables
				global $site_config;
				global $pagination;
				global $tot_page_count;
				global $cur_page;
				global $img;
				global $breadcrumb;
				global $invalidPage;								
				global $headerDivMsg;
				global $action_panel_menu;
				global $all_clients_count;
				global $all_clients;
				$breadcrumb = "";
				$sortBy = "";
				$cur_path = "";
				$sortPath = "";																												
				// Bredcrmb to the pfa section
				$breadcrumb .= "<div class=\"breadcrumbMessageDiv defaultFont boldText\"><a href=\"".$site_config['base_url']."\" class=\"headerLink\">Home</a> &rsaquo; Manage Clients</div>";
				// Object Instantiation
				$clientModel = new ClientModel(); 
				$pagination_obj = new Pagination();				
				// Display the success message after success case updation
				if ($_SESSION['is_not_client_id'] == "true"){
					$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">This client id is not exist.</div>";										
				}
				unset($_SESSION['is_not_client_id']);
				// Display the success message after success case updation
				if ($_SESSION['is_having_cases'] == "true"){
					$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Cannot delete this client : Some cases owned by this client.</div>";										
				}
				unset($_SESSION['is_having_cases']);
				// Display the success message after success case updation
				if ($_SESSION['client_deleted'] == "true"){
					$headerDivMsg = "<div class=\"headerMessageDivInNotice defaultFont\">Successfully deleted the client.</div>";										
				}
				unset($_SESSION['client_deleted']);
				// Configuring the action panel against user permissions
				$action_panel = array(
										array(
												"menu_id" => 1,
												"menu_text" => "Add / Edit Note",
												"menu_url" => $site_config['base_url']."client/notes/?mode=edit-notes",
												"menu_img" => "<img src=\"../../public/images/notepadicon.png\" border=\"0\" alt=\"New / Edit Note\" />",
												"menu_permissions" => array(67, 68, 69, 70)
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
				$param_array = array(
									'client_id', 'country_name', 'title', 'first_name', 'last_name', 'martial_status', 'client_owned_country_id', 
									'resident_address', 'land_phone', 'country', 'address_of_employment', 'email'
									);
				// Display all pfac_records				
				if (isset($_GET['sort'])){	
				
					$imgDefault = "<a href=\"".$site_config['base_url']."client/view/?sort=".$_GET['sort']."&by=asc".(isset($_GET['page']) ? "&page=1".$_GET['page'] : "")."\"><img src=\"../../public/images/s_asc.png\" border=\"0\" /></a>";			
					$imgAsc = "<a href=\"".$site_config['base_url']."client/view/?sort=".$_GET['sort']."&by=asc".(isset($_GET['page']) ? "&page=1".$_GET['page'] : "")."\"><img src=\"../../public/images/s_asc.png\" border=\"0\" /></a>";			
					$imgDesc = "<a href=\"".$site_config['base_url']."client/view/?sort=".$_GET['sort']."&by=desc".(isset($_GET['page']) ? "&page=1".$_GET['page'] : "")."\"><img src=\"../../public/images/s_desc.png\" border=\"0\" /></a>";								
					$img = (!isset($_GET['by'])) ? ($imgDefault) : (($_GET['by'] == "asc") ? $imgDesc : $imgAsc);

					$sort_param_array = array(
											  "f_name" => "first_name", "l_name" => "last_name", 
											  "mar_stat" => "martial_status", "res" => "resident_address", "l_phone" => "land_phone", "coun" => "country", "own_country" => "country_name",
											  "emp_add" => "address_of_employment", "email" => "email"
											  );
					foreach($sort_param_array as $key => $value) {
						if ($key == $_GET['sort']) {
							$sortBy = $value;
						}
					}
				}
				$curPath = $_SERVER['REQUEST_URI'];
				$cur_page = ((isset($_GET['page'])) && ($_GET['page'] != "") && ($_GET['page'] != 0)) ? $_GET['page'] : 1; 																				
				// Load all data from the address book
				$all_clients = $clientModel->display_all_clients($param_array, $cur_page, $sortBy, ((isset($_GET['by'])) ? $_GET['by'] : ""), $_SESSION['logged_user']['countries'], $_SESSION['curr_country']['country_code']);
				$all_clients_count = $clientModel->display_clients_all_count($param_array, $_SESSION['logged_user']['countries'], $_SESSION['curr_country']['country_code']);
				// Pagination load
				$pagination = $pagination_obj->generate_pagination($all_clients_count, $curPath, NO_OF_RECORDS_PER_PAGE_DEFAULT);				
				$tot_page_count = ceil($all_clients_count/NO_OF_RECORDS_PER_PAGE_DEFAULT);				

				// If no records found or no pages found
				$page = (isset($_GET['page'])) ? $_GET['page'] : 1;
				if (($page > $tot_page_count) || ($page == 0)){
					$invalidPage = true;	
				}
				// Unset all used variables
				unset($clientModel);
				unset($pagination_obj);
			break;			
		
			case "edit":			
				// All Global variables
				global $full_details;
				global $headerDivMsg;
				global $site_config;
				global $breadcrumb;
				global $printHtml;
				global $all_notes_to_this_client_in_edit;
				global $all_notes_count_to_this_client_in_edit;
				global $note_full_details;
				global $pagination;
				global $tot_page_count;
				global $img;
				global $headerDivMsg;
				global $site_config;
				global $printHtml;
				global $breadcrumb;
				global $martial_status;
				global $allTitles;
				global $countryListArray;
				global $case_ids;
				global $client_id;
				global $clientModel;
				global $pre_client_attachments;
				global $all_countries;
				$invalid_case_ids = false;
				$breadcrumb = "";
				// Bredcrmb to the pfa section				
				$client_id = trim($_GET['client_id']);
				$breadcrumb .= "<div class=\"breadcrumbMessageDiv defaultFont boldText\"><a href=\"".$site_config['base_url']
								."\" class=\"headerLink\">Home</a> ";
				if (isset($_GET['opt'])){
					$breadcrumb .= "&rsaquo; <a class=\"headerLink\" href=\"".$site_config['base_url']."case/view/\">All Clients</a> &rsaquo; <a class=\"headerLink\" href=\"".
										$site_config['base_url']."client/edit/?mode=main&client_id=".$client_id."&page=1"."\">Edit Client</a> &rsaquo; ".
										"<a class=\"headerLink\" href=\"".$site_config['base_url']."client/edit/?mode=notes&client_id=".$client_id."&page=1&notes_page=1"."\">Your all notes</a>";
					if ($_GET['opt'] == "view"){
						$breadcrumb	.= " &rsaquo; View single note";				
					}else{
						$breadcrumb	.= " &rsaquo; Edit single note";				
					}										
				}else{
					$breadcrumb .= " &rsaquo; <a class=\"headerLink\" href=\"".$site_config['base_url']."client/view/\">All Clients</a> &rsaquo; Edit Client";					
				}
				$breadcrumb .= "</div>";												
				// Generate the top header menu
				$printHtml = "";
				$printHtml .= ((isset($_GET['mode'])) && ($_GET['mode'] == "main")) ? "<span class=\"headerTopicSelected\">Main Details</span>" : "<span><a href=\"?mode=main&client_id=".trim($_GET['client_id']).((isset($_GET['page'])) ? "&page=".$_GET['page'] : "")."\">Main Details</a></span>";					
				$printHtml .= "&nbsp;&nbsp;<span class=\"ar\">&rsaquo;</span>&nbsp;&nbsp;";
				$printHtml .= ((isset($_GET['mode'])) && ($_GET['mode'] == "edit-notes")) ? "<span class=\"headerTopicSelected\">Notes</span>" : "<span><a href=\"".$site_config['base_url']."client/notes/?mode=edit-notes&client_id=".trim($_GET['client_id']).((isset($_GET['page'])) ? "&page=".$_GET['page'] : "").((isset($_GET['notes_page'])) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\">Notes</a></span>";					
				// Object Instantiation
				$clientModel = new ClientModel(); 
				// Switch to the correct sub view
				switch($mode){
					
					case "main": 
						$whichFormToInclude = "main"; 
						// Load the case main variables
						// Display the success message after success case updation
						if ($_SESSION['client_details_updated'] == "true"){
							$headerDivMsg = "<div class=\"headerMessageDivInSuccess defaultFont\">Successfully updated the client.&nbsp;&nbsp;<a class='edtingMenusLink_in_view' href='".
									$site_config['base_url']."client/show/?client_id=".trim($_GET['client_id'])."'>View details.</a></div>";										
						}
						unset($_SESSION['client_details_updated']);
						//$countryListArray = CommonFunctions::retrieve_country_list();				
						$countryListArray = CommonFunctions::retrieve_country_list();
						$allTitles = CommonFunctions::retrieve_titles_list();
						$martial_status = CommonFunctions::retrieve_martial_status_list();
						$pre_client_attachments = $clientModel->retrieve_all_client_attachments_for_this_client(trim($_GET['client_id']));
						if (count($_SESSION['logged_user']['countries']) > 1){
							// Retieeve all counties
							$countires_params = array("country_id", "country_name");
							$all_countries = $clientModel->retrieve_all_countires_for_users($countires_params);
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
						if ($_SESSION['notes_section_got_updated'] == "true"){
							$headerDivMsg = "<div class=\"headerMessageDivInSuccess defaultFont\">The above note was updated successfully.</div>";																																			
						}					
						unset($_SESSION['notes_section_got_updated']);
						// Sorting of the notes section
						$cur_path = $_SERVER['REQUEST_URI']; 						
						// Load all notes regarding to this client						
						$notes_array = array('note_id', 'note', 'username', 'date_modified', 'added_date', 'modified_by');						
						// Grab the current page
						$cur_page = ((isset($_GET['notes_page'])) && ($_GET['notes_page'] != "") && ($_GET['notes_page'] != 0)) ? $_GET['notes_page'] : 1; 												
						// Grab the pfac id
						if (isset($_GET['sort'])){	

							$imgDefault = "<a href=\"".$site_config['base_url']."client/edit/?mode=notes&client_id=".$client_id."&opt=sorting&sort=".$_GET['sort']."&by=asc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\"><img src=\"../../public/images/s_asc.png\" border=\"0\" /></a>";			
							$imgAsc = "<a href=\"".$site_config['base_url']."client/edit/?mode=notes&client_id=".$client_id."&opt=sorting&sort=".$_GET['sort']."&by=asc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\"><img src=\"../../public/images/s_asc.png\" border=\"0\" /></a>";			
							$imgDesc = "<a href=\"".$site_config['base_url']."client/edit/?mode=notes&client_id=".$client_id."&opt=sorting&sort=".$_GET['sort']."&by=desc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\"><img src=\"../../public/images/s_desc.png\" border=\"0\" /></a>";								
							$img = (!isset($_GET['by'])) ? ($imgDefault) : (($_GET['by'] == "asc") ? $imgDesc : $imgAsc);
		
							$notes_array = array('note_id' => 'note_id', 'note' => 'note', 'add_by' => 'username', 'mod_date' => 'date_modified', 'mod_by' => 'modified_by', 'add_date' => 'added_date');						
							foreach($notes_array as $key => $value) {
								if ($key == $_GET['sort']) {
									$sortBy = $value;
								}
							}
						}
						// If the notes filtering button has been clicked
						if (isset($_POST['notes_filter'])){
							// If notes categories are empty then display the error message
							if (empty($_POST['client_note_categories_required'])){
								$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please select at least one category to filter.</div>";										
							}else{
								$filtering = true;
							}
						}
						// retrieve all notes and all notes couunt to this client
						$all_notes_to_this_client_in_edit = $clientModel->retrieve_all_notes_owned_by_this_client_only_for_the_edit_view($notes_array, $cur_page, $sortBy, ((isset($_GET['by'])) ? $_GET['by'] : ""), trim($_GET['client_id']), $filtering, $_POST['client_note_categories_required']);
						$all_notes_count_to_this_client_in_edit = $clientModel->retrieve_all_notes_count_owned_by_this_client_only_for_the_edit_view(trim($_GET['client_id']), $notes_array, $filtering, $_POST['client_note_categories_required']);
						$pagination = $pagination_obj->generate_pagination($all_notes_count_to_this_client_in_edit, $cur_path, $cur_page, NO_OF_RECORDS_PER_PAGE_DEFAULT);				
						$tot_page_count = ceil($all_notes_count_to_this_client_in_edit/NO_OF_RECORDS_PER_PAGE_DEFAULT);				
						// Delete the selected note	
						if ((isset($_GET['opt'])) && ($_GET['opt'] == "drop")){
							$clientModel->delete_selected_note($_GET['note_id'], $client_id, "CLIENT");
							// Delete notes category relation as well regarding the note deletion
							$caseModel->remove_previous_notes_categories_relation(trim($_GET['note_id']));							
							$_SESSION['deleted_selected_note'] = "true";
							// Log keeping
							$log_params = array(
												"user_id" => $_SESSION['logged_user']['id'], 
												"action_desc" => "Deleted the note of CLIENT ID {$client_id}",
												"date_crated" => date("Y-m-d-H:i:s")
												);
							$clientModel->keep_track_of_activity_log_in_client($log_params);
							AppController::redirect_to($site_config['base_url']."client/edit/?mode=notes&client_id=".$client_id.((isset($_GET['page'])) ? "&page=".$_GET['page'] : "").((isset($_GET['notes_page'])) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1"));							
						}
						// Show full details of the above client selected note
						if ((isset($_GET['opt'])) && (($_GET['opt'] == "view") || ($_GET['opt'] == "edit"))){						
							// Check the note id exist in the db
							if ($clientModel->check_note_id_exist(trim($_GET['note_id']))){						
								if ($clientModel->check_note_id_owned_by_the_correct_client(trim($_GET['client_id']), trim($_GET['note_id']))){
									$note_param = array("note_id", "note", "added_date", "username", "modified_by", "date_modified");
									$note_full_details = $clientModel->retrieve_full_details_of_selected_note($_GET['note_id'], $note_param, "CLIENT");				
								}else{
									AppController::redirect_to($site_config['base_url'] ."client/view/");	
								}	
							}else{
								AppController::redirect_to($site_config['base_url'] ."client/view/");																						
							}	
						}	
					break;										
				}
				// Check whether the case id is exist in the database		
				if (
					($clientModel->check_client_id_exist_in_system(trim($_GET['client_id']))) &&
				    ($clientModel->check_client_id_can_be_visble_to_requested_user_by_country($_SESSION['logged_user']['countries'], trim($_GET['client_id']))) && 										
					($clientModel->check_client_country_with_curr_selected_country($_SESSION['curr_country']['country_code'], trim($_GET['client_id'])))
				   )	
				{
					$full_details = $clientModel->retrieve_full_details_per_each_client(trim($_GET['client_id']));	
					$client_id = $full_details[0]['client_id'];
					$case_ids = $clientModel->retrieve_owned_case_ids_by_this_client($client_id);
					// Start to validate and other main functions				
					if ("POST" == $_SERVER['REQUEST_METHOD']){
					$errors = AppModel::validate($_POST['client_main_reqired']);
					$_SESSION['client_main_reqired'] = $_POST['client_main_reqired'];
					$_SESSION['client_main_not_reqired'] = $_POST['client_main_not_reqired'];
					$_SESSION['martial_status'] = $_POST['martial_status'];
					$_SESSION['client_main_dob_non_reqired'] = $_POST['client_main_dob_non_reqired'];
					$temp_cids = $_SESSION['client_main_not_reqired']['case_ids'];
					if ($temp_cids != ""){
						if (substr($temp_cids, -1) != ','){
						  $_SESSION['client_main_not_reqired']['case_ids'] .= ',';					
						} 
					}	
					// Data Grabbing and validating for the required in contact view										
					if ((!empty($_POST['client_main_dob_non_reqired']['month'])) || (!empty($_POST['client_main_dob_non_reqired']['day'])) || (!empty($_POST['client_main_dob_non_reqired']['year']))){
						$_SESSION['client_main_dob_non_reqired'] = $_POST['client_main_dob_non_reqired'];					
						// Check data is valid
						if (!@checkdate($_POST['client_main_dob_non_reqired']['month'], $_POST['client_main_dob_non_reqired']['day'], $_POST['client_main_dob_non_reqired']['year'])){
							$_SESSION['date_input_error'] = true;
						}else{
							$_SESSION['date_input_error'] = false;							
						}
					}elseif (
							(empty($_POST['client_main_dob_non_reqired']['month'])) && 
							(empty($_POST['client_main_dob_non_reqired']['day'])) && 
							(empty($_POST['client_main_dob_non_reqired']['year']))													
							){
								unset($_SESSION['client_main_dob_non_reqired']);
								$_SESSION['date_input_error'] = false;															
					}
					// If notes form has been submitted
					if (isset($_POST['client_main_submit'])){	
						// If errors found in the adding section
						if ($errors){
							$_SESSION['client_reqired_errors'] = $errors;					
							// Display the error message				
							$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Required fields cannot be blank !</div>";
						}elseif ((isset($_SESSION['date_input_error'])) && ($_SESSION['date_input_error'])){
							$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Invalid date for Date of Birth.</div>";																		
						}else{	
							
								if (!empty($_POST['client_main_not_reqired']['case_ids'])){
									if (strstr($_POST['client_main_not_reqired']['case_ids'], ",")){
										$each_ids = explode(",", $_POST['client_main_not_reqired']['case_ids']);						
										$to_be_trimmed_each_ids = $each_id;
										foreach($to_be_trimmed_each_ids as $each_id){
										
											$each_ids[] = trim($each_id);
										}
										foreach ($each_ids as $key => $value) {
										  if (is_null($value) || $value==" ") {
											unset($each_ids[$key]);
										  }
										}
									}else{
										$each_ids = $_POST['client_main_not_reqired']['case_ids'];															
									}	
									if (is_array($each_ids)){	
										foreach($each_ids as $each_id){
											if (!empty($each_id)){
												if (!$clientModel->check_case_id_exist_in_system(trim($each_id))){
													$invalid_case_id = true;
													break;
												}	
												if (!$clientModel->check_case_id_does_belongs_to_logged_users_owned_country_for_client(trim($each_id), $client_id)){
													$no_in_this_country = true;
													break;
												}	
												//if ($clientModel->notifying_about_the_case_adding_for_the_duplications(trim($each_ids[$i]), $client_id)){
													//$duplicate_case_id = true;
													//break;
												//}	
											}	
										}
									}else{
										if (!$clientModel->check_case_id_exist_in_system(trim($each_ids))){
											$invalid_case_id = true;
										}			
										if (!$clientModel->check_case_id_does_belongs_to_logged_users_owned_country_for_client(trim($each_ids), $client_id)){
											$no_in_this_country = true;
										}		
										//if ($clientModel->notifying_about_the_case_adding_for_the_duplications(trim($each_ids), $client_id)){
											//$duplicate_case_id = true;
										//}	
									}	
								}
						
								if ($invalid_case_id){
									$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Case ID does not exist.</div>";																											
									$_SESSION['client_reqired_errors']['case_ids'] = "case_ids";
								}elseif ($no_in_this_country){
									$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">This case is not matching with selected Client's country.</div>";																											
									$_SESSION['client_reqired_errors'] = "case_ids";
//								}elseif ($duplicate_case_id){
//									$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">This case is previoulsly assigned to this client.</div>";																											
//									$_SESSION['client_reqired_errors'] = "case_ids";
								}else{
									$client_details_other_params = array(
																		"dob" => $_SESSION['client_main_dob_non_reqired'],
																		"martial_status" => $_SESSION['martial_status']
																		);
									// If users' owned country count is equal to one then add user's session country else give the user to select one of country from the country list
									$country = (count($_SESSION['logged_user']['countries']) == 1) ? $_SESSION['logged_user']['countries'][0] : $full_details[0]['client_owned_country_id'];
									// Insert case details				   
									$clientModel->update_client_details(array_merge($_SESSION['client_main_reqired'], $_SESSION['client_main_not_reqired']), $client_details_other_params, $client_id, $country);
																												
									/* Start of the attachments saving */	
									// Create the attachments folder
									if(count($_SESSION['client_main']['template_name']) > 0){
										$upload_folder_path = $_SERVER['DOCUMENT_ROOT'].DS."user-uploads/clients";
										$client_folder_path = $upload_folder_path.DS.$client_id;
										if(!is_dir($client_folder_path)){
											mkdir($client_folder_path);
										}
							
										// Insert case templates details
										$client_attach_details = array(
																			"name" => $_SESSION['client_main']['template_name'], 
																			"client_id" => $client_id
																		);
										$clientModel->insert_client_attachments_details($client_attach_details, "edit");
									}
									/* End of the attachments saving */
																												
									// Delete previously owned case ids
									$clientModel->delete_previous_owned_case_ids_by_this_client($client_id);
									// Inserting new case ids
									if (is_array($each_ids)){
										if (count($each_ids) > 1){
											$each_ids = array_unique($each_ids);
										}	
									}	
									$clientModel->insert_owned_cases($client_id, $each_ids);
									// Log keeping
									$log_params = array(
														"user_id" => $_SESSION['logged_user']['id'], 
														"action_desc" => "CLIENT details was updated. ID : $client_id",
														"date_crated" => date("Y-m-d-H:i:s")
														);
									$clientModel->keep_track_of_activity_log_in_client($log_params);
									// Unset all used data
									unset($_SESSION['client_reqired_errors']);						
									unset($_SESSION['client_main_reqired']);
									unset($_SESSION['client_main_not_reqired']);
									unset($_SESSION['martial_status']);
									unset($_SESSION['client_main_dob_non_reqired']);
									unset($_SESSION['date_input_error']);								
									$_SESSION['client_details_updated']	= "true";	
									unset($_SESSION['client_main']);
									unset($clientModel);	
									AppController::redirect_to($site_config['base_url']."client/edit/?mode=main&client_id=".$client_id."&notes_page=1");												
								}
							}
						}
						// If notes form has been submitted
						if (isset($_POST['client_notes_submit'])){
							// If no value has been submitted regarding the notes section						
							if (empty($_POST['client_note_categories_required'])){
								$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please select at least one category.</div>";		
							// If no value has been submitted regarding the notes section
							}elseif (empty($_POST['client_note_required']['note_text'])){
								$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please fill the note text box.</div>";		
							// If values has been submitted	
							}else{
								$_SESSION['client_note_required']['note_text'] = $_POST['client_note_required']['note_text'];							
								$notes_inputting_array = array(
																"client_id" => $client_id,
																"note_owner_type" => "CLIENT",
																"note" => $_SESSION['client_note_required']['note_text'],
																"added_by" => $_SESSION['logged_user']['id'],
																"added_date" => date("Y-m-d-H:i:s")
															  );
								$last_inserted_note_id = $clientModel->insert_new_note($notes_inputting_array);
								// Inserting the notes related categories
								$notes_categories_params = array(
																	"note_id" => $last_inserted_note_id,
																	"notes_categories" => $_POST['client_note_categories_required'],
																	"note_owner_section" => "CLIENT"
																);	
								$clientModel->insert_notes_categories($notes_categories_params);
								$_SESSION['new_note_has_created'] = "true";		
								// Log keeping
								$log_params = array(
													"user_id" => $_SESSION['logged_user']['id'], 
													"action_desc" => "Created a new note to CLIENT ID {$client_id}",
													"date_crated" => date("Y-m-d-H:i:s")
													);
								$clientModel->keep_track_of_activity_log_in_client($log_params);
								$notes_array = array('note_id', 'note');					
								$all_notes_to_this_client = $clientModel->retrieve_all_notes_owned_by_this_client($client_id, $notes_array, "CLIENT");																																																	
								unset($_POST);									
								unset($_SESSION['client_note_required']['note_text']);
								AppController::redirect_to($site_config['base_url'] ."client/edit/?mode=notes&client_id=".$client_id.((isset($_GET['page'])) ? "&page=".$_GET['page'] : "").((isset($_GET['notes_page'])) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1"));									
							}
							$notes_array = array('note_id', 'note');					
							$all_notes_to_this_client = $clientModel->retrieve_all_notes_owned_by_this_client($client_id, $notes_array);																																																	
						}	
						// If notes form has been submitted in edit note section
						if (isset($_POST['client_notes_update_submit'])){
							// Check the note id exist in the db
							if ($clientModel->check_note_id_exist(trim($_GET['note_id']))){
								// Check the note is owned by him self
								if ($clientModel->check_note_id_owned_by_the_correct_client(trim($_GET['client_id']), trim($_GET['note_id']))){								
									// If no value has been submitted regarding the notes section						
									if (empty($_POST['client_note_categories_required'])){
										$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please select at least one category.</div>";		
									// If no value has been submitted regarding the notes section
									}elseif (empty($_POST['client_note_required']['note_text'])){
										$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please fill the note text box.</div>";		
									// If both values has been submitted	
									}else{
										$_SESSION['client_note_required']['note_text'] = $_POST['client_note_required']['note_text'];							
										$notes_inputting_array = array(
															"client_id" => $client_id,
															"note_owner_type" => "CLIENT",													 
															"note" => $_SESSION['client_note_required']['note_text'],
															"date_modified" => date("Y-m-d-H:i:s"),
															"modified_by" => $_SESSION['logged_user']['id']
															);
										$clientModel->update_the_exsiting_note($notes_inputting_array, trim($_GET['note_id']));
										// Remove previous notes categories relation
										$clientModel->remove_previous_notes_categories_relation(trim($_GET['note_id']));										
										// Inserting the notes related categories
										$notes_categories_params = array(
																			"note_id" => trim($_GET['note_id']),
																			"notes_categories" => $_POST['client_note_categories_required'],
																			"note_owner_section" => "CLIENT"
																		);	
										$clientModel->insert_notes_categories($notes_categories_params);
										// Log keeping
										$log_params = array(
															"user_id" => $_SESSION['logged_user']['id'], 
															"action_desc" => "Updated a note of CLIENT ID {$client_id}",
															"date_crated" => date("Y-m-d-H:i:s")
															);
										$clientModel->keep_track_of_activity_log_in_client($log_params);
										$notes_array = array('note_id', 'note', 'username', 'added_date', 'modified_date');						
										$note_full_details = $clientModel->retrieve_full_details_of_selected_note($_GET['note_id'], $note_param, "CLIENT");				
										unset($_POST);
										unset($_SESSION['client_note_required']['note_text']);
										$_SESSION['notes_section_got_updated'] = "true";
										AppController::redirect_to($site_config['base_url']."client/edit/?mode=notes&client_id=".$client_id.((isset($_GET['page'])) ? "&page=".$_GET['page'] : "").((isset($_GET['notes_page'])) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1"));												
									}
								// If note id is not owned by him self																				
								}else{
									unset($_SESSION['pfac_note_required']['note_text']);
									AppController::redirect_to($site_config['base_url'] ."client/view/");	
								}
							// If wrong note id										
							}else{
								unset($_SESSION['pfac_note_required']['note_text']);
								AppController::redirect_to($site_config['base_url'] ."client/view/");	
							}	
						}
					}else{
						unset($_SESSION['client_reqired_errors']);						
						unset($_SESSION['client_main_reqired']);
						unset($_SESSION['client_main_not_reqired']);
						unset($_SESSION['martial_status']);
						unset($_SESSION['client_main_dob_non_reqired']);
						unset($_SESSION['date_input_error']);								
						unset($clientModel);	
						unset($full_details);
					}
				// If wrong id	
				}else{
					AppController::redirect_to($site_config['base_url'] ."client/view/");
				}
			break;
			
			case "drop":
				// All global variables		
				global $site_config;				
				// objects instantiation
				$clientModel = new ClientModel();
				// Check whether the user id is exist in the database						
				if (
					(!$clientModel->check_client_id_exist_in_system(trim($_GET['client_id']))) &&
				    (!$clientModel->check_client_id_can_be_visble_to_requested_user_by_country($_SESSION['logged_user']['countries'], trim($_GET['client_id'])))															
				   )					
				{
					$_SESSION['is_not_client_id'] = "true";		
					$rest_of_deletion = false;												
				// Check whether the selected client have assigned been responsible for any case
				}elseif ($clientModel->is_having_any_cases(trim($_GET['client_id']))){
					// Dlete client owned cases from client__cases table
					$clientModel->delete_cases_involved_with_this_client(trim($_GET['client_id']));
					$rest_of_deletion = true;					
				// If no cases assigned for this client
				}else{
					$rest_of_deletion = true;
				}	
				
				if ($rest_of_deletion){
				
					// Deleting the clients notes
					$clientModel->delete_owned_note(trim($_GET['client_id']), "CLIENT");					
					// Actually deleting the PHARO user from the users table
					$clientModel->delete_user_details_from_the_table(trim($_GET['client_id']));
					// Delete the case templates names
					$clientModel->delete_client_attachments_names(trim($_GET['client_id']));
					// Remove case templates
					$case_folder_path = SERVER_ROOT.DS.'user-uploads'.DS.'clients'.DS.trim($_GET['client_id']);
					if(is_dir($case_folder_path)) { CommonFunctions::delete_entries($case_folder_path); }
					// Log keeping
					$log_params = array(
										"user_id" => $_SESSION['logged_user']['id'], 
										"action_desc" => "Deleted CLIENT. ID : ".$_GET['client_id'],
										"date_crated" => date("Y-m-d-H:i:s")
										);
					$clientModel->keep_track_of_activity_log_in_client($log_params);
					$_SESSION['client_deleted'] = "true";
				}
				unset($clientModel);
				AppController::redirect_to($site_config['base_url'] ."client/view/");																																								
			break;			
			
			case "show":
				// All global variables		
				global $fullDetails;
				global $breadcrumb;
				global $site_config;
				global $caseModel;				
				global $cases_owned;
				global $clientModel;
				$breadcrumb = "";
				// objects instantiation
				$clientModel = new ClientModel(); 
				// Bredcrmb to the pfa section
				$breadcrumb .= "<div class=\"breadcrumbMessageDiv defaultFont boldText\"><a href=\"".$site_config['base_url']
								."\" class=\"headerLink\">Home</a> &rsaquo; "."<a class=\"headerLink\" href=\"".$site_config['base_url']."client/view/\">All Clients</a> &rsaquo; View Client</div>";
				// Check whether the CASE id is exist in the database		
				if (
					($clientModel->check_client_id_exist_in_system(trim($_GET['client_id']))) &&
				    ($clientModel->check_client_id_can_be_visble_to_requested_user_by_country($_SESSION['logged_user']['countries'], trim($_GET['client_id']))) &&
					($clientModel->check_client_country_with_curr_selected_country($_SESSION['curr_country']['country_code'], trim($_GET['client_id'])))										
				   )	
				{
					$fullDetails = $clientModel->grab_full_details_for_the_single_view_in_client(trim($_GET['client_id']));		
					$cases_owned = $clientModel->retrieve_cases_owned_by_this_client(trim($_GET['client_id']));
					// Log keeping
					$log_params = array(
										"user_id" => $_SESSION['logged_user']['id'], 
										"action_desc" => "Viewed details of CLIENT ID {$_GET['client_id']}",
										"date_crated" => date("Y-m-d-H:i:s")
										);
					$clientModel->keep_track_of_activity_log_in_client($log_params);
				// If wrong id	
				}else{
					AppController::redirect_to($site_config['base_url'] ."client/view/");							
				}
				unset($caseModel);
				unset($fullDetails);
				unset($vols_assigned);
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
				global $note_full_details;
				global $pagination;
				global $tot_page_count;
				global $img;
				global $headerDivMsg;
				global $site_config;
				global $printHtml;
				global $breadcrumb;
				global $client_id;
				global $clientModel;
				$breadcrumb = "";
				// Bredcrmb to the pfa section				
				$breadcrumb .= "<div class=\"breadcrumbMessageDiv defaultFont boldText\"><a href=\"".$site_config['base_url']
								."\" class=\"headerLink\">Home</a> ";
				if (isset($_GET['opt'])){
					$breadcrumb .= "&rsaquo; <a class=\"headerLink\" href=\"".$site_config['base_url']."case/view/\">All Clients</a> &rsaquo; <a class=\"headerLink\" href=\"".
										$site_config['base_url']."client/edit/?mode=main&client_id=".trim($_GET['client_id'])."&page=1"."\">Edit Client</a> &rsaquo; ".
										"<a class=\"headerLink\" href=\"".$site_config['base_url']."client/notes/?mode=edit-notes&client_id=".trim($_GET['client_id'])."&page=1&notes_page=1"."\">Your all notes</a>";
					if ($_GET['opt'] == "view"){
						$breadcrumb	.= " &rsaquo; View single note";				
					}else{
						$breadcrumb	.= " &rsaquo; Edit single note";				
					}										
				}else{
					$breadcrumb .= " &rsaquo; <a class=\"headerLink\" href=\"".$site_config['base_url']."client/view/\">All Clients</a> &rsaquo; Edit Client";					
				}
				$breadcrumb .= "</div>";	
				// If the correct post back not submitted then redirect the user to the correct page
				if (($mode == "add-notes") && (!isset($_SESSION['newly_inserted_client_id']))){
					AppController::redirect_to($site_config['base_url'] ."client/add/?mode=main");
				}
				// Generate the top header menu
				if ($_GET['mode'] == "edit-notes"){
					$client_id = trim($_GET['client_id']);				
					$printHtml = "";
					
					if (in_array("11", $_SESSION['logged_user']['permissions'])){
						$printHtml .= ((isset($_GET['mode'])) && ($_GET['mode'] == "main")) ? "<span class=\"headerTopicSelected\">Main Details</span>" : "<span><a href=\"".$site_config['base_url']."client/edit/?mode=main&client_id=".trim($_GET['client_id']).((isset($_GET['page'])) ? "&page=".$_GET['page'] : "")."\">Main Details</a></span>";					
						$printHtml .= "&nbsp;&nbsp;<span class=\"ar\">&rsaquo;</span>&nbsp;&nbsp;";
					}
					
					$printHtml .= ((isset($_GET['mode'])) && ($_GET['mode'] == "edit-notes")) ? "<span class=\"headerTopicSelected\">Notes</span>" : "<span><a href=\"".$site_config['base_url']."client/notes/?mode=edit-notes&client_id=".trim($_GET['client_id']).((isset($_GET['page'])) ? "&page=".$_GET['page'] : "").((isset($_GET['notes_page'])) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\">Notes</a></span>";					
				}else{
					$printHtml = "Notes";
				}	
				// Object Instantiation
				$clientModel = new ClientModel(); 
				// Switch to the correct sub view
				switch($mode){
					
					case "add-notes": 
						$whichFormToInclude = "notes";
						// Display the success message in new contact adding
						if ($_SESSION['new_client_added'] == "true"){					
							$headerDivMsg = "<div class=\"headerMessageDivInSuccess defaultFont\">New client was added successfully.&nbsp;&nbsp;<a class='edtingMenusLink_in_view' href='".
									$site_config['base_url']."client/show/?client_id=".$_SESSION['newly_inserted_client_id']."'>View details.</a></div>";																																			
						}
						unset($_SESSION['new_client_added']);	
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
						$client_id = $_SESSION['newly_inserted_client_id'];
						// Load last inserted notes by last inserted user
						$notes_array = array('note_id', 'note');					
						$all_notes_to_this_client = $clientModel->retrieve_all_notes_owned_by_this_client($_SESSION['newly_inserted_client_id'], $notes_array, "CLIENT");
						// Delete the selected note	
						if ((isset($_GET['opt'])) && ($_GET['opt'] == "drop")){
							$clientModel->delete_selected_note(trim($_GET['note_id']), $client_id, "CLIENT");
							$_SESSION['deleted_selected_note'] = "true";
							// Log keeping
							$log_params = array(
												"user_id" => $_SESSION['logged_user']['id'], 
												"action_desc" => "Deleted the note of CLIENT ID {$client_id}",
												"date_crated" => date("Y-m-d-H:i:s")
												);
							$clientModel->keep_track_of_activity_log_in_client($log_params);
							AppController::redirect_to($site_config['base_url']."client/notes/?mode=add-notes");
						}
						// Start to validate and other main functions				
						if ("POST" == $_SERVER['REQUEST_METHOD']){
							// If notes form has been submitted
							if (isset($_POST['client_notes_submit'])){
								// If no value has been submitted regarding the notes section						
								if (empty($_POST['client_note_categories_required'])){
									$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please select at least one category.</div>";		
								// If no value has been submitted regarding the notes section
								}elseif (empty($_POST['client_note_required']['note_text'])){
									$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please fill the note text box.</div>";		
								// If values has been submitted	
								}else{
									$_SESSION['client_note_required']['note_text'] = $_POST['client_note_required']['note_text'];							
									$notes_inputting_array = array(
																	"client_id" => $client_id,
																	"note_owner_type" => "CLIENT",
																	"note" => $_SESSION['client_note_required']['note_text'],
																	"added_by" => $_SESSION['logged_user']['id'],
																	"added_date" => date("Y-m-d-H:i:s")
																  );
									$last_inserted_note_id = $clientModel->insert_new_note($notes_inputting_array);
									// Inserting the notes related categories
									$notes_categories_params = array(
																		"note_id" => $last_inserted_note_id,
																		"notes_categories" => $_POST['client_note_categories_required'],
																		"note_owner_section" => "CLIENT"
																	);	
									$clientModel->insert_notes_categories($notes_categories_params);
									$_SESSION['new_note_has_created'] = "true";		
									// Log keeping
									$log_params = array(
														"user_id" => $_SESSION['logged_user']['id'], 
														"action_desc" => "Created a new note to CLIENT ID {$client_id}",
														"date_crated" => date("Y-m-d-H:i:s")
														);
									$clientModel->keep_track_of_activity_log_in_client($log_params);
									$notes_array = array('note_id', 'note');					
									$all_notes_to_this_client = $clientModel->retrieve_all_notes_owned_by_this_client($client_id, $notes_array, "CLIENT");																																																	
									unset($_POST);									
									unset($_SESSION['client_note_required']['note_text']);
									AppController::redirect_to($site_config['base_url'] ."client/notes/?mode=add-notes");									
								}
								$notes_array = array('note_id', 'note');					
								$all_notes_to_this_client = $clientModel->retrieve_all_notes_owned_by_this_client($client_id, $notes_array);																																																	
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
						$cur_path = $_SERVER['REQUEST_URI']; 						
						// Load all notes regarding to this client						
						$notes_array = array('note_id', 'note', 'username', 'date_modified', 'added_date', 'modified_by');						
						// Grab the current page
						$cur_page = ((isset($_GET['notes_page'])) && ($_GET['notes_page'] != "") && ($_GET['notes_page'] != 0)) ? $_GET['notes_page'] : 1; 												
						// Grab the pfac id
						if (isset($_GET['sort'])){	

							$imgDefault = "<a href=\"".$site_config['base_url']."client/notes/?mode=edit-notes&client_id=".$client_id."&opt=sorting&sort=".$_GET['sort']."&by=asc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\"><img src=\"../../public/images/s_asc.png\" border=\"0\" /></a>";			
							$imgAsc = "<a href=\"".$site_config['base_url']."client/notes/?mode=edit-notes&client_id=".$client_id."&opt=sorting&sort=".$_GET['sort']."&by=asc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\"><img src=\"../../public/images/s_asc.png\" border=\"0\" /></a>";			
							$imgDesc = "<a href=\"".$site_config['base_url']."client/notes/?mode=edit-notes&client_id=".$client_id."&opt=sorting&sort=".$_GET['sort']."&by=desc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\"><img src=\"../../public/images/s_desc.png\" border=\"0\" /></a>";								
							$img = (!isset($_GET['by'])) ? ($imgDefault) : (($_GET['by'] == "asc") ? $imgDesc : $imgAsc);
		
							$notes_array = array('note_id' => 'note_id', 'note' => 'note', 'add_by' => 'username', 'mod_date' => 'date_modified', 'mod_by' => 'modified_by', 'add_date' => 'added_date');						
							foreach($notes_array as $key => $value) {
								if ($key == $_GET['sort']) {
									$sortBy = $value;
								}
							}
						}
						// If the notes filtering button has been clicked
						if (isset($_POST['notes_filter'])){
							// If notes categories are empty then display the error message
							if (empty($_POST['client_note_categories_required'])){
								$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please select at least one category to filter.</div>";										
							}else{
								$filtering = true;
							}
						}
						// retrieve all notes and all notes couunt to this client
						$all_notes_to_this_client_in_edit = $clientModel->retrieve_all_notes_owned_by_this_client_only_for_the_edit_view($notes_array, $cur_page, $sortBy, ((isset($_GET['by'])) ? $_GET['by'] : ""), trim($_GET['client_id']), $filtering, $_POST['client_note_categories_required']);
						$all_notes_count_to_this_client_in_edit = $clientModel->retrieve_all_notes_count_owned_by_this_client_only_for_the_edit_view(trim($_GET['client_id']), $notes_array, $filtering, $_POST['client_note_categories_required']);
						$pagination = $pagination_obj->generate_pagination($all_notes_count_to_this_client_in_edit, $cur_path, $cur_page, NO_OF_RECORDS_PER_PAGE_DEFAULT);				
						$tot_page_count = ceil($all_notes_count_to_this_client_in_edit/NO_OF_RECORDS_PER_PAGE_DEFAULT);				
						// Delete the selected note	
						if ((isset($_GET['opt'])) && ($_GET['opt'] == "drop")){
							$clientModel->delete_selected_note($_GET['note_id'], $client_id, "CLIENT");
							// Delete notes category relation as well regarding the note deletion
							$clientModel->remove_previous_notes_categories_relation(trim($_GET['note_id']));							
							$_SESSION['deleted_selected_note'] = "true";
							// Log keeping
							$log_params = array(
												"user_id" => $_SESSION['logged_user']['id'], 
												"action_desc" => "Deleted the note of CLIENT ID {$client_id}",
												"date_crated" => date("Y-m-d-H:i:s")
												);
							$clientModel->keep_track_of_activity_log_in_client($log_params);
							AppController::redirect_to($site_config['base_url']."client/notes/?mode=edit-notes&client_id=".$client_id.((isset($_GET['page'])) ? "&page=".$_GET['page'] : "").((isset($_GET['notes_page'])) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1"));							
						}
						// Show full details of the above client selected note
						if ((isset($_GET['opt'])) && (($_GET['opt'] == "view") || ($_GET['opt'] == "edit"))){						
							// Check the note id exist in the db
							if ($clientModel->check_note_id_exist(trim($_GET['note_id']))){						
								if ($clientModel->check_note_id_owned_by_the_correct_client(trim($_GET['client_id']), trim($_GET['note_id']))){
									$note_param = array("note_id", "note", "added_date", "username", "modified_by", "date_modified");
									$note_full_details = $clientModel->retrieve_full_details_of_selected_note($_GET['note_id'], $note_param, "CLIENT");				
								}else{
									AppController::redirect_to($site_config['base_url'] ."client/view/");	
								}	
							}else{
								AppController::redirect_to($site_config['base_url'] ."client/view/");																						
							}	
						}
						if (
							($clientModel->check_client_id_exist_in_system(trim($_GET['client_id']))) &&
							($clientModel->check_client_id_can_be_visble_to_requested_user_by_country($_SESSION['logged_user']['countries'], trim($_GET['client_id']))) &&
							($clientModel->check_client_country_with_curr_selected_country($_SESSION['curr_country']['country_code'], trim($_GET['client_id'])))										
						   )	
						{
							// Start to validate and other main functions				
							if ("POST" == $_SERVER['REQUEST_METHOD']){
								// If notes form has been submitted
								if (isset($_POST['client_notes_submit'])){
									// If no value has been submitted regarding the notes section						
									if (empty($_POST['client_note_categories_required'])){
										$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please select at least one category.</div>";		
									// If no value has been submitted regarding the notes section
									}elseif (empty($_POST['client_note_required']['note_text'])){
										$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please fill the note text box.</div>";		
									// If values has been submitted	
									}else{
										$_SESSION['client_note_required']['note_text'] = $_POST['client_note_required']['note_text'];							
										$notes_inputting_array = array(
																		"client_id" => $client_id,
																		"note_owner_type" => "CLIENT",
																		"note" => $_SESSION['client_note_required']['note_text'],
																		"added_by" => $_SESSION['logged_user']['id'],
																		"added_date" => date("Y-m-d-H:i:s")
																	  );
										$last_inserted_note_id = $clientModel->insert_new_note($notes_inputting_array);
										// Inserting the notes related categories
										$notes_categories_params = array(
																			"note_id" => $last_inserted_note_id,
																			"notes_categories" => $_POST['client_note_categories_required'],
																			"note_owner_section" => "CLIENT"
																		);	
										$clientModel->insert_notes_categories($notes_categories_params);
										$_SESSION['new_note_has_created'] = "true";		
										// Log keeping
										$log_params = array(
															"user_id" => $_SESSION['logged_user']['id'], 
															"action_desc" => "Created a new note to CLIENT ID {$client_id}",
															"date_crated" => date("Y-m-d-H:i:s")
															);
										$clientModel->keep_track_of_activity_log_in_client($log_params);
										$notes_array = array('note_id', 'note');					
										$all_notes_to_this_client = $clientModel->retrieve_all_notes_owned_by_this_client($client_id, $notes_array, "CLIENT");																																																	
										unset($_POST);									
										unset($_SESSION['client_note_required']['note_text']);
										AppController::redirect_to($site_config['base_url'] ."client/notes/?mode=edit-notes&client_id=".$client_id.((isset($_GET['page'])) ? "&page=".$_GET['page'] : "").((isset($_GET['notes_page'])) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1"));									
									}
									$notes_array = array('note_id', 'note');					
									$all_notes_to_this_client = $clientModel->retrieve_all_notes_owned_by_this_client($client_id, $notes_array);																																																	
								}	
								// If notes form has been submitted in edit note section
								if (isset($_POST['client_notes_update_submit'])){
									// Check the note id exist in the db
									if ($clientModel->check_note_id_exist(trim($_GET['note_id']))){
										// Check the note is owned by him self
										if ($clientModel->check_note_id_owned_by_the_correct_client(trim($_GET['client_id']), trim($_GET['note_id']))){								
											// If no value has been submitted regarding the notes section						
											if (empty($_POST['client_note_categories_required'])){
												$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please select at least one category.</div>";		
											// If no value has been submitted regarding the notes section
											}elseif (empty($_POST['client_note_required']['note_text'])){
												$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please fill the note text box.</div>";		
											// If both values has been submitted	
											}else{
												$_SESSION['client_note_required']['note_text'] = $_POST['client_note_required']['note_text'];							
												$notes_inputting_array = array(
																	"client_id" => $client_id,
																	"note_owner_type" => "CLIENT",													 
																	"note" => $_SESSION['client_note_required']['note_text'],
																	"date_modified" => date("Y-m-d-H:i:s"),
																	"modified_by" => $_SESSION['logged_user']['id']
																	);
												$clientModel->update_the_exsiting_note($notes_inputting_array, trim($_GET['note_id']));
												// Remove previous notes categories relation
												$clientModel->remove_previous_notes_categories_relation(trim($_GET['note_id']));										
												// Inserting the notes related categories
												$notes_categories_params = array(
																					"note_id" => trim($_GET['note_id']),
																					"notes_categories" => $_POST['client_note_categories_required'],
																					"note_owner_section" => "CLIENT"
																				);	
												$clientModel->insert_notes_categories($notes_categories_params);
												// Log keeping
												$log_params = array(
																	"user_id" => $_SESSION['logged_user']['id'], 
																	"action_desc" => "Updated a note of CLIENT ID {$client_id}",
																	"date_crated" => date("Y-m-d-H:i:s")
																	);
												$clientModel->keep_track_of_activity_log_in_client($log_params);
												$notes_array = array('note_id', 'note', 'username', 'added_date', 'modified_date');						
												$note_full_details = $clientModel->retrieve_full_details_of_selected_note($_GET['note_id'], $note_param, "CLIENT");				
												unset($_POST);
												unset($_SESSION['client_note_required']['note_text']);
												$_SESSION['notes_section_got_updated'] = "true";
												AppController::redirect_to($site_config['base_url']."client/notes/?mode=edit-notes&client_id=".$client_id.((isset($_GET['page'])) ? "&page=".$_GET['page'] : "").((isset($_GET['notes_page'])) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1"));												
											}
										// If note id is not owned by him self																				
										}else{
											unset($_SESSION['pfac_note_required']['note_text']);
											AppController::redirect_to($site_config['base_url'] ."client/view/");	
										}
									// If wrong note id										
									}else{
										unset($_SESSION['pfac_note_required']['note_text']);
										AppController::redirect_to($site_config['base_url'] ."client/view/");	
									}	
								}
							}else{
								unset($_SESSION['client_reqired_errors']);						
								unset($_SESSION['client_main_reqired']);
								unset($_SESSION['client_main_not_reqired']);
								unset($_SESSION['martial_status']);
								unset($_SESSION['client_main_dob_non_reqired']);
								unset($_SESSION['date_input_error']);								
								unset($clientModel);	
								unset($full_details);
							}
						// If wrong id	
						}else{
							AppController::redirect_to($site_config['base_url'] ."client/view/");
						}
					break;										
				}
				// Check whether the case id is exist in the database		
			break;
		}	
	}
}
?>