<?php

include CONTROLLER_PATH."classes/".$route['controller'].'.php';
include MODEL_PATH.'system.php';	
include MODEL_PATH.'pagination.php';	
// Instantiating the new system object
$systemCon = new SystemFuncsController();
// URL Gate keeper for the system all query strings
AppController::url_gate_keeper($_GET, $route['controller']);
// Load the correct view mode	
$systemCon->process_the_correct_model($route['view'], $route['controller']);

?>