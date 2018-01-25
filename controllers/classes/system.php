<?php

class SystemFuncsController extends AppController{

	/* This function will process the correct model for the given view */
	function process_the_correct_model($view, $controller){	

		switch($view){

			case "home": 
			case "index":
				// All global variables
				global $site_config;
				global $headerDivTinyMsg;				
				global $no_of_all_cases; 
				global $no_of_all_clients;
				global $no_of_all_cp;
				global $no_of_cases_added;
				global $no_of_clients_added;
				global $no_of_cps_added;
				// Instaintiating the system object
				$system = new SystemModel();				
				// Retreieve no of cases in the system			
				$system_resources = $system->retrieve_count_of_each_system_functional_resources($_SESSION['logged_user']['permissions'], $_SESSION['logged_user']['id']);
				// Retrieve all recently added cases 
				$recently_added_cases = $system->load_recently_added_updated_cases($_SESSION['logged_user']['permissions'], $_SESSION['logged_user']['id']);				
			break;

			case "login":
				// All global variables
				global $site_config;
				global $headerDivTinyMsg;				
				// Instaintiating the system object
				$system = new SystemModel();				
				$sys_log = false;
				if ("POST" == $_SERVER['REQUEST_METHOD']){
					$errors = AppModel::validate($_POST['user_login']);
					if (
					   ($errors['total_errors'] != 0) || 
					   ($_POST['user_login']['username'] == "Username") || 
					   ($_POST['user_login']['password'] == "Password")
					   ){
						$_SESSION['headerDivTinyMsg'] = "<div class=\"tinyHeaderMessageDivInError defaultFont\">Username &amp; Password are required.</div>";																	
					// If username provided							
					}else{
						$_SESSION['user_login']['username'] = $_POST['user_login']['username'];
						// Check the given username exist						
						if ($system->check_username_exist(trim($_POST['user_login']['username'])) != 1){
							$headerDivTinyMsg = "<div class=\"tinyHeaderMessageDivInError defaultFont\">Username &amp; Password are not matching.</div>";																							
						// Check the password correct for the given username													
						}else{
							$sys_log = $system->check_password_correct($_POST['user_login']);						 

							if (!$sys_log){
								$headerDivTinyMsg = "<div class=\"tinyHeaderMessageDivInError defaultFont\">Wrong Password.</div>";																							
								// If no errors fouund								
							}else{
								unset($headerDivTinyMsg);						
								// Logged in success	
								$logged_user = $system->grab_full_details_for_the_loggedin_user(trim($_SESSION['user_login']['username']));
								// Put the logged user details in to the session user array
								$_SESSION['logged_user'] = $logged_user[0];
								// Update the llast logged time
								$system->update_last_login($_SESSION['logged_user']['id']);
								// Log keeping
								$log_params = array(
													"user_id" => $_SESSION['logged_user']['id'], 
													"action_desc" => "{$_SESSION['logged_user']['username']} was Logged in to the PAHRO",
													"date_crated" => date("Y-m-d-H:i:s")
													);
								$system->keep_track_of_activity_log_in_system($log_params);
								// Load users permissions and put them in to an array of session data
								// Clears the user login params that used to login process
								$logged_url = (
												($_SERVER['HTTP_REFERER'] == $site_config['base_url']) || 
												($_SERVER['HTTP_REFERER'] == $site_config['base_url']."system/login/") || 
												($_SERVER['HTTP_REFERER'] == $site_config['base_url']."system/logout/")												
											  ) ? $site_config['base_url']."logged/".$_SESSION['logged_user']['username'].DS : $_SERVER['HTTP_REFERER'];
								AppController::redirect_to($logged_url);							
							}	
						}
					}	
				}else{
					// Unset all session variables regarding to this logged user							
					unset($_SESSION['user_login']);				
					unset($headerDivTinyMsg);
				}
			break;
			
			case "activity-log":
				// All global variables
				global $site_config;
				global $allLogs;
				global $allLogs_count;
				global $pagination;
				global $tot_page_count;
				global $cur_page;
				global $invalidPage;
				global $breadcrumb;				
				global $img;
				$breadcrumb = "";
				$sortBy = "";
				// Instantiating the new system object
				$system = new SystemModel();	
				$pagination_obj = new Pagination();											
				// Bredcrmb to the pfa section
				$breadcrumb .= "<div class=\"breadcrumbMessageDiv defaultFont boldText\"><a href=\"".$site_config['base_url']."\" class=\"headerLink\">Home</a> &rsaquo; View All Activities</div>";
				// Sorting of the logs

				if (isset($_GET['sort'])){	
				
					$imgDefault = "<a href=\"".$site_config['base_url']."system/activity-log/?sort=".$_GET['sort']."&by=asc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\"><img src=\"../../public/images/s_asc.png\" border=\"0\" /></a>";			
					$imgAsc = "<a href=\"".$site_config['base_url']."system/activity-log/?sort=".$_GET['sort']."&by=asc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\"><img src=\"../../public/images/s_asc.png\" border=\"0\" /></a>";			
					$imgDesc = "<a href=\"".$site_config['base_url']."system/activity-log/?sort=".$_GET['sort']."&by=desc".(isset($_GET['page']) ? "&page=".$_GET['page'] : "")."\"><img src=\"../../public/images/s_desc.png\" border=\"0\" /></a>";								
					$img = (!isset($_GET['by'])) ? ($imgDefault) : (($_GET['by'] == "asc") ? $imgDesc : $imgAsc);

					$sort_param_array = array("past" => "id", "u_name" => "username", "act_desc" => "action_type_desc", "time" => "date_time");
					foreach($sort_param_array as $key => $value) {
						if ($key == $_GET['sort']) {
							$sortBy = $value;
						}
					}
				}
				$cur_page = ((isset($_GET['page'])) && ($_GET['page'] != "") && ($_GET['page'] != 0)) ? $_GET['page'] : 1; 																
				$params = array('id', 'username', 'action_type_desc', 'date_time');																									
				// Disply all logs
				$allLogs = $system->retrieve_all_logs($params, $cur_page, $sortBy, (isset($_GET['by'])) ? $_GET['by'] : "");
				$allLogs_count = $system->retrieve_all_logs_count();	
				// Create the current path if other qStrings available
				$curPath = $_SERVER['REQUEST_URI'];
				// Pagination load
				$pagination = $pagination_obj->generate_pagination($allLogs_count, $curPath, 100);				
				$tot_page_count = ceil($allLogs_count/100);	
				// If the page not exist
				if ((isset($_GET['page'])) && ($_GET['page'] > $tot_page_count)){
					$invalidPage = true;	
					if ($allLogs_count == 0){
						AppController::wait_then_redirect_to(1, $site_config['base_url']);					
					}else{					
						AppController::wait_then_redirect_to(1, $site_config['base_url']."system/activity-log/");
					}	
				}
				// Unset all used variables
				unset($allLogs);
				unset($allLogs_count);
				unset($pagination);
				unset($tot_page_count);
				unset($cur_page);
				unset($invalidPage);
				unset($breadcrumb);
				unset($img);
				unset($system);
			break;
			
			case "logout":
				// All global variables
				global $site_config;
				// Instantiating the new system object
				$system = new SystemModel();				
				// Log keeping
				$log_params = array(
									"user_id" => $_SESSION['logged_user']['id'], 
									"action_desc" => "{$_SESSION['logged_user']['username']} was Logged out from the PAHRO",
									"date_crated" => date("Y-m-d-H:i:s")
									);
				$system->keep_track_of_activity_log_in_system($log_params);
				// Unset all session variables regarding to this logged user			
				unset($_SESSION['logged_user']);
				session_destroy();
				AppController::redirect_to($site_config['base_url']);
			break;			
		}
	}
}
?>