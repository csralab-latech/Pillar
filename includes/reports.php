<?php

if(isset($_POST['action'])){
	if($_POST['action']=="power_consumption")
	{
		print(draw_watts_consumed_reports());
	}
}

function draw_watts_consumed_reports(){
	include 'connect_db.php';
	// Getting device data from the database
	$sql_get_device_history = "select state, brightness, time_stamp from light where devices_id = 1 order by id asc";
	$result = mysqli_query($conn, $sql_get_device_history);
	
	/* Let us assume Bulb wattage = 100WPH (watts per hour) */
	$rows = Array();
	while($row = mysqli_fetch_assoc($result))
	{
		$rows[] = $row;
	}
	
	$num_hours =  0.0;
	$total_watts_consumed = 0.0;
	$light_wattaage_per_hour = 100;
	$rows_data_chart = "";
	 
	$previous_day = "";
	for($i = 0; $i < sizeof($rows); $i++)
	{
	 
		// Ignoring the OFF states from the starting of the table
		if($i==0)
			while($rows[$i]['state']!='on')
			$i++;
	   
			// calculate the the number of hours the light is ON
		if($rows[$i]['state']=='on')
		{
			$date = explode(" ", $rows[$i]['time_stamp'])[0];
		
			if($previous_day!=$date)
			{
	    		if($previous_day != "")
				{
					$dates = explode('-',$previous_day);
					$rows_data_chart .= '[new Date(' . $dates[0] . ', ' . ($dates[1]-1) . ', ' . $dates[2] . '), ' . $watts_consumed_per_day . '],';
				}
					$watts_consumed_per_day = 0.0;
			}
		
			$previous_day = $date;
			if($i!= sizeof($rows)-1)
			{
				$hours = (strtotime($rows[$i+1]['time_stamp']) - strtotime($rows[$i]['time_stamp']))/3600;
				$num_hours += $hours;
			}
			else
	    	{
				$hours = (time() - strtotime($rows[$i]['time_stamp']))/3600;
				$num_hours += $hours;
			}
		
			$watts_consumed = (($rows[$i]['brightness']*$light_wattaage_per_hour)/255) * $hours;
			$watts_consumed_per_day += $watts_consumed;
			$total_watts_consumed += $watts_consumed;
		
			if($i==(sizeof($rows)-1))
			{
				$dates = explode('-',$previous_day);
				$rows_data_chart .= '[new Date(' . $dates[0] . ', ' . ($dates[1]-1) . ', ' . $dates[2] . '), ' . $watts_consumed_per_day . '],';
			}
		}
							 
	}
	return $rows_data_chart;}?>