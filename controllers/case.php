<?php

include CONTROLLER_PATH."classes/".$route['controller'].'.php';	
include MODEL_PATH.$route['controller'].'.php';	
include MODEL_PATH.'pagination.php';
include ('lib/ImageCreate.php');
// Instantiating the new pfac object
$caseCon = new CaseController();
// URL Gate keeper for the pfac all query strings
AppController::url_gate_keeper($_GET, $route['controller']);		
// check the correct sub view loaded in add/edit 
if (isset($_GET['mode'])) $view_mode = $caseCon->correct_sub_view_gate_keeper(trim($_GET['mode']));
// Load the correct view mode
$caseCon->process_the_correct_model($route['view'], $route['controller'], $view_mode);				

?>