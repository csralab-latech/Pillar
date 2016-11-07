<?php 

error_reporting(0);
include 'includes/restful_commands.php';
include 'includes/class_entity.php';
include 'includes/controls.php';
include 'includes/sql_queries.php';
include 'includes/connect_db.php';

// Home assistant variables
$home_assistant_url = "localhost:8123/api/";
$ch = curl_init($home_assistant_url);
?>

<html>
	<head>
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script type="text/javascript" src="resources/js/script.js"></script>
		<link href="resources/css/default.css" rel="stylesheet" type="text/css" media="all" />
	</head>
 	<body>
 		<h1 style="text-align: center;font-weight: bold;font-size: 60px;" class="text-primary">PILLAR</h1>
 		<ul class="nav nav-tabs">
		  <li class="active"><a data-toggle="tab" href="#display_devices">Control Devices</a></li>
		  <li><a data-toggle="tab" href="#reports" onclick="getGraph('power_consumption');">Reports</a></li>
		</ul>
		
		<div class="tab-content">
			<div class="container tab-pane fade in active" id="display_devices">
	 			<?php 
	 			
	 			// Getting group information to divide devices based on the location
	 			$all_states = getData($ch, $home_assistant_url, "states");
	 			$groups = Array(); // Array to hold the groups information
	 			$entity_ids = Array(Array()); // Two dimentional array to hold devices of each location
	 			
	 			$index = 0;
	 			$entities = Array(); // Array to store the entities created
	 			foreach ($all_states  as $new_entity){
	 				$entity = new Entity($new_entity);
	 				$entities[] = $entity; // storing entity objects fo future reference
	 				$attributes = $entity->attributes;
	 				
	 				// identity the attributes which are having view key
	 				if(array_key_exists("view", $attributes))
	 				{
	 					$groups[] = $entity->friendly_name;
	 					$entity_ids[$index++] = $entity->entity_id_groups;
					}
	 				
	 			}
	 			
	 			
	 			
	 			// display panels for each location to hold entities
	 			$index = 0;
	 			foreach ($groups as $location)
	 			{?>
	 				<div id="room" class="col-sm-4 col-md-4 col-lg-4">
		 				<div class="panel panel-info">
		 					<div class="panel-heading"><?php echo $location;?></div>
		 					<div class="panel-body">
		 						<?php 
		 							foreach($entity_ids[$index][0] as $devices)
		 							{
										?>
		 								<div>
		 									<!-- Label -->
		 									<label><?php //echo preg_replace(Array('/(.*)\.(.*)/','/_/'), Array("$2"," "), $devices);
		 										// Getting the friendly name of the device
		 										foreach ($entities as $entity)
		 										{
		 											if($entity->entity_id==$devices)
		 											{
		 												// Checking and creating if devices table exists
		 												createTableIfNotExists($conn, "devices");
		 												
		 												echo $entity->friendly_name;
		 												$state = $entity->state;
		 												$control = preg_replace('/(.*)\.(.*)/', '$1', $devices);
		 												// Data to save in the table
		 												$data = Array($devices, $entity->friendly_name, $location, $control);
		 												insertRow($conn, "devices", $data);
		 												// Checking if the talbe for the entity id exists, if not creating the table
		 												createTableIfNotExists($conn, $control);
		 												// Checking if the device is light, if yes getting the brighness information
		 												
		 												if($control=="light")
		 												{
		 													if(array_key_exists("brightness", $entity->attributes))
		 														$brightness = $entity->attributes['brightness'];
		 													else
		 														$brightness = 0;
		 												}
		 												break;
		 											} 
		 									} ?>
		 									</label>
		 									
		 									<!-- Control -->
		 									<div id="control" style="float: right;">
		 										<?php getControl($control, $state, $devices);?>
		 									</div>
		 									<div style="clear: both;"></div>
		 									<?php if($control=="light") // adding brightness control
		 										  {?>
		 										  	<div id="<?php echo $devices;?>_brightness_div" style="float: right;">
		 										  		<input type="range" min="0" max="255" id="<?php echo $devices;?>_brightness_control" onchange="changeBrightness('<?php echo $devices;?>',this.value);" value=<?php echo $brightness; if($state=='off') {echo ' style="display: none;"';}?>>
		 										  	</div>
		 										  	<div style="clear: both;"></div>
		 									<?php }	?>
		 				
		 								</div>
		 				  	<?php	}$index++;
		 						?>
		 					</div>
		 				</div>
		 			</div>
	 			<?php }
	 			
	 			?>
	 		</div>
 		
	 		<!-- Visual graphs for the device history -->
	 		<!-- Temperory button to call function to draw charts -->
	 		<div class="tab-pane fade" id="reports" >
	 			<div>
		 			<div class="col-md-2 col-lg-2 col-sm-2">
		 				<ul class="nav nav-pills nav-stacked">
					        <li class="active"><a data-toggle="pill" href="#power_consumption">Power Consumption</a></li>
					        <li><a data-toggle="pill" href="#calories">Calories</a></li>
					        <li><a data-toggle="pill" href="#temperature">Temperature</a></li>
					        <li><a data-toggle="pill" href="#health">Health Data</a></li>
				      </ul>
		 			</div>
		 			<div class="col-md-10 col-lg-10 col-sm-10 tab-content">
    					<div id="power_consumption" class="tab-pane fade in active">
      						<div id='light_chart_div'></div>
    					</div>
    					<div id="calories" class="tab-pane fade">
      						<h3>HOME2</h3>
      						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
    					</div>
    				</div>
	 			</div>
	 		</div>
 		</div> 			 		
 	</body>

<?php 

?>

</html>