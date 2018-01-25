<?php

require './../../define.inc';
require './../../lib/database.php'; 
require './../../lib/model.php'; 

class Preview_model{

	/* This function will retrieve some case details for the given note id */
	function retrieve_some_details_for_the_given_note_id($note_id){
	
		global $connection;
		$sql = "SELECT case__main.reference_number, case__main.case_name, case__notes.note  
				FROM case__main 
				LEFT JOIN case__notes ON case__notes.case_id = case__main.reference_number
				WHERE case__notes.note_id = ".$note_id;
		$params = array('reference_number', 'case_name', 'note');		
		$settings = $this->grab_db_settings();
		$connection = DBFunctions::db_connect($settings->db_settings);
		return (AppModel::grab_db_function_class()->result_to_array_for_few_fields(AppModel::grab_db_function_class()->execute_query($sql), $params));														
	} 
	/* End of the fucntion */
	
	/* This function will retirve db_settings */
	function grab_db_settings(){
	
		return new PahroSettings();
	}
	/* End of the function */	
}
?>