<?php
/**
 * Export to PHP Array plugin for PHPMyAdmin
 * @version 0.2b
 */

//
// Database `homeautomation`
//

// `homeautomation`.`devices`
$devices = array(
  array('id' => '350','entity_id' => 'lock.front_door_lock','friendly_name' => 'Front Door Lock','room' => 'Hall','domain' => 'lock'),
  array('id' => '351','entity_id' => 'light.kitchen_ceiling_lights','friendly_name' => 'Kitchen Ceiling Lights','room' => 'Kitchen','domain' => 'light'),
  array('id' => '352','entity_id' => 'light.kitchen_hanging_lights','friendly_name' => 'Kitchen Hanging Lights','room' => 'Kitchen','domain' => 'light'),
  array('id' => '353','entity_id' => 'switch.kitchen_sink_plug','friendly_name' => 'Kitchen Sink Plug','room' => 'Kitchen','domain' => 'switch')
);

// `homeautomation`.`light`
$light = array(
  array('id' => '1','devices_id' => '1','state' => 'on','brightness' => '255','color' => '','time_stamp' => '2016-11-03 18:47:18'),
  array('id' => '2','devices_id' => '1','state' => 'off','brightness' => '0','color' => '','time_stamp' => '2016-11-03 21:48:18'),
  array('id' => '3','devices_id' => '1','state' => 'on','brightness' => '255','color' => '','time_stamp' => '2016-11-05 19:20:04'),
  array('id' => '4','devices_id' => '1','state' => 'on','brightness' => '150','color' => '','time_stamp' => '2016-11-06 01:20:04'),
  array('id' => '5','devices_id' => '1','state' => 'off','brightness' => '0','color' => '','time_stamp' => '2016-11-06 03:21:35'),
  array('id' => '6','devices_id' => '1','state' => 'on','brightness' => '255','color' => '','time_stamp' => '2016-11-06 15:21:35')
);

// `homeautomation`.`lock`
$lock = array(
);

// `homeautomation`.`switch`
$switch = array(
);
