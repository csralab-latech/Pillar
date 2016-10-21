<?php
// Getting light state
function get_light_state($curl,$client_id) // What if we instead passed the light 
{
	$component_name = "light";
    $device_state = get_device_state($ch,$component_name,$client_id);
	return $device_state['state'];
}

//function create_function_endpoint($rest_action,$component_group,$client_id) //These could be contained with in a single array instead, then loop over array
//{
	//$url  = "http://localhost:8123/api/"; 
	//$url .=  $rest_action ."/"; 		
	//$url .=  $component_group . ".";
	//$url .=  $client_id;
	//return $url;
//}

function get_all_states($curl)
{
	curl_setopt($curl, CURLOPT_URL, "http://localhost:8123/api/states");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    $result = json_decode($result,true);
	return $result;
}
    
//  This might be part of 
function get_device_state($curl,$component_group,$client_id)
{
	
	$inital_url = "http://localhost:8123/api/states";
	$full_endpoint = $initial_url . $component_group . "." . $client_id;
	curl_setopt($curl, CURLOPT_URL, $full_endpoint);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    $result = json_decode($result,true);
	return $result;
}

function turn_on_light($curl)
{
    $data = Array("entity_id" => "light.living_room_lamp");
    $json_data = json_encode($data);
    curl_setopt($curl, CURLOPT_URL, "http://localhost:8123/api/services/light/turn_on");
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($curl, CURLOPT_HTTPHEADER, Array('Content-Type: application/json','Content-Length: ' . strlen($json_data)));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
	var_dump($result);
}
?>
