<?php
    //include 'states.php';
	//include 'services.php';
	include 'restful_commands.php';
	// This is a personal note: Look up autoloader and see performance related issues with loading class files
	require_once 'class_entity.php';
	
	// initalize curl, which must be based to functions to run curl commands
    $ch = curl_init();

	$all_states = get_states($ch);					//	Get array of all full entity states
	$entities = Array();							//	Initialize array of entities
	foreach ($all_states  as $new_entity){			

		$entity = new Entity($new_entity);			//	Initialize new Entity object
		$id = $entity->entity_id;					//  Get it's entity_id to use as a key
		$entities[$id] = $entity;					//  Store entity in array using it's entity_id as a key
		
		var_dump($entities[$id]);					//  Demonstrator that you can find entity in array by its entity_id and see all its contents
	}
	
	print count($entities) . PHP_EOL;				// print number of entities in array.
	
	//$configuration = get_config($ch);
	//var_dump($configuration);

	//Does not print the variable contents, just says, Array. Must use var_dump
	//print $all_device_states;
		//var_dump($all_device_states);
	
	
	//  Print first device's friendly_name
		//print $all_device_states[0]['attributes']['friendly_name'] . PHP_EOL;
	
?>
