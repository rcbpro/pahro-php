<?php

include CONTROLLER_PATH."classes/".$route['controller'].'.php';
include MODEL_PATH.$route['controller'].'.php';	
include MODEL_PATH.'pagination.php';	
// Instantiating the new pahro object
$pahroNewUsersController = new PahroUseController();
// URL Gate keeper for the pahro all query strings
AppController::url_gate_keeper($_GET, $route['controller']);		
// Load the correct sub view and restrict unecessary modes
if (isset($_GET['mode'])) $view_mode = $pahroNewUsersController->correct_sub_view_gate_keeper(trim($_GET['mode']));
// Load the correct view mode	
$pahroNewUsersController->process_the_correct_model($route['view'], $route['controller']);

?>