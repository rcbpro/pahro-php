<?php

class CounterPartyController extends AppController{	

	/* This function will check the correct sub view provided else redirect to the home page */
	function correct_sub_view_gate_keeper($subView){
	
		if (isset($subView)){
			$modes_array = array("main", "edit-notes", "add-notes");
			if (!in_array($subView, $modes_array)){
				global $site_config;
				AppController::redirect_to($site_config['base_url']."counter-party/view/");
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
				global $all_notes_to_this_counter_party;				
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
				$breadcrumb .= "<div class=\"breadcrumbMessageDiv defaultFont boldText\"><a href=\"".$site_config['base_url']."\" class=\"headerLink\">Home</a> &rsaquo; Add New Counter Party</div>";
				// Object Instantiation
				$counterPartyModel = new CounterPartyModel(); 
				$allTitles = CommonFunctions::retrieve_titles_list();
				// Switch to the correct sub view
				// If the correct post back not submitted then redirect the user to the correct page
				if (($mode == "notes") && (!isset($_SESSION['newly_inserted_counter_id']))){
					AppController::redirect_to($site_config['base_url'] ."counter-party/add/?mode=main");
				}
				switch($mode){
					
					case "main": 
						$whichFormToInclude = "main"; 
						unset($_SESSION['newly_inserted_counter_id']);																						
						if (count($_SESSION['logged_user']['countries']) > 1){
							// Retieeve all counties
							$countires_params = array("country_id", "country_name");
							$all_countries = $counterPartyModel->retrieve_all_countires_for_users($countires_params);
						}
					break;
				}				
				// If post has been submitted
				if ("POST" == $_SERVER['REQUEST_METHOD']){
					$errors = AppModel::validate($_POST['counter_main_reqired']);
					$_SESSION['counter_main_reqired'] = $_POST['counter_main_reqired'];
					$_SESSION['counter_main_not_reqired'] = $_POST['counter_main_not_reqired'];
					$names_validations = array("first_name" => $_SESSION['counter_main_reqired']['first_name'], "last_name" => $_SESSION['counter_main_reqired']['last_name']);		
					$temp_cids = $_SESSION['counter_main_not_reqired']['case_ids'];
					if ($temp_cids != ""){
						if (substr($temp_cids, -1) != ','){
						  $_SESSION['counter_main_not_reqired']['case_ids'] .= ',';					
						} 
					}	
					// If notes form has been submitted
					if (isset($_POST['counter_main_submit'])){	
						// If errors found in the adding section
						if ($errors){
							$_SESSION['counter_reqired_errors'] = $errors;					
							// Display the error message				
							$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Required fields cannot be blank !</div>";
						}elseif(!$counterPartyModel->validate_cp_names_against_client_records($names_validations)){	
							// Display the error message				
							$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">This counter party cannot be saved : is a current client in the system !</div>";
						}else{	
								// If users' owned country count is equal to one then add user's session country else give the user to select one of country from the country list
								$country = (count($_SESSION['logged_user']['countries']) == 1) ? $_SESSION['logged_user']['countries'][0] : $_SESSION['counter_main_reqired']['cp_owned_country_id'];
						
								if (!empty($_SESSION['counter_main_not_reqired']['case_ids'])){
									if (strstr($_SESSION['counter_main_not_reqired']['case_ids'], ",")){
										$each_ids = explode(",", $_SESSION['counter_main_not_reqired']['case_ids']);						
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
										$each_ids = $_SESSION['counter_main_not_reqired']['case_ids'];															
									}	
	
									if (is_array($each_ids)){	
										
										foreach($each_ids as $each_id){
											if (!empty($each_id)){
												if (!$counterPartyModel->check_case_id_exist_in_system(trim($each_id))){
													$invalid_case_id = true;
													break;
												}	
												if (!$counterPartyModel->check_case_id_does_belongs_to_logged_users_owned_country_for_cp_in_add_mode(trim($each_id), $country)){
													$no_in_this_country = true;
													break;
												}
											}	
										}
									}else{
										if (!$counterPartyModel->check_case_id_exist_in_system(trim($each_ids))){
											$invalid_case_id = true;
										}
										if (!$counterPartyModel->check_case_id_does_belongs_to_logged_users_owned_country_for_cp_in_add_mode(trim($each_ids[$i]), $country)){
											$no_in_this_country = true;
										}
									}	
								}	
								
								if ($invalid_case_id){
									$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Case ID does not exist.</div>";	
									$_SESSION['counter_reqired_errors'] = "case_ids";
								}elseif ($no_in_this_country){
									$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">This case is not matching with selected Counter Party's country.</div>";																											
									$_SESSION['client_reqired_errors'] = "case_ids";
								}else{
									// Insert case details				   
									$_SESSION['newly_inserted_counter_id'] = $counterPartyModel->insert_counter_party_details(array_merge($_SESSION['counter_main_reqired'], $_SESSION['counter_main_not_reqired']), $_SESSION['curr_country']['country_code']);
	
									/* Start of the attachments saving */	
									// Create the attachments folder
									if(count($_SESSION['counter_party_main']['template_name']) > 0){
										$upload_folder_path = $_SERVER['DOCUMENT_ROOT'].DS."user-uploads/counter-parties";
										$cp_folder_path = $upload_folder_path.DS.$_SESSION['newly_inserted_counter_id'];
										if(!is_dir($cp_folder_path)){
											mkdir($cp_folder_path);
										}
							
										// Insert case templates details
										$cp_attach_details = array(
																	"name" => $_SESSION['counter_party_main']['template_name'], 
																	"cp_id" => $_SESSION['newly_inserted_counter_id']
																  );
										$counterPartyModel->insert_cp_attachments_details($cp_attach_details, "add");
									}
									/* End of the attachments saving */
									if (is_array($each_ids)){
										if (count($each_ids) > 1){
											$each_ids = array_unique($each_ids);
										}	
									}	
									$counterPartyModel->insert_owned_cases($_SESSION['newly_inserted_counter_id'], $each_ids);
									// Log keeping
									$log_params = array(
														"user_id" => $_SESSION['logged_user']['id'], 
														"action_desc" => "New CLIENT was saved. ID : {$_SESSION['newly_inserted_counter_id']}",
														"date_crated" => date("Y-m-d-H:i:s")
														);
									$counterPartyModel->keep_track_of_activity_log_in_counter_party($log_params);
									// Unset all used data
									unset($_SESSION['counter_main_not_reqired']);						
									unset($_SESSION['counter_main_reqired']);
									unset($_SESSION['counter_reqired_errors']);
									unset($_SESSION['counter_party_main']);																															
									$_SESSION['new_counter_added'] = "true";
									unset($counterPartyModel);	
									AppController::redirect_to($site_config['base_url']."counter-party/notes/?mode=add-notes");												
								}
							}
						}
						// If notes form has been submitted
						if (isset($_POST['counter_notes_submit'])){
							// If no value has been submitted regarding the notes section						
							if (empty($_POST['counter_party_note_categories_required'])){
								$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please select at least one category.</div>";		
							// If no value has been submitted regarding the notes section
							}elseif (empty($_POST['counter_note_required']['note_text'])){
								$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Required fields cannot be blank !</div>";		
							// If values has been submitted	
							}else{
								$_SESSION['counter_note_required']['note_text'] = $_POST['counter_note_required']['note_text'];							
								$notes_inputting_array = array(
																"counter_party_id" => $_SESSION['newly_inserted_counter_id'],
																"note_owner_type" => "COUNTER-PARTY",
																"note" => $_SESSION['counter_note_required']['note_text'],
																"added_by" => $_SESSION['logged_user']['id'],
																"added_date" => date("Y-m-d-H:i:s")
															  );
								$last_inserted_note_id = $counterPartyModel->insert_new_note($notes_inputting_array);
								// Inserting the notes related categories
								$notes_categories_params = array(
																	"note_id" => $last_inserted_note_id,
																	"notes_categories" => $_POST['counter_party_note_categories_required'],
																	"note_owner_section" => "COUNTER-PARTY"
																);	
								$counterPartyModel->insert_notes_categories($notes_categories_params);
								$_SESSION['new_note_has_created'] = "true";		
								// Log keeping
								$log_params = array(
													"user_id" => $_SESSION['logged_user']['id'], 
													"action_desc" => "Created a new note to COUNTER-PARTY ID {$_SESSION['newly_inserted_counter_id']}",
													"date_crated" => date("Y-m-d-H:i:s")
													);
								$counterPartyModel->keep_track_of_activity_log_in_counter_party($log_params);
								$notes_array = array('note_id', 'note');					
								$all_notes_to_this_counter_party = $counterPartyModel->retrieve_all_notes_owned_by_this_counter_party($_SESSION['newly_inserted_counter_id'], $notes_array, "COUNTER-PARTY");																																																	
								unset($_POST);									
								unset($_SESSION['counter_note_required']['note_text']);
								AppController::redirect_to($site_config['base_url'] ."counter-party/add/?mode=notes");									
							}
							$notes_array = array('note_id', 'note');					
							$all_notes_to_this_counter_party = $counterPartyModel->retrieve_all_notes_owned_by_this_counter_party($_SESSION['newly_inserted_counter_id'], $notes_array, "COUNTER-PARTY");																																																	
						}	
					}else{
						unset($_SESSION['counter_main_not_reqired']);						
						unset($_SESSION['counter_main_reqired']);
						unset($_SESSION['counter_reqired_errors']);						
						if (!isset($_SESSION['counter_main_reqired'])) unset($_SESSION['counter_party_main']);													
						unset($counterPartyModel);	
					}	
			break;
			
			case "view":
				// Unset all frist step session data
				unset($_SESSION['counter_main_not_reqired']);						
				unset($_SESSION['counter_main_reqired']);
				unset($_SESSION['counter_reqired_errors']);						
				unset($_SESSION['newly_inserted_counter_id']);	
				unset($_SESSION['counter_party_main']);																																																												
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
				global $all_counter_partys_count;
				global $all_counter_partys;
				$breadcrumb = "";
				$sortBy = "";
				$cur_path = "";
				$sortPath = "";																												
				// Bredcrmb to the pfa section
				$breadcrumb .= "<div class=\"breadcrumbMessageDiv defaultFont boldText\"><a href=\"".$site_config['base_url']."\" class=\"headerLink\">Home</a> &rsaquo; Manage Counter Parties</div>";
				// Object Instantiation
				$counterPartyModel = new CounterPartyModel(); 
				$pagination_obj = new Pagination();				
				// Display the success message after success case updation
				if ($_SESSION['is_not_counter_party_id'] == "true"){
					$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">This Counter Party id is not exist.</div>";										
				}
				unset($_SESSION['is_not_counter_party_id']);
				// Display the success message after success case updation
				if ($_SESSION['is_having_cases'] == "true"){
					$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Cannot delete this counter party : Some cases owned by this counter party.</div>";										
				}
				unset($_SESSION['is_having_cases']);
				// Display the success message after success case updation
				if ($_SESSION['counter-party_deleted'] == "true"){
					$headerDivMsg = "<div class=\"headerMessageDivInNotice defaultFont\">Successfully deleted the counter party.</div>";										
				}
				unset($_SESSION['counter-party_deleted']);
				// Configuring the action panel against user permissions
				$action_panel = array(
										array(
												"menu_id" => 1,
												"menu_text" => "Add / Edit Note",
												"menu_url" => $site_config['base_url']."counter-party/notes/?mode=edit-notes",
												"menu_img" => "<img src=\"../../public/images/notepadicon.png\" border=\"0\" alt=\"New / Edit Note\" />",
												"menu_permissions" => array(71, 73, 74, 75)
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
				$param_array = array('cp_owned_country_id', 'country_name', 'counter_party_id', 'title', 'first_name', 'last_name', 'resident_address', 'postal_address', 'land_phone', 'mobile_phone', 'email', 'company_name');
				// Display all pfac_records				
				if (isset($_GET['sort'])){	
				
					$imgDefault = "<a href=\"".$site_config['base_url']."counter-party/view/?sort=".$_GET['sort']."&by=asc".(isset($_GET['page']) ? "&page=1".$_GET['page'] : "")."\"><img src=\"../../public/images/s_asc.png\" border=\"0\" /></a>";			
					$imgAsc = "<a href=\"".$site_config['base_url']."counter-party/view/?sort=".$_GET['sort']."&by=asc".(isset($_GET['page']) ? "&page=1".$_GET['page'] : "")."\"><img src=\"../../public/images/s_asc.png\" border=\"0\" /></a>";			
					$imgDesc = "<a href=\"".$site_config['base_url']."counter-party/view/?sort=".$_GET['sort']."&by=desc".(isset($_GET['page']) ? "&page=1".$_GET['page'] : "")."\"><img src=\"../../public/images/s_desc.png\" border=\"0\" /></a>";								
					$img = (!isset($_GET['by'])) ? ($imgDefault) : (($_GET['by'] == "asc") ? $imgDesc : $imgAsc);

					$sort_param_array = array(
											  "f_name" => "first_name", "l_name" => "last_name", "pos_add" => "postal_address", "mob" => "mobile_phone", "own_country" => "country_name",
											  "res" => "resident_address", "l_phone" => "land_phone", "comp" => "company_name", "email" => "email"
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
				$all_counter_partys = $counterPartyModel->display_all_counter_partys($param_array, $cur_page, $sortBy, ((isset($_GET['by'])) ? $_GET['by'] : ""), $_SESSION['logged_user']['countries'], $_SESSION['curr_country']['country_code']);
				$all_counter_partys_count = $counterPartyModel->display_counter_partys_all_count($param_array, $_SESSION['logged_user']['countries'], $_SESSION['curr_country']['country_code']);
				// Pagination load
				$pagination = $pagination_obj->generate_pagination($all_counter_partys_count, $curPath, NO_OF_RECORDS_PER_PAGE_DEFAULT);				
				$tot_page_count = ceil($all_counter_partys_count/NO_OF_RECORDS_PER_PAGE_DEFAULT);				
				// If no records found or no pages found
				$page = (isset($_GET['page'])) ? $_GET['page'] : 1;
				if (($page > $tot_page_count) || ($page == 0)){
					$invalidPage = true;	
				}
				// Unset all used variables
				unset($counterPartyModel);
				unset($pagination_obj);
			break;			
		
			case "edit":			
				// All Global variables
				global $full_details;
				global $headerDivMsg;
				global $site_config;
				global $breadcrumb;
				global $printHtml;
				global $all_notes_to_this_counter_party_in_edit;
				global $all_notes_count_to_this_counter_party_in_edit;
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
				global $counter_party_id;
				global $counterPartyModel;
				global $pre_counter_party_attachments;
				global $all_countries;
				$invalid_case_ids = false;				
				$breadcrumb = "";
				// Bredcrmb to the pfa section				
				$counter_party_id = trim($_GET['cp_id']);
				$breadcrumb .= "<div class=\"breadcrumbMessageDiv defaultFont boldText\"><a href=\"".$site_config['base_url']
								."\" class=\"headerLink\">Home</a> ";
				if (isset($_GET['opt'])){
					$breadcrumb .= "&rsaquo; <a class=\"headerLink\" href=\"".$site_config['base_url']."case/view/\">All Counter Parties</a> &rsaquo; <a class=\"headerLink\" href=\"".
										$site_config['base_url']."counter-party/edit/?mode=main&cp_id=".$counter_party_id."&page=1"."\">Edit Counter Party</a> &rsaquo; ".
										"<a class=\"headerLink\" href=\"".$site_config['base_url']."counter-party/edit/?mode=notes&cp_id=".$counter_party_id."&page=1&notes_page=1"."\">Your all notes</a>";
					if ($_GET['opt'] == "view"){
						$breadcrumb	.= " &rsaquo; View single note";				
					}else{
						$breadcrumb	.= " &rsaquo; Edit single note";				
					}										
				}else{
					$breadcrumb .= " &rsaquo; <a class=\"headerLink\" href=\"".$site_config['base_url']."counter-party/view/\">All Counter Parties</a> &rsaquo; Edit Counter Party";					
				}
				$breadcrumb .= "</div>";												
				// Generate the top header menu
				$printHtml = "";
				$printHtml .= ((isset($_GET['mode'])) && ($_GET['mode'] == "main")) ? "<span class=\"headerTopicSelected\">Main Details</span>" : "<span><a href=\"?mode=main&cp_id=".trim($_GET['cp_id']).((isset($_GET['page'])) ? "&page=".$_GET['page'] : "")."\">Main Details</a></span>";					
				$printHtml .= "&nbsp;&nbsp;<span class=\"ar\">&rsaquo;</span>&nbsp;&nbsp;";
				$printHtml .= ((isset($_GET['mode'])) && ($_GET['mode'] == "edit-notes")) ? "<span class=\"headerTopicSelected\">Notes</span>" : "<span><a href=\"".$site_config['base_url']."counter-party/notes/?mode=edit-notes&cp_id=".trim($_GET['cp_id']).((isset($_GET['page'])) ? "&page=".$_GET['page'] : "").((isset($_GET['notes_page'])) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\">Notes</a></span>";					
				// Object Instantiation
				$counterPartyModel = new CounterPartyModel(); 
				// Display the success message after success case updation
				if ($_SESSION['counter_party_details_updated'] == "true"){
					$headerDivMsg = "<div class=\"headerMessageDivInSuccess defaultFont\">Successfully updated the Counter Party.&nbsp;&nbsp;<a class='edtingMenusLink_in_view' href='".
							$site_config['base_url']."counter-party/show/?cp_id=".$counter_party_id."'>View details.</a></div>";																																			
					
				}
				unset($_SESSION['counter_party_details_updated']);
				// Switch to the correct sub view
				switch($mode){
					
					case "main": 
						$whichFormToInclude = "main"; 
						// Load the case main variables
						$allTitles = CommonFunctions::retrieve_titles_list();
						$pre_counter_party_attachments = $counterPartyModel->retrieve_all_counter_party_attachments_for_this_counter_party($counter_party_id);
						if (count($_SESSION['logged_user']['countries']) > 1){
							// Retieeve all counties
							$countires_params = array("country_id", "country_name");
							$all_countries = $counterPartyModel->retrieve_all_countires_for_users($countires_params);
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
						if ($_SESSION['counter_party_updated'] == "true"){
							$headerDivMsg = "<div class=\"headerMessageDivInSuccess defaultFont\">Counter Party details was updated successfully.&nbsp;&nbsp;<a class='edtingMenusLink_in_view' href='".
									$site_config['base_url']."counter-party/show/?cp_id=".$counter_party_id."'>View details.</a></div>";																																			
						}					
						unset($_SESSION['counter_party_updated']);
						// Display the success message after succesful updation of contact details						
						if ($_SESSION['notes_section_got_updated'] == "true"){
							$headerDivMsg = "<div class=\"headerMessageDivInSuccess defaultFont\">The above note was updated successfully.</div>";																																			
						}					
						unset($_SESSION['notes_section_got_updated']);
						// Sorting of the notes section
						$cur_path = $_SERVER['REQUEST_URI']; 						
						// Load all notes regarding to this counter-party						
						$notes_array = array('note_id', 'note', 'username', 'date_modified', 'added_date', 'modified_by');						
						// Grab the current page
						$cur_page = ((isset($_GET['notes_page'])) && ($_GET['notes_page'] != "") && ($_GET['notes_page'] != 0)) ? $_GET['notes_page'] : 1; 												
						// Grab the pfac id
						if (isset($_GET['sort'])){	

							$imgDefault = "<a href=\"".$site_config['base_url']."counter-party/edit/?mode=notes&cp_id=".$counter_party_id."&opt=sorting&sort=".$_GET['sort']."&by=asc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\"><img src=\"../../public/images/s_asc.png\" border=\"0\" /></a>";			
							$imgAsc = "<a href=\"".$site_config['base_url']."counter-party/edit/?mode=notes&cp_id=".$counter_party_id."&opt=sorting&sort=".$_GET['sort']."&by=asc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\"><img src=\"../../public/images/s_asc.png\" border=\"0\" /></a>";			
							$imgDesc = "<a href=\"".$site_config['base_url']."counter-party/edit/?mode=notes&cp_id=".$counter_party_id."&opt=sorting&sort=".$_GET['sort']."&by=desc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\"><img src=\"../../public/images/s_desc.png\" border=\"0\" /></a>";								
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
							if (empty($_POST['counter_party_note_categories_required'])){
								$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please select at least one category to filter.</div>";										
							}else{
								$filtering = true;
							}
						}
						// retrieve all notes and all notes couunt to this counter-party
						$all_notes_to_this_counter_party_in_edit = $counterPartyModel->retrieve_all_notes_owned_by_this_counter_party_only_for_the_edit_view($notes_array, $cur_page, $sortBy, ((isset($_GET['by'])) ? $_GET['by'] : ""), trim($_GET['cp_id']), $filtering, $_POST['counter_party_note_categories_required']);
						$all_notes_count_to_this_counter_party_in_edit = $counterPartyModel->retrieve_all_notes_count_owned_by_this_counter_party_only_for_the_edit_view(trim($_GET['cp_id']), $notes_array);
						$pagination = $pagination_obj->generate_pagination($all_notes_count_to_this_counter-party_in_edit, $cur_path, $cur_page, NO_OF_RECORDS_PER_PAGE_DEFAULT);				
						$tot_page_count = ceil($all_notes_count_to_this_counter-party_in_edit/NO_OF_RECORDS_PER_PAGE_DEFAULT);				
						// Delete the selected note	
						if ((isset($_GET['opt'])) && ($_GET['opt'] == "drop")){
							$counterPartyModel->delete_selected_note($_GET['note_id'], $counter_party_id, "COUNTER-PARTY");
							// Delete notes category relation as well regarding the note deletion
							$counterPartyModel->remove_previous_notes_categories_relation(trim($_GET['note_id']));							
							$_SESSION['deleted_selected_note'] = "true";
							// Log keeping
							$log_params = array(
												"user_id" => $_SESSION['logged_user']['id'], 
												"action_desc" => "Deleted the note of COUNTER-PARTY ID {$counter_party_id}",
												"date_crated" => date("Y-m-d-H:i:s")
												);
							$counterPartyModel->keep_track_of_activity_log_in_counter_party($log_params);
							AppController::redirect_to($site_config['base_url']."counter-party/edit/?mode=notes&cp_id=".$counter_party_id.((isset($_GET['page'])) ? "&page=".$_GET['page'] : "").((isset($_GET['notes_page'])) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1"));							
						}
						// Show full details of the above counter-party selected note
						if ((isset($_GET['opt'])) && (($_GET['opt'] == "view") || ($_GET['opt'] == "edit"))){						
							// Check the note id exist in the db
							if ($counterPartyModel->check_note_id_exist(trim($_GET['note_id']))){						
								if ($counterPartyModel->check_note_id_owned_by_the_correct_counter_party(trim($_GET['cp_id']), trim($_GET['note_id']))){
									$note_param = array("note_id", "note", "added_date", "username", "modified_by", "date_modified");
									$note_full_details = $counterPartyModel->retrieve_full_details_of_selected_note(trim($_GET['note_id']), $note_param, "COUNTER-PARTY");				
								}else{
									AppController::redirect_to($site_config['base_url'] ."counter-party/view/");	
								}	
							}else{
								AppController::redirect_to($site_config['base_url'] ."counter-party/view/");																						
							}	
						}	
					break;										
				}
				// Check whether the case id is exist in the database		
				if (
					($counterPartyModel->check_counter_party_id_exist_in_system(trim($_GET['cp_id']))) &&
					($counterPartyModel->check_counter_party_id_can_be_visble_to_requested_user_by_country($_SESSION['logged_user']['countries'], trim($_GET['cp_id']))) &&
					($counterPartyModel->check_counter_party_country_with_curr_selected_country($_SESSION['curr_country']['country_code'], trim($_GET['cp_id'])))										
				   )	
					{
					$full_details = $counterPartyModel->retrieve_full_details_per_each_counter_party(trim($_GET['cp_id']));	
					$counter_party_id = $full_details[0]['counter_party_id'];
					$case_ids = $counterPartyModel->retrieve_owned_case_ids_by_this_counter_party($counter_party_id);
					// Start to validate and other main functions				
					if ("POST" == $_SERVER['REQUEST_METHOD']){
					$errors = AppModel::validate($_POST['counter_main_reqired']);
					$_SESSION['counter_main_reqired'] = $_POST['counter_main_reqired'];
					$_SESSION['counter_main_not_reqired'] = $_POST['counter_main_not_reqired'];
					$temp_cids = $_SESSION['counter_main_not_reqired']['case_ids'];
					if ($temp_cids != ""){
						if (substr($temp_cids, -1) != ','){
						  $_SESSION['counter_main_not_reqired']['case_ids'] .= ',';					
						} 
					}	
					// If notes form has been submitted
					if (isset($_POST['counter_main_submit'])){	
						// If errors found in the adding section
						if ($errors){
							$_SESSION['counter_reqired_errors'] = $errors;					
							// Display the error message				
							$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Required fields cannot be blank !</div>";
						}else{	
						
								if (!empty($_SESSION['counter_main_not_reqired']['case_ids'])){
									if (strstr($_SESSION['counter_main_not_reqired']['case_ids'], ",")){
										$each_ids = explode(",", $_SESSION['counter_main_not_reqired']['case_ids']);						
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
										$each_ids = $_SESSION['counter_main_not_reqired']['case_ids'];															
									}	
									if (is_array($each_ids)){	
										
										foreach($each_ids as $each_id){
											if (!empty($each_id)){
												if (!$counterPartyModel->check_case_id_exist_in_system(trim($each_id))){
													$invalid_case_id = true;
													break;
												}
	
												if (!$counterPartyModel->check_case_id_does_belongs_to_logged_users_owned_country_for_cp(trim($each_id), $counter_party_id)){
													$no_in_this_country = true;
													break;
												}
											}	
										}
									}else{
									
										if (!$counterPartyModel->check_case_id_exist_in_system(trim($each_ids))){
											$invalid_case_id = true;
											break;
										}	
										if (!$counterPartyModel->check_case_id_does_belongs_to_logged_users_owned_country_for_cp(trim($each_ids[$i]), $counter_party_id)){
											$no_in_this_country = true;
											break;
										}
										//if ($counterPartyModel->notifying_about_the_case_adding_for_the_duplications(trim($each_ids), $counter_party_id)){
											//$duplicate_case_id = true;
										//}	
									}	
								}	
						 
								if ($invalid_case_id){
									$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Case ID does not exist.</div>";																											
									$_SESSION['counter_reqired_errors']['case_ids'] = "case_ids";
								}elseif ($no_in_this_country){
									$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">This case is not matching with selected Counter Party's country.</div>";																											
									$_SESSION['counter_reqired_errors'] = "case_ids";
								//}elseif ($duplicate_case_id){
									//$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">This case is previoulsly assigned to this counter party.</div>";																											
									//_SESSION['counter_reqired_errors'] = "case_ids";
								}else{
										
									// If users' owned country count is equal to one then add user's session country else give the user to select one of country from the country list
									$country = (count($_SESSION['logged_user']['countries']) == 1) ? $_SESSION['logged_user']['countries'][0] : $full_details[0]['cp_owned_country_id'];
									// Insert case details				   
									$counterPartyModel->update_counter_party_details(array_merge($_SESSION['counter_main_reqired'], $_SESSION['counter_main_not_reqired']), $counter_party_id, $country);
									// Delete previously owned case ids
									$counterPartyModel->delete_previous_owned_case_ids_by_this_counter_party($counter_party_id);
									
									/* Start of the attachments saving */	
									// Create the attachments folder
									if(count($_SESSION['counter_party_main']['template_name']) > 0){
										$upload_folder_path = $_SERVER['DOCUMENT_ROOT'].DS."user-uploads/counter-parties";
										$cp_folder_path = $upload_folder_path.DS.$counter_party_id;
										if(!is_dir($cp_folder_path)){
											mkdir($cp_folder_path);
										}
							
										// Insert case templates details
										$cp_attach_details = array(
																	"name" => $_SESSION['counter_party_main']['template_name'], 
																	"cp_id" => $counter_party_id
																  );
										$counterPartyModel->insert_cp_attachments_details($cp_attach_details, "edit");
									}	
									/* End of the attachments saving */
									
									// Inserting new case ids
									if (is_array($each_ids)){
										if (count($each_ids) > 1){
											$each_ids = array_unique($each_ids);
										}	
									}	
									$counterPartyModel->insert_owned_cases($counter_party_id, $each_ids);
									// Log keeping
									$log_params = array(
														"user_id" => $_SESSION['logged_user']['id'], 
														"action_desc" => "COUNTER-PARTY details was updated. ID : $counter_party_id",
														"date_crated" => date("Y-m-d-H:i:s")
														);
									$counterPartyModel->keep_track_of_activity_log_in_counter_party($log_params);
									// Unset all used data
									unset($_SESSION['counter_main_not_reqired']);						
									unset($_SESSION['counter_main_reqired']);
									unset($_SESSION['counter_reqired_errors']);
									$_SESSION['counter_party_details_updated'] = "true";																	
									unset($counterPartyModel);	
									AppController::redirect_to($site_config['base_url']."counter-party/edit/?mode=main&cp_id=".$counter_party_id.((isset($_GET['page'])) ? "&page=".$_GET['page'] : ""));												
								}
							}
						}
						// If notes form has been submitted
						if (isset($_POST['counter_notes_submit'])){
							// If no value has been submitted regarding the notes section
							if (empty($_POST['counter_note_required']['note_text'])){
								$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Required fields cannot be blank !</div>";		
							// If values has been submitted	
							}else{
								$_SESSION['counter_note_required']['note_text'] = $_POST['counter_note_required']['note_text'];							
								$notes_inputting_array = array(
																"counter_party_id" => $counter_party_id,
																"note_owner_type" => "COUNTER-PARTY",
																"note" => $_SESSION['counter_note_required']['note_text'],
																"added_by" => $_SESSION['logged_user']['id'],
																"added_date" => date("Y-m-d-H:i:s")
															  );
								$last_inserted_note_id = $counterPartyModel->insert_new_note($notes_inputting_array);
								// Inserting the notes related categories
								$notes_categories_params = array(
																	"note_id" => $last_inserted_note_id,
																	"notes_categories" => $_POST['counter_party_note_categories_required'],
																	"note_owner_section" => "COUNTER-PARTY"
																);	
								$counterPartyModel->insert_notes_categories($notes_categories_params);
								$_SESSION['new_note_has_created'] = "true";		
								// Log keeping
								$log_params = array(
													"user_id" => $_SESSION['logged_user']['id'], 
													"action_desc" => "Created a new note to COUNTER-PARTY ID {$counter_party_id}",
													"date_crated" => date("Y-m-d-H:i:s")
													);
								$counterPartyModel->keep_track_of_activity_log_in_counter_party($log_params);
								$notes_array = array('note_id', 'note');					
								$all_notes_to_this_counter_party = $counterPartyModel->retrieve_all_notes_owned_by_this_counter_party($counter_party_id, $notes_array, "COUNTER-PARTY");																																																	
								unset($_POST);									
								unset($_SESSION['counter_note_required']['note_text']);
								AppController::redirect_to($site_config['base_url'] ."counter-party/edit/?mode=notes&cp_id=".$counter_party_id.((isset($_GET['page'])) ? "&page=".$_GET['page'] : "").((isset($_GET['notes_page'])) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1"));									
							}
							$notes_array = array('note_id', 'note');					
							$all_notes_to_this_counter_party = $counterPartyModel->retrieve_all_notes_owned_by_this_counter_party($counter_party_id, $notes_array);																																																	
						}	
						// If notes form has been submitted in edit note section
						if (isset($_POST['counter_notes_update_submit'])){
							// Check the note id exist in the db
							if ($counterPartyModel->check_note_id_exist(trim($_GET['note_id']))){
								// Check the note is owned by him self
								if ($counterPartyModel->check_note_id_owned_by_the_correct_counter_party(trim($_GET['cp_id']), trim($_GET['note_id']))){								
									// If no value has been submitted regarding the notes section						
									if (empty($_POST['counter_party_note_categories_required'])){
										$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please select at least one category.</div>";		
									// If no value has been submitted regarding the notes section
									}elseif (empty($_POST['counter_note_required']['note_text'])){
										$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Required fields cannot be blank !</div>";		
									// If both values has been submitted	
									}else{
										$_SESSION['counter_note_required']['note_text'] = $_POST['counter_note_required']['note_text'];							
										$notes_inputting_array = array(
															"counter_party_id" => $counter_party_id,
															"note_owner_type" => "COUNTER-PARTY",													 
															"note" => $_SESSION['counter_note_required']['note_text'],
															"date_modified" => date("Y-m-d-H:i:s"),
															"modified_by" => $_SESSION['logged_user']['id']
															);
										$counterPartyModel->update_the_exsiting_note($notes_inputting_array, trim($_GET['note_id']));
										// Remove previous notes categories relation
										$counterPartyModel->remove_previous_notes_categories_relation(trim($_GET['note_id']));										
										// Inserting the notes related categories
										$notes_categories_params = array(
																			"note_id" => trim($_GET['note_id']),
																			"notes_categories" => $_POST['counter_party_note_categories_required'],
																			"note_owner_section" => "COUNTER-PARTY"
																		);	
										$counterPartyModel->insert_notes_categories($notes_categories_params);
										// Log keeping
										$log_params = array(
															"user_id" => $_SESSION['logged_user']['id'], 
															"action_desc" => "Update a note of COUNTER-PARTY ID {$counter_party_id}",
															"date_crated" => date("Y-m-d-H:i:s")
															);
										$counterPartyModel->keep_track_of_activity_log_in_counter_party($log_params);
										$notes_array = array('note_id', 'note', 'username', 'added_date', 'modified_date');						
										$note_full_details = $counterPartyModel->retrieve_full_details_of_selected_note($_GET['note_id'], $note_param, "COUNTER-PARTY");				
										unset($_POST);
										unset($_SESSION['counter_note_required']['note_text']);
										$_SESSION['notes_section_got_updated'] = "true";
										AppController::redirect_to($site_config['base_url']."counter-party/edit/?mode=notes&cp_id=".$counter_party_id.((isset($_GET['page'])) ? "&page=".$_GET['page'] : "").((isset($_GET['notes_page'])) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1"));												
									}
								// If note id is not owned by him self																				
								}else{
									unset($_SESSION['counter_note_required']['note_text']);
									AppController::redirect_to($site_config['base_url'] ."counter-party/view/");	
								}
							// If wrong note id										
							}else{
								unset($_SESSION['counter_note_required']['note_text']);
								AppController::redirect_to($site_config['base_url'] ."counter-party/view/");	
							}	
						}
					}else{
						unset($_SESSION['counter_main_not_reqired']);						
						unset($_SESSION['counter_main_reqired']);
						unset($_SESSION['counter_reqired_errors']);
						unset($counterPartyModel);	
						unset($full_details);
					}
				// If wrong id	
				}else{
					AppController::redirect_to($site_config['base_url'] ."counter-party/view/");
				}
			break;
			
			case "drop":
				// All global variables		
				global $site_config;
				$case_folder_path = "";				
				// objects instantiation
				$counterPartyModel = new CounterPartyModel();
				// Check whether the user id is exist in the database						
				if (
					(!$counterPartyModel->check_counter_party_id_exist_in_system(trim($_GET['cp_id']))) &&
					(!$counterPartyModel->check_counter_party_id_can_be_visble_to_requested_user_by_country($_SESSION['logged_user']['countries'], trim($_GET['cp_id'])))					
				   )	
				{
					$_SESSION['is_not_counter-party_id'] = "true";				
					$rest_of_deletion_proceed = false;									
				// Check whether the selected user have been responsible for any case
				}elseif ($counterPartyModel->is_having_any_cases(trim($_GET['cp_id']))){
					// Dlete client owned cases from client__cases table
					$counterPartyModel->delete_cases_involved_with_this_counter_party(trim($_GET['cp_id']));
					$rest_of_deletion_proceed = true;
				// If no cases assigned
				}else{
					$rest_of_deletion_proceed = true;				
				}
				
				if ($rest_of_deletion_proceed){
					// Deleting the counter-partys notes
					$counterPartyModel->delete_owned_note(trim($_GET['cp_id']));					
					// Actually deleting the PHARO user from the users table
					$counterPartyModel->delete_user_details_from_the_table(trim($_GET['cp_id']));
					// Delete the case templates names
					$counterPartyModel->delete_cp_attachments_names(trim($_GET['cp_id']));
					// Remove case templates
					$case_folder_path = SERVER_ROOT.DS.'user-uploads'.DS.'counter-parties'.DS.trim($_GET['cp_id']);
					if(is_dir($case_folder_path)) { CommonFunctions::delete_entries($case_folder_path); }
					// Log keeping
					$log_params = array(
										"user_id" => $_SESSION['logged_user']['id'], 
										"action_desc" => "Deleted COUNTER-PARTY. ID : ".$_GET['cp_id'],
										"date_crated" => date("Y-m-d-H:i:s")
										);
					$counterPartyModel->keep_track_of_activity_log_in_counter_party($log_params);
					$_SESSION['counter-party_deleted'] = "true";
					// Unset all used variables	
				}
				unset($counterPartyModel);
				AppController::redirect_to($site_config['base_url'] ."counter-party/view/");																										
			break;			
			
			case "show":
				// All global variables		
				global $fullDetails;
				global $breadcrumb;
				global $site_config;
				global $cases_owned;
				global $counterPartyModel;
				$breadcrumb = "";
				// objects instantiation
				$counterPartyModel = new CounterPartyModel(); 
				// Bredcrmb to the pfa section
				$breadcrumb .= "<div class=\"breadcrumbMessageDiv defaultFont boldText\"><a href=\"".$site_config['base_url']
								."\" class=\"headerLink\">Home</a> &rsaquo; "."<a class=\"headerLink\" href=\"".$site_config['base_url']."counter-party/view/\">All Counter Parties</a> &rsaquo; View Counter Party</div>";
				// Check whether the CASE id is exist in the database		
				if (
					($counterPartyModel->check_counter_party_id_exist_in_system(trim($_GET['cp_id']))) &&
				    ($counterPartyModel->check_counter_party_id_can_be_visble_to_requested_user_by_country($_SESSION['logged_user']['countries'], trim($_GET['cp_id']))) &&																				
					($counterPartyModel->check_counter_party_country_with_curr_selected_country($_SESSION['curr_country']['country_code'], trim($_GET['cp_id'])))															
				   )					
				{
					$fullDetails = $counterPartyModel->grab_full_details_for_the_single_view_in_counter_party(trim($_GET['cp_id']));		
					$cases_owned = $counterPartyModel->retrieve_owned_case_ids_by_this_counter_party(trim($_GET['cp_id']));
					// Log keeping
					$log_params = array(
										"user_id" => $_SESSION['logged_user']['id'], 
										"action_desc" => "Viewed details of COUNTER-PARTY ID : {$_GET['cp_id']}",
										"date_crated" => date("Y-m-d-H:i:s")
										);
					$counterPartyModel->keep_track_of_activity_log_in_counter_party($log_params);
				// If wrong id	
				}else{
					AppController::redirect_to($site_config['base_url'] ."counter-party/view/");							
				}
				unset($counterPartyModel);
				unset($fullDetails);
				unset($cases_owned);
			break;						
			
			case "notes":			
				// All Global variables
				global $headerDivMsg;
				global $site_config;
				global $breadcrumb;
				global $printHtml;
				global $all_notes_to_this_counter_party;
				global $all_notes_to_this_counter_party_in_edit;
				global $all_notes_count_to_this_counter_party_in_edit;
				global $note_full_details;
				global $pagination;
				global $tot_page_count;
				global $img;
				global $headerDivMsg;
				global $site_config;
				global $printHtml;
				global $breadcrumb;
				global $martial_status;
				global $counter_party_id;
				global $counterPartyModel;
				$invalid_case_ids = false;				
				$breadcrumb = "";
				// Bredcrmb to the pfa section				
				$counter_party_id = trim($_GET['cp_id']);
				$breadcrumb .= "<div class=\"breadcrumbMessageDiv defaultFont boldText\"><a href=\"".$site_config['base_url']
								."\" class=\"headerLink\">Home</a> ";
				if (isset($_GET['opt'])){
					$breadcrumb .= "&rsaquo; <a class=\"headerLink\" href=\"".$site_config['base_url']."case/view/\">All Counter Parties</a> &rsaquo; <a class=\"headerLink\" href=\"".
										$site_config['base_url']."counter-party/edit/?mode=main&cp_id=".$counter_party_id."&page=1"."\">Edit Counter Party</a> &rsaquo; ".
										"<a class=\"headerLink\" href=\"".$site_config['base_url']."counter-party/notes/?mode=edit-notes&cp_id=".$counter_party_id."&page=1&notes_page=1"."\">Your all notes</a>";
					if ($_GET['opt'] == "view"){
						$breadcrumb	.= " &rsaquo; View single note";				
					}else{
						$breadcrumb	.= " &rsaquo; Edit single note";				
					}										
				}else{
					$breadcrumb .= " &rsaquo; <a class=\"headerLink\" href=\"".$site_config['base_url']."counter-party/view/\">All Counter Parties</a> &rsaquo; Edit Counter Party";					
				}
				$breadcrumb .= "</div>";												
				// Generate the top header menu
				if ($_GET['mode'] == "edit-notes"){
					$printHtml = "";
					
					if (in_array("15", $_SESSION['logged_user']['permissions'])){
						$printHtml .= ((isset($_GET['mode'])) && ($_GET['mode'] == "main")) ? "<span class=\"headerTopicSelected\">Main Details</span>" : "<span><a href=\"".$site_config['base_url']."counter-party/edit/?mode=main&cp_id=".trim($_GET['cp_id']).((isset($_GET['page'])) ? "&page=".$_GET['page'] : "")."\">Main Details</a></span>";					
						$printHtml .= "&nbsp;&nbsp;<span class=\"ar\">&rsaquo;</span>&nbsp;&nbsp;";
					}
					
					$printHtml .= ((isset($_GET['mode'])) && ($_GET['mode'] == "edit-notes")) ? "<span class=\"headerTopicSelected\">Notes</span>" : "<span><a href=\"".$site_config['base_url']."case/notes/?mode=edit-notes&ref_no=".trim(urlencode($_GET['ref_no'])).((isset($_GET['page'])) ? "&page=".$_GET['page'] : "").((isset($_GET['notes_page'])) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\">Notes</a></span>";					
				}else{
					$printHtml = "Notes";					
				}
				// Object Instantiation
				$counterPartyModel = new CounterPartyModel(); 
				// If the correct post back not submitted then redirect the user to the correct page
				if (($mode == "notes") && (!isset($_SESSION['newly_inserted_counter_id']))){
					AppController::redirect_to($site_config['base_url'] ."counter-party/add/?mode=main");
				}
				// Switch to the correct sub view
				switch($mode){
					
					case "add-notes": 
						$whichFormToInclude = "notes"; 						
						// Display the success message in new contact adding
						if ($_SESSION['new_counter_added'] == "true"){					
							$headerDivMsg = "<div class=\"headerMessageDivInSuccess defaultFont\">New counter-party was added successfully.&nbsp;&nbsp;<a class='edtingMenusLink_in_view' href='".
									$site_config['base_url']."counter-party/show/?cp_id=".$_SESSION['newly_inserted_counter_id']."'>View details.</a></div>";																																			
						}
						unset($_SESSION['new_counter_added']);	
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
						$counter_party_id = $_SESSION['newly_inserted_counter_id'];
						// Load last inserted notes by last inserted user
						$notes_array = array('note_id', 'note');					
						$all_notes_to_this_counter_party = $counterPartyModel->retrieve_all_notes_owned_by_this_counter_party($_SESSION['newly_inserted_counter_id'], $notes_array, "COUNTER-PARTY");
						// Delete the selected note	
						if ((isset($_GET['opt'])) && ($_GET['opt'] == "drop")){
							$counterPartyModel->delete_selected_note(trim($_GET['note_id']), $counter_party_id, "COUNTER-PARTY");
							$_SESSION['deleted_selected_note'] = "true";
							// Log keeping
							$log_params = array(
												"user_id" => $_SESSION['logged_user']['id'], 
												"action_desc" => "Deleted the note of COUNTER-PARTY ID {$counter_party_id}",
												"date_crated" => date("Y-m-d-H:i:s")
												);
							$counterPartyModel->keep_track_of_activity_log_in_counter_party($log_params);
							AppController::redirect_to($site_config['base_url']."counter-party/notes/?mode=add-notes");
						}
						// Start to validate and other main functions				
						if ("POST" == $_SERVER['REQUEST_METHOD']){
							// If notes form has been submitted
							if (isset($_POST['counter_notes_submit'])){
								// If no value has been submitted regarding the notes section
								if (empty($_POST['counter_note_required']['note_text'])){
									$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Required fields cannot be blank !</div>";		
								// If values has been submitted	
								}else{
									$_SESSION['counter_note_required']['note_text'] = $_POST['counter_note_required']['note_text'];							
									$notes_inputting_array = array(
																	"counter_party_id" => $counter_party_id,
																	"note_owner_type" => "COUNTER-PARTY",
																	"note" => $_SESSION['counter_note_required']['note_text'],
																	"added_by" => $_SESSION['logged_user']['id'],
																	"added_date" => date("Y-m-d-H:i:s")
																  );
									$last_inserted_note_id = $counterPartyModel->insert_new_note($notes_inputting_array);
									// Inserting the notes related categories
									$notes_categories_params = array(
																		"note_id" => $last_inserted_note_id,
																		"notes_categories" => $_POST['counter_party_note_categories_required'],
																		"note_owner_section" => "COUNTER-PARTY"
																	);	
									$counterPartyModel->insert_notes_categories($notes_categories_params);
									$_SESSION['new_note_has_created'] = "true";		
									// Log keeping
									$log_params = array(
														"user_id" => $_SESSION['logged_user']['id'], 
														"action_desc" => "Created a new note to COUNTER-PARTY ID {$counter_party_id}",
														"date_crated" => date("Y-m-d-H:i:s")
														);
									$counterPartyModel->keep_track_of_activity_log_in_counter_party($log_params);
									$notes_array = array('note_id', 'note');					
									$all_notes_to_this_counter_party = $counterPartyModel->retrieve_all_notes_owned_by_this_counter_party($counter_party_id, $notes_array, "COUNTER-PARTY");																																																	
									unset($_POST);									
									unset($_SESSION['counter_note_required']['note_text']);
									AppController::redirect_to($site_config['base_url'] ."counter-party/notes/?mode=add-notes");									
								}
								$notes_array = array('note_id', 'note');					
								$all_notes_to_this_counter_party = $counterPartyModel->retrieve_all_notes_owned_by_this_counter_party($counter_party_id, $notes_array);																																																	
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
						// Load all notes regarding to this counter-party						
						$notes_array = array('note_id', 'note', 'username', 'date_modified', 'added_date', 'modified_by');						
						// Grab the current page
						$cur_page = ((isset($_GET['notes_page'])) && ($_GET['notes_page'] != "") && ($_GET['notes_page'] != 0)) ? $_GET['notes_page'] : 1; 												
						// Grab the pfac id
						if (isset($_GET['sort'])){	

							$imgDefault = "<a href=\"".$site_config['base_url']."counter-party/edit/?mode=notes&cp_id=".$counter_party_id."&opt=sorting&sort=".$_GET['sort']."&by=asc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\"><img src=\"../../public/images/s_asc.png\" border=\"0\" /></a>";			
							$imgAsc = "<a href=\"".$site_config['base_url']."counter-party/edit/?mode=notes&cp_id=".$counter_party_id."&opt=sorting&sort=".$_GET['sort']."&by=asc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\"><img src=\"../../public/images/s_asc.png\" border=\"0\" /></a>";			
							$imgDesc = "<a href=\"".$site_config['base_url']."counter-party/edit/?mode=notes&cp_id=".$counter_party_id."&opt=sorting&sort=".$_GET['sort']."&by=desc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "").(isset($_GET['notes_page']) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1")."\"><img src=\"../../public/images/s_desc.png\" border=\"0\" /></a>";								
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
							if (empty($_POST['counter_party_note_categories_required'])){
								$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please select at least one category to filter.</div>";										
							}else{
								$filtering = true;
							}
						}
						// retrieve all notes and all notes couunt to this counter-party
						$all_notes_to_this_counter_party_in_edit = $counterPartyModel->retrieve_all_notes_owned_by_this_counter_party_only_for_the_edit_view($notes_array, $cur_page, $sortBy, ((isset($_GET['by'])) ? $_GET['by'] : ""), trim($_GET['cp_id']), $filtering, $_POST['counter_party_note_categories_required']);
						$all_notes_count_to_this_counter_party_in_edit = $counterPartyModel->retrieve_all_notes_count_owned_by_this_counter_party_only_for_the_edit_view(trim($_GET['cp_id']), $notes_array);
						$pagination = $pagination_obj->generate_pagination($all_notes_count_to_this_counter-party_in_edit, $cur_path, $cur_page, NO_OF_RECORDS_PER_PAGE_DEFAULT);				
						$tot_page_count = ceil($all_notes_count_to_this_counter-party_in_edit/NO_OF_RECORDS_PER_PAGE_DEFAULT);				
						// Delete the selected note	
						if ((isset($_GET['opt'])) && ($_GET['opt'] == "drop")){
							$counterPartyModel->delete_selected_note($_GET['note_id'], $counter_party_id, "COUNTER-PARTY");
							// Delete notes category relation as well regarding the note deletion
							$counterPartyModel->remove_previous_notes_categories_relation(trim($_GET['note_id']));							
							$_SESSION['deleted_selected_note'] = "true";
							// Log keeping
							$log_params = array(
												"user_id" => $_SESSION['logged_user']['id'], 
												"action_desc" => "Deleted the note of COUNTER-PARTY ID {$counter_party_id}",
												"date_crated" => date("Y-m-d-H:i:s")
												);
							$counterPartyModel->keep_track_of_activity_log_in_counter_party($log_params);
							AppController::redirect_to($site_config['base_url']."counter-party/notes/?mode=edit-notes&cp_id=".$counter_party_id.((isset($_GET['page'])) ? "&page=".$_GET['page'] : "").((isset($_GET['notes_page'])) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1"));							
						}
						// Show full details of the above counter-party selected note
						if ((isset($_GET['opt'])) && (($_GET['opt'] == "view") || ($_GET['opt'] == "edit"))){						
							// Check the note id exist in the db
							if ($counterPartyModel->check_note_id_exist(trim($_GET['note_id']))){						
								if ($counterPartyModel->check_note_id_owned_by_the_correct_counter_party(trim($_GET['cp_id']), trim($_GET['note_id']))){
									$note_param = array("note_id", "note", "added_date", "username", "modified_by", "date_modified");
									$note_full_details = $counterPartyModel->retrieve_full_details_of_selected_note(trim($_GET['note_id']), $note_param, "COUNTER-PARTY");				
								}else{
									AppController::redirect_to($site_config['base_url'] ."counter-party/view/");	
								}	
							}else{
								AppController::redirect_to($site_config['base_url'] ."counter-party/view/");																						
							}	
						}
						// Check whether the case id is exist in the database		
						if (
							($counterPartyModel->check_counter_party_id_exist_in_system(trim($_GET['cp_id']))) &&
							($counterPartyModel->check_counter_party_id_can_be_visble_to_requested_user_by_country($_SESSION['logged_user']['countries'], trim($_GET['cp_id']))) &&
							($counterPartyModel->check_counter_party_country_with_curr_selected_country($_SESSION['curr_country']['country_code'], trim($_GET['cp_id'])))																							
						   )	
							{
							$full_details = $counterPartyModel->retrieve_full_details_per_each_counter_party(trim($_GET['cp_id']));	
							$counter_party_id = $full_details[0]['counter_party_id'];
							$case_ids = $counterPartyModel->retrieve_owned_case_ids_by_this_counter_party($counter_party_id);
							// Start to validate and other main functions				
							if ("POST" == $_SERVER['REQUEST_METHOD']){
								// If notes form has been submitted
								if (isset($_POST['counter_notes_submit'])){
									// If no value has been submitted regarding the notes section
									if (empty($_POST['counter_note_required']['note_text'])){
										$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Required fields cannot be blank !</div>";		
									// If values has been submitted	
									}else{
										$_SESSION['counter_note_required']['note_text'] = $_POST['counter_note_required']['note_text'];							
										$notes_inputting_array = array(
																		"counter_party_id" => $counter_party_id,
																		"note_owner_type" => "COUNTER-PARTY",
																		"note" => $_SESSION['counter_note_required']['note_text'],
																		"added_by" => $_SESSION['logged_user']['id'],
																		"added_date" => date("Y-m-d-H:i:s")
																	  );
										$last_inserted_note_id = $counterPartyModel->insert_new_note($notes_inputting_array);
										// Inserting the notes related categories
										$notes_categories_params = array(
																			"note_id" => $last_inserted_note_id,
																			"notes_categories" => $_POST['counter_party_note_categories_required'],
																			"note_owner_section" => "COUNTER-PARTY"
																		);	
										$counterPartyModel->insert_notes_categories($notes_categories_params);
										$_SESSION['new_note_has_created'] = "true";		
										// Log keeping
										$log_params = array(
															"user_id" => $_SESSION['logged_user']['id'], 
															"action_desc" => "Created a new note to COUNTER-PARTY ID {$counter_party_id}",
															"date_crated" => date("Y-m-d-H:i:s")
															);
										$counterPartyModel->keep_track_of_activity_log_in_counter_party($log_params);
										$notes_array = array('note_id', 'note');					
										$all_notes_to_this_counter_party = $counterPartyModel->retrieve_all_notes_owned_by_this_counter_party($counter_party_id, $notes_array, "COUNTER-PARTY");																																																	
										unset($_POST);									
										unset($_SESSION['counter_note_required']['note_text']);
										AppController::redirect_to($site_config['base_url'] ."counter-party/notes/?mode=edit-notes&cp_id=".$counter_party_id.((isset($_GET['page'])) ? "&page=".$_GET['page'] : "").((isset($_GET['notes_page'])) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1"));									
									}
									$notes_array = array('note_id', 'note');					
									$all_notes_to_this_counter_party = $counterPartyModel->retrieve_all_notes_owned_by_this_counter_party($counter_party_id, $notes_array);																																																	
								}	
								// If notes form has been submitted in edit note section
								if (isset($_POST['counter_notes_update_submit'])){
									// Check the note id exist in the db
									if ($counterPartyModel->check_note_id_exist(trim($_GET['note_id']))){
										// Check the note is owned by him self
										if ($counterPartyModel->check_note_id_owned_by_the_correct_counter_party(trim($_GET['cp_id']), trim($_GET['note_id']))){								
											// If no value has been submitted regarding the notes section						
											if (empty($_POST['counter_party_note_categories_required'])){
												$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Please select at least one category.</div>";		
											// If no value has been submitted regarding the notes section
											}elseif (empty($_POST['counter_note_required']['note_text'])){
												$headerDivMsg = "<div class=\"headerMessageDivInError defaultFont\">Required fields cannot be blank !</div>";		
											// If both values has been submitted	
											}else{
												$_SESSION['counter_note_required']['note_text'] = $_POST['counter_note_required']['note_text'];							
												$notes_inputting_array = array(
																	"counter_party_id" => $counter_party_id,
																	"note_owner_type" => "COUNTER-PARTY",													 
																	"note" => $_SESSION['counter_note_required']['note_text'],
																	"date_modified" => date("Y-m-d-H:i:s"),
																	"modified_by" => $_SESSION['logged_user']['id']
																	);
												$counterPartyModel->update_the_exsiting_note($notes_inputting_array, trim($_GET['note_id']));
												// Remove previous notes categories relation
												$counterPartyModel->remove_previous_notes_categories_relation(trim($_GET['note_id']));										
												// Inserting the notes related categories
												$notes_categories_params = array(
																					"note_id" => trim($_GET['note_id']),
																					"notes_categories" => $_POST['counter_party_note_categories_required'],
																					"note_owner_section" => "COUNTER-PARTY"
																				);	
												$counterPartyModel->insert_notes_categories($notes_categories_params);
												// Log keeping
												$log_params = array(
																	"user_id" => $_SESSION['logged_user']['id'], 
		
																	"action_desc" => "Update a note of COUNTER-PARTY ID {$counter_party_id}",
																	"date_crated" => date("Y-m-d-H:i:s")
																	);
												$counterPartyModel->keep_track_of_activity_log_in_counter_party($log_params);
												$notes_array = array('note_id', 'note', 'username', 'added_date', 'modified_date');						
												$note_full_details = $counterPartyModel->retrieve_full_details_of_selected_note($_GET['note_id'], $note_param, "COUNTER-PARTY");				
												unset($_POST);
												unset($_SESSION['counter_note_required']['note_text']);
												$_SESSION['notes_section_got_updated'] = "true";
												AppController::redirect_to($site_config['base_url']."counter-party/notes/?mode=edit-notes&cp_id=".$counter_party_id.((isset($_GET['page'])) ? "&page=".$_GET['page'] : "").((isset($_GET['notes_page'])) ? "&notes_page=".$_GET['notes_page'] : "&notes_page=1"));												
											}
										// If note id is not owned by him self																				
										}else{
											unset($_SESSION['counter_note_required']['note_text']);
											AppController::redirect_to($site_config['base_url'] ."counter-party/view/");	
										}
									// If wrong note id										
									}else{
										unset($_SESSION['counter_note_required']['note_text']);
										AppController::redirect_to($site_config['base_url'] ."counter-party/view/");	
									}	
								}
							}else{
								unset($_SESSION['counter_main_not_reqired']);						
								unset($_SESSION['counter_main_reqired']);
								unset($_SESSION['counter_reqired_errors']);
								unset($counterPartyModel);	
								unset($full_details);
							}
						// If wrong id	
						}else{
							AppController::redirect_to($site_config['base_url'] ."counter-party/view/");
						}
					break;										
				}
			break;
		}	
	}
}
?>