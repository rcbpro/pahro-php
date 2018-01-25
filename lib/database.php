<?php

class DBFunctions{

	/* This function will return the database connection */
	function db_connect($db_settings){
		
		$connection = mysql_connect($db_settings['host'], $db_settings['username'], $db_settings['password']);
		if (!$connection){
			return false;
		}
		if (!mysql_select_db($db_settings['database'], $connection)){
			return false;
		}
		return $connection;
	}
	/* End of the function */
	
	/* This function will turn the mysql resource in to an array */
	function result_to_array_for_few_fields($result, $params){
	
		$result_array = array();		
		$i=0;
		while($row = @mysql_fetch_array($result)){
			foreach($params as $key){
				$result_array[$i][$key] = $row[$key];
			}	
			$i++;			
		}		
		return $result_array;
	}
	/* End of the function */
	
	/* This function will turn the mysql resource in to an array */
	function result_to_array_for_few_fields_for_file_manager($result, $params){
	
		$result_array = array();		
		$i=0;
		while($row = @mysql_fetch_array($result)){
			foreach($params as $key){
				$result_array[$row['unique_name']][$key] = $row[$key];
			}	
			$i++;			
		}		
		return $result_array;
	}
	/* End of the function */
	
	/* This function will return a single value from the executed query */
	function return_single_result_from_mysql_resource($result, $row){ return @mysql_result($result, $row); }
	/* End of the function */
	
	/* This fucntion will execute the query */
	function execute_query($sql){	
		
		if ($sql != ""){
			try{
				$results = mysql_query($sql);
			}catch(Exception $e){
				$results = mysql_error();
			}	
			return $results;
		}else{
			return false;
		}
	}
	/* End of the fucntion */

	/* This function will return a single value from the executed query */
	function return_num_of_rows_in_result($result){ return mysql_num_rows($result); }
	/* End of the function */
	
	/* This function will tun the mysql resource in to a single elements array */
	function result_to_single_array_of_data($result, $field){
	
		$result_array = array();		
		while($row = mysql_fetch_array($result)){
			$result_array[] = $row[$field];
		}		
		return $result_array;		
	}
	/* End of the function */
	
	/* This functiono will return the last inserted mysql id */
	function return_last_inserted_id(){ return mysql_insert_id(); }
	/* End of the fucntion */
	
	/* This function will close the current connection */
	function shift_database($connection){ mysql_close($connection); } 
	/* End of the function */ 
}	
?>