<?php
//include 'configure.php'
//TODO create logger class file. Right now I have a function Log($file="log",$entry).

function Log($entry){
	$logfile = $GLOBALS['LOGFILE'];
	file_put_contents($logfile, $entry, FILE_APPEND | LOCK_EX);	
}

?>