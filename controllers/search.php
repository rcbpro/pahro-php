<?php

include CONTROLLER_PATH."classes/".$route['controller'].'.php';	
include MODEL_PATH.'search.php';	
include MODEL_PATH.'pagination.php';	
// Grab the regarded controller
$searchController = new SearchController();
$splittedUrl = explode("/", $_SERVER['REQUEST_URI']);
$_SESSION['Controller_to_search'] = ($splittedUrl[1] != "") ? $splittedUrl[1] : "case";					
// URL Gate keeper for the pfac all query strings
AppController::url_gate_keeper($_GET, $_SESSION['Controller_to_search']);		
// check the correct sub vie wloaded in add/edit 
$searchController->correct_sub_view_gate_keeper($_GET, $_SESSION['Controller_to_search']);
// Load the correct view for the correct controller
$searchController->process_the_correct_model($route['view']);

?>