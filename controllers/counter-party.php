<?php

include CONTROLLER_PATH."classes/".$route['controller'].'.php';	
include MODEL_PATH.$route['controller'].'.php';	
include MODEL_PATH.'pagination.php';
include ('lib/ImageCreate.php');
// Instantiating the new pfac object
$counter_partyCon = new CounterPartyController();
// URL Gate keeper for the pfac all query strings
AppController::url_gate_keeper($_GET, $route['controller']);		
// Load the correct sub view and restrict unecessary modes
if (isset($_GET['mode'])) $view_mode = $counter_partyCon->correct_sub_view_gate_keeper(trim($_GET['mode']));
// Load the correct view mode
$counter_partyCon->process_the_correct_model($route['view'], $route['controller'], $view_mode);				

?>