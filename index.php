<?php 
session_start();

if(!(isset($_SESSION['sessionUsername']) && !empty($_SESSION['sessionUsername'])))
{
	header("Location: http://iothouse.ddns.net");
}

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
<!DOCTYPE html>
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
<<<<<<< Updated upstream
 		<h1 style="text-align: center;font-weight: bold;font-size: 60px;" class="text-primary">PILLAR</h1>
 		<ul class="nav nav-tabs">
		  <li class="active"><a data-toggle="tab" href="#display_devices">Control Devices</a></li>
		  <li><a data-toggle="tab" href="#reports_navigation" onclick="getGraph('power_consumption');">Reports</a></li>
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
	 		<div id="reports_navigation" class="tab-pane fade">
	 			<div>
		 			<div class="col-md-2 col-lg-2 col-sm-2">
		 				<div class='list-group'>
					        <a class="list-group-item active" onclick="toggleVisibility(this,'power_consumption');" href="#power_consumption">Power Consumption</a>
					        <a class="list-group-item" onclick="toggleVisibility(this,'calories');" href="#calories">Calories</a>
					        <a class="list-group-item" onclick="toggleVisibility(this,'temperature');" href="#temperature">Temperature</a>
					        <a class="list-group-item" onclick="toggleVisibility(this,'health');" href="#health">Health Data</a>
					    </div>
				     </div>
		 			<div id="reports" class="col-md-10 col-lg-10 col-sm-10">
    					<div id="power_consumption">
      						<div class="col-md-6 col-lg-6 col-sm-6" id='light_chart_div'></div>
      						<div class="col-md-6 col-lg-6 col-sm-6" id='light_chart_div1'></div>
      						<div class="col-md-6 col-lg-6 col-sm-6" id='light_chart_div2'></div>
    					</div>
    					<div id="calories">
      						<div class="col-md-6 col-lg-6 col-sm-6" id='calories_chart_div'></div>
    					</div>
    					<div id="temperature">
      						<div class="col-md-6 col-lg-6 col-sm-6" id='temperature_chart_div'></div>
    					</div>
    					<div id="health">
      						<div style="background-color: white" class="col-md-6 col-lg-6 col-sm-6" id='health_chart_div'>
      							Last treatment name: Heart Transplantation <br>
      							Last treatment date: 11/7/2016 <br>
      							Doctor name: Box <br>
      							Blood pressure: 60-90mm Hg 90-140mm Hg <br>
      							Cholesterol: 2.1~5.2 mmol/L 
      						</div>
    					</div>
    				</div>
=======
 		<h1 style="text-align: center;font-weight: bold;font-size: 60px;" class="text-primary page-header">PILLAR</h1>
 		<div id="stream_data"></div>
 		<div class="container" id="display_devices">
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
	 										}
	 										
	 										?>
	 									</label>
	 									
	 									<!-- Control -->
	 									<div id="control" style="float: right;">
	 										<?php getControl($control, $state, $devices);?>
	 									</div>
	 									<div style="clear: both;"></div>
	 									<?php if($control=="light") // adding brightness control
	 										  {?>
	 										  	<div id="<?php echo $devices;?>_brightness_div" style="float: right;">
	 										  		<input type="range" min="0" max="255" id="<?php echo $devices;?>_brightness_control" onchange="changeBrightness('<?php echo $devices?>',this.value);" value=<?php echo $brightness; if($state=='off') echo ' style="display: none;"'?>>
	 										  	</div>
	 										  	<div style="clear: both;"></div>
	 									<?php }		
	 									?>
	 				
	 								</div>
	 				  	<?php	}$index++;
	 						?>
	 					</div>
	 				</div>
>>>>>>> Stashed changes
	 			</div>
	 		</div>
 		</div> 			 		
 	</body>

<?php 

?>

</html>