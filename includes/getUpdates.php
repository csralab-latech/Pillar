<?php
//header('Content-Type: text/event-stream');
//header('Cache-Control: no-cache');
//var_dump($_GET);
if(isset($_GET))
{
	// writing the contents to a file
	$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
	//$txt = "Mickey Mouse\n";
	fwrite($myfile, var_dump($_GET));
	$txt = "Minnie Mouse\n";
	fwrite($myfile, $txt);
	fclose($myfile);
}
//echo "data: Kiran Yedidi \n\n";
//var_dump($_GET);
//echo "data: \n\n";
//flush();
?>