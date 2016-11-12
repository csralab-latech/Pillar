<html>
  <head>
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    

    <?php 
    include 'includes/connect_db.php';
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
    echo "Number of Hours Light is turned ON: " . number_format($num_hours, 2) . "<br>";
    echo "Number Watts consumed by the light: " . number_format($total_watts_consumed, 2) . "<br>";
    echo $rows_data_chart;
    ?>
    
    <script type="text/javascript">
      
      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

    // Create our data table out of JSON data loaded from server.
    	var data = new google.visualization.DataTable();
    	data.addColumn('date', 'Day');
      	data.addColumn('number', 'Watts consumed');

      	data.addRows([<?php echo $rows_data_chart?>]);
        // Set chart options
        var options = {'title':'Watts consumed per day',
                       'width':400,
                       'height':300};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.ColumnChart(document.getElementById('light_chart_div'));
        chart.draw(data, options);
        // Chart data for activity tracker
        data = new google.visualization.DataTable();
        data.addColumn('date', 'Day');
      	data.addColumn('number', 'Calories Burned');

        data.addRows([
                      [new Date(2016,10,6) , 100],
                      [new Date(2016,10,4), 150],
                      [new Date(2016,10,3), 50],
                      [new Date(2016,10,2), 200], 
                      [new Date(2016,10,1), 25] 
                    ]);

                    var options = {
                      title: 'Calories Consumed per day',
                      'width':400,
                      //areaOpacity: 0.0,
                      backgroundColor: { fill:'transparent' }
                      'height':300};
		var chart = new google.visualization.ColumnChart(document.getElementById('activity_tracker_chart_div'));
        chart.draw(data, options);
      }
    </script>
  </head>

  <body>
    <!--Div that will hold the pie chart-->
    <div id="light_chart_div"></div>
    <div id="activity_tracker_chart_div"></div>
  </body>
</html>
