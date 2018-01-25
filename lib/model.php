<?php

class AppModel{

	/**		
	 * Loops over an array of fields.
	 * @param array $params
	 * @return bool or array 
	 */
	 static function validate($params){

		$errors = "";
	 	$errors = array('total_errors' => 0);		
		foreach($params as $filed => $value){
			if ( (!isset($filed)) || (trim(empty($value))) || (trim($value) == "") ){
				$errors['total_errors']++;
				$errors[$filed] = true;
			}	
		}
		return ($errors['total_errors'] > 0) ? $errors : false;
	 }
	 
	 /* This function will insert tempory data in to session table */
	 function keep_session_data(){}
	 /* End of the function */
	 
	 	/* This function will load the database activity class */
	 static function grab_db_function_class(){ return new DBFunctions(); }
	/* End of the fucntion */
	
	/* This function will check for errors in max length validation */	
	static function check_max_field_length($field_length_array, $field_name){

		$field_errors = array();
		foreach ($field_length_array as $value => $max_length){
			if (strlen(trim(CommonFunctions::mysql_preperation($value))) < $max_length){
				$field_errors[] = $field_name;
			}
		}
		return $field_errors;		
	}
	/* End of the function */
}	
?>