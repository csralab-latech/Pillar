<?php

include '../includes/states.php';
include '../includes/services.php';
include '../includes/restful_commands.php';
// This is a personal note: Look up autoloader and see performance related issues with loading class files
require_once '../includes/class_entity.php';

// initalize curl, which must be based to functions to run curl commands
$ch = curl_init();

$file="../configuration.yaml";
$parsed = yaml_parse_file($file);
$new_file ="edited_configuration.yaml";
    print_r($parsed);
//    var_dump(yaml_emit($parsed));
    
$status =  yaml_emit_file($new_file,$parsed);

?>
