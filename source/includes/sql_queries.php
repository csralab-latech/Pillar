<?php

include 'connect_db.php';

if(isset($_POST['action']))
{
	if($_POST['action']=="getDeviceHistory")
	{
		$entity_id = $_POST['entity_id'];
		$domain = $_POST['domain'];
		print getDeviceHistory($conn, $entity_id, $domain);
	}
}

function getDeviceHistory($conn, $entity_id, $domain)
{
	$sql_get_device_history = "SELECT * FROM $domain WHERE `devices_id`= (SELECT id from devices where entity_id='$entity_id')";
	$result = mysqli_query($conn, $sql_get_device_history);
	$rows = mysqli_num_rows($result);
	//return $rows;
	//$columns = mysqli_num_fields($result);
	$data = Array(Array());
	$i = 0;
	while ($row = mysqli_fetch_assoc($result)){
		$data[$i] = $row;
		$i++;
	}
	return json_encode($data);
}

function createTableIfNotExists($conn, $domain){
 	// Check if the database has this entity table, if not present create table.
 	$sql_check_table = "SHOW TABLES LIKE '" . $domain . "'";
 	//echo @mysqli_ping($conn) ? 'true' : 'false';
 	$count = mysqli_num_rows(mysqli_query($conn, $sql_check_table));
 	if($count==0)
 	{
 		// No table exists for that  domain in the database, thus creating one
 		
 		if($domain == "devices")
 		{
 			$sql_create_table = "CREATE TABLE IF NOT EXISTS `devices` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `entity_id` varchar(200) NOT NULL,
			  `friendly_name` varchar(200) NOT NULL,
			  `room` varchar(100) NOT NULL,
			  `domain` varchar(100) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";	
 		}
 		
 		else if($domain == "light")
		{
 			$sql_create_table = "CREATE TABLE IF NOT EXISTS `light` (
 			`id` int(11) NOT NULL AUTO_INCREMENT,
 			`devices_id` int(11) NOT NULL,
 			`state` varchar(10) NOT NULL,
 			`brightness` int(11) NOT NULL,
 			`color` varchar(50) NOT NULL,
 			`time_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 			PRIMARY KEY (`id`),
 			KEY `devices_id` (`devices_id`)
 			) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1; ";
 			
 			/*$sql_create_table .= "ALTER TABLE `light`
 			ADD CONSTRAINT `light_ibfk_1` FOREIGN KEY (`devices_id`) REFERENCES `devices` (`id`); ";
 			*///echo $sql_create_table;
 		}
 		// code added
 		else if($domain == "lock")
 		{
 			$sql_create_table = "CREATE TABLE IF NOT EXISTS `lock` (
 			`id` int(11) NOT NULL AUTO_INCREMENT,
 			`devices_id` int(11) NOT NULL,
 			`state` varchar(10) NOT NULL,
 			`time_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 			PRIMARY KEY (`id`),
 			KEY `devices_id` (`devices_id`)
 			) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1; ";
 				
 			/*$sql_create_table .= "ALTER TABLE `light`
 			 ADD CONSTRAINT `light_ibfk_1` FOREIGN KEY (`devices_id`) REFERENCES `devices` (`id`); ";
 			*///echo $sql_create_table;
 		}//till here
 		
 		mysqli_query($conn, $sql_create_table) ? "" : die(mysqli_error($conn));
 	}
 }
 
 function insertRow($conn, $table, $data){
 	if($table == "devices")
 	{
 		// Checking if that entity information is already present in the row
 		$sql_select_entity = "SELECT * FROM `devices` WHERE `entity_id` = '$data[0]'";
 		if(mysqli_num_rows(mysqli_query($conn, $sql_select_entity))==0)
 		{
 			$sql_insert_row = "INSERT INTO `devices`(`entity_id`, `friendly_name`, `room`, `domain`) VALUES ('$data[0]','$data[1]','$data[2]','$data[3]')";
 			mysqli_query($conn, $sql_insert_row) ? "" : die(mysqli_error($conn));
 		}
 	}
 }
 
 