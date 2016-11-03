<?php

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
 		else if($domain == "switch")
 		{
 			$sql_create_table = "CREATE TABLE IF NOT EXISTS `switch` (
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
 		}
 		mysqli_query($conn, $sql_create_table) ? "" : die(mysqli_error($conn));
 	}
 }
 
 function insertRow($conn, $table, $data){
 	if($table == "devices")
 	{
 		$sql_insert_row = "INSERT INTO `devices`(`entity_id`, `friendly_name`, `room`, `domain`) VALUES ('$data[0]','$data[1]','$data[2]','$data[3]')";
 		mysqli_query($conn, $sql_insert_row) ? "" : die(mysqli_error($conn));
 	}
 }
 
 