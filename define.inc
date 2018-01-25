<?php

// Server Root
define('SERVER_ROOT', $_SERVER['DOCUMENT_ROOT']);
// Application Root
// This following code need only to check in the localhost
//define('APP_ROOT', 'pahro');
// Web URL 
define('WEB_URL', 'http://'.$_SERVER['HTTP_HOST']);
// Directory Structure
define('DS', '/');
// MVC Paths
define('MODEL_PATH', SERVER_ROOT.DS.'models'.DS);
define('CONTROLLER_PATH', SERVER_ROOT.DS.'controllers'.DS);
define('VIEW_PATH', SERVER_ROOT.DS.'views'.DS);

// This following code need only to check in the localhost
//define('MODEL_PATH', SERVER_ROOT.APP_ROOT.DS.'models'.DS);
//define('CONTROLLER_PATH', SERVER_ROOT.APP_ROOT.DS.'controllers'.DS);
//define('VIEW_PATH', SERVER_ROOT.APP_ROOT.DS.'views'.DS);
    
// Number of records per page 
define('NO_OF_RECORDS_PER_PAGE', 15);
define('NO_OF_RECORDS_PER_PAGE_DEFAULT', 15);
define('NO_OF_RECORDS_PER_PAGE_FOR_LOG', 100);
define('NO_OF_RECORDS_PER_PAGE_FOR_CASES', 30);

// Tagos settings
define('HOST_TAGOS', 'localhost');
define('USER_TAGOS', 'tagos');
define('PASS_TAGOS', '175r1LaNkA');

class PahroSettings{        

	var $db_settings = array(
								'host' => 'localhost', 
								'username' => 'tagos',
								'password' => '175r1LaNkA',
								'database' => 'pahro'
							);
} 

/*class TagosSettings{        

	var $db_settings = array(
								'host' => 'localhost', 
								'username' => 'tagos',
								'password' => '175r1LaNkA',
								'database' => 'volunteers'
							);
}
*/	
?>