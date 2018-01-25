<?php

	session_start();		
	//error_reporting(E_ALL ^ E_NOTICE);
	//ini_set('display_errors', '1');			
	require 'config.php';
	// Global variables for index page
	global $connection;
	global $connection2;
	global $site_config;
	global $submitStatus;
	global $whichFileToInclude;				
	global $left_menu_system;
	global $logged_user_countries;
	// Instantiating the Configuration object	
	$config = new Configuration();
	$submitStatus = ("POST" == $_SERVER['REQUEST_METHOD']) ? true : false;
	// Grab the database connection & basic site details
	$connection = $config->return_db_connection();
	$site_config = $config->return_site_base_details();
	// Load users permissions and put them in to an array of session data
	if (isset($_SESSION['logged_user']['permissions'])) unset($_SESSION['logged_user']['permissions']);
	$_SESSION['logged_user']['permissions'] = (AppController::check_authentication()) ? $config->retrieve_all_permissions_for_the_logged_user($_SESSION['logged_user']['id']) : NULL;
	// Grab the logged in user's countries
	if (isset($_SESSION['logged_user']['countries'])) unset($_SESSION['logged_user']['countries']);	
	$_SESSION['logged_user']['countries'] = (AppController::check_authentication()) ? $config->grab_users_countires_for_the_loggedin_user($_SESSION['logged_user']['id']) : NULL;
	// Admin Gate keeper function for the permissions restriction
	$config->admin_gate_keeper($_SERVER['REQUEST_URI'], $_SESSION['logged_user']['permissions']);			
	// Load the left menu system for logged users permissions
	$left_menu_system = (AppController::check_authentication()) ? $config->load_left_menu_for_given_permissions($_SESSION['logged_user']['permissions']) : "";
	// Which file need to be included
	$whichFileToInclude = $config->check_routes_map($_SERVER['REQUEST_URI']);
	// Retreive the logged user countries to display in the logged left corner
	$logged_user_countries = $config->grab_users_counties_names_for_loggedin_users($_SESSION['logged_user']['id']);
	// Layout include
	include str_replace("views", "public", VIEW_PATH).'layouts'.DS.(AppController::check_authentication() ? 'application_layout.html' : "login.html");

?>