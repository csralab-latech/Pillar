<?php

function get_light_state($curl,$client_id) // What if we instead passed the light 
{
	$component_name = "light";
    $device_state = get_device_state($ch,$component_name,$client_id);
	return $device_state['state'];
}
//   api/states: Returns an array of state objects. Each state has the following attributes: entity_id, state, last_changed and attributes.


function get_device_state($curl,$component_group,$client_id)
{
	
	$inital_url = "http://localhost:8123/api/states/";
	$full_endpoint = $initial_url . $component_group . "." . $client_id;
	curl_setopt($curl, CURLOPT_URL, $full_endpoint);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    $result = json_decode($result,true);
	return $result;
}


