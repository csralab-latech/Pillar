<?php
include 'configure.php';

// receiving evnts from user actions
if(isset($_POST['action']) && !empty($_POST['action']))
{
	$curl = curl_init();
	$hass_url = "localhost:8123/api/";
	
	if(isset($_POST['action']) && $_POST['action']=="toggleLight")
	{
		$entity_id = $_POST['id'];
		$data = Array("entity_id"=>"$entity_id");
		$domain = preg_replace('/(.*)\.(.*)/', '$1', $entity_id);
		$service = "toggle"; 
		$entity_endpoint = "services/$domain/$service";
		echo post_states($curl, $data, $entity_endpoint, $hass_url);
	}
	else if(isset($_POST['action']) && $_POST['action']=="changeBrightness")
	{
		$entity_id = $_POST['id'];
		$brightness = $_POST['brightness'];
		$domain = preg_replace('/(.*)\.(.*)/', '$1', $entity_id);
		if($brightness>0)
		{
			$service = "turn_on";
			$data = Array("entity_id"=>$entity_id, "brightness"=>$brightness);
		}
		else
		{
			$service = "turn_off";
			$data = Array("entity_id"=>$entity_id);
		}
		$entity_endpoint = "services/$domain/$service";
		//var_dump($data);
		echo post_states($curl, $data, $entity_endpoint, $hass_url);
	}
	else if(isset($_POST['action']) && $_POST['action']=="toggleLock")
	{
		$entity_id = $_POST['id'];
		$type = $_POST['type'];
		$domain = preg_replace('/(.*)\.(.*)/', '$1', $entity_id);
		if($type=="on")
		{
			$service = "lock";
		}
		else
		{
			$service = "unlock";
		}
		$data = Array("entity_id"=>$entity_id);
		$entity_endpoint = "services/$domain/$service";
		//var_dump($data);
		echo post_states($curl, $data, $entity_endpoint, $hass_url);
	}
	
	curl_close($curl);
}

//TODO instead of having hass_url as default to localhost, just make function to create endpoint, then pass that endpoint to these functions.  

function getData($curl, $hass_url, $end_point){
	$hass_url .= $end_point;
	curl_setopt($curl, CURLOPT_URL, $hass_url );
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($curl);
	$result = json_decode($result,true);
	return $result;
}

//$hass_url .= "config";
//$hass_url .= "discovery_info";
//$hass_url .= "bootstrap";
//$hass_url .= "events";
//$hass_url .= "services";
//$hass_url .= "history/period";
//$hass_url .= "states";
//$hass_url .= "error_log";

//TODO Figure out how to generalizing use of POST to update entity states. What is needed to update state of existing entity instead of creating new one?
//TODO Should I pass entire entity object, or just the endpoint?  Figure out if constantly passing PHP objects to functions is memory heavy.

function post_states($curl, $data, $entity_endpoint,$hass_url){
	
	$full_endpoint = $hass_url . $entity_endpoint;
	$json_data = json_encode($data);
    curl_setopt($curl, CURLOPT_URL, $full_endpoint);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($curl, CURLOPT_HTTPHEADER, Array('Content-Type: application/json','Content-Length: ' . strlen($json_data)));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
	return $result;
}

// $full_endpoint = $hass_url . "states/". $entity_endpoint;
// $full_endpoint = $hass_url . "events/". $event_type;
// $full_endpoint  = $hass_url ."services/";
// $full_endpoint  = $hass_url ."template";
//function post_event_forwarding(){}
//function delete_event_forwarding(){}
?>