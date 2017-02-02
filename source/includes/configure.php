<?php
//TODO create class that handles inital configuration upon start up.  This includes reading a configuration file that has hass_url, logfile, etc. Right now I just store global variables here.


//////////////////////////
//   Home Assistant Information
$HASS_HOSTNAME 	= "localhost";
$HASS_PORT		= "8123";
$HASS_URL		= "http://"  . $HASS_HOSTNAME . ":" . $HASS_PORT . "/";
//////////////////////////



//////////////////////////
//	Logging Information
$LOGFILE = "Pillar.log";
//////////////////////////

?>
