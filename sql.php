<?php

include 'define.inc';

$connection = mysql_connect("localhost", "tagos", "175r1LaNkA");
if (!$connection) return false;
if (!mysql_select_db("pahro", $connection)) return false; 

// Retreive all the volunteers
$sql = "SELECT id FROM pahro__user WHERE user_type_id = 2";
$result = mysql_query($sql , $connection);
$i=0;
while($row = mysql_fetch_array($result)){
	$vols[] = $row['id'];
	$i++;
}

// Delete these volunteers from the permission rel table
foreach($vols as $each_vol){
	mysql_query("DELETE FROM pahro__user_permission_rel WHERE user_id = ".$each_vol, $connection);	
}

$vol_permissions = array(1 , 3, 62, 63, 9, 11, 67, 68, 13, 14, 15, 71, 73, 22, 23, 25, 26, 27, 29, 30, 31, 33, 34, 35, 57, 37, 38, 40, 41, 42, 44, 45, 46, 48, 49, 50, 52, 53, 54, 56, 58, 59, 61, 76, 77, 78, 79, 80, 81, 82, 83);

// Insert new volunteer permissions to the permissions rel
foreach($vols as $each_vol){
	foreach($vol_permissions as $each_per){	
		mysql_query("INSERT INTO pahro__user_permission_rel (user_id, permission_id) VALUES (".$each_vol.", ".$each_per.")", $connection);
	}
}

echo "DONE";

?>