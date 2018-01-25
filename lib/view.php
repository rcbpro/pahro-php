<?php

class AppViewer{

	/**		
	 * Checks if the user logged in.
	 * @return bool
	 */
	static function logged_in(){ return (isset($_SESSION['logged_user'])) ? true : false; }	

	/**		
	 * Returns user firld
	 * @string $field
	 * returns string	 
	 */
	static function current_user($field){ return $_SESSION['logged_user'][$field]; }

	/**		
	 * Format Date
	 * @param mysql timestamp $mysql_timestamp
	 * @param string	 
	 */
	static function format_date($mysql_timestamp){ return date("d, F Y", strtotime($mysql_timestamp)); }
	
	static function format_date_regular($mysql_timestamp){ return date("d-m-Y", strtotime($mysql_timestamp)); }
	
	static function format_date_full($mysql_timestamp){ return date("d/m/Y h:i A", strtotime($mysql_timestamp)); }

	/**		
	 * Format Date with time
	 * @param mysql timestamp $mysql_timestamp
	 * @param string	 
	 */
	static function format_date_with_time($mysql_timestamp){ return date('d\<\s\u\p\>S\<\/\s\u\p\> l F Y h:i:s A', strtotime($mysql_timestamp)); }	
	
	/* This function will format the viewing data */
	static function format_view_data_with_line_breaks($data_to_be_splitted){
	
		$splitted_data = "";
		for($n=0; $n<strlen($data_to_be_splitted); $n++){
			if (($n == 15) || ($n == 25) || ($n == 35) || ($n == 45) || ($n == 55)){
				$splitted_data .= "<br />";												
			}
			$splitted_data .= $data_to_be_splitted[$n];	
		}
		return $splitted_data;		
	}
	/* End of the function */	
}
?>