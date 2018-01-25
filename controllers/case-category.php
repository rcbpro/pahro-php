<?php

include CONTROLLER_PATH."classes/".$route['controller'].'.php';	
include MODEL_PATH.$route['controller'].'.php';	
include MODEL_PATH.'pagination.php';
include ('lib/ImageCreate.php');
// Instantiating the new pfac object
$caseCatCon = new CaseCategoryController();
// URL Gate keeper for the pfac all query strings
AppController::url_gate_keeper($_GET, $route['controller']);		
// Load the correct view mode
$caseCatCon->process_the_correct_model($route['view'], $route['controller']);				

?>