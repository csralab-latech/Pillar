<?php

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