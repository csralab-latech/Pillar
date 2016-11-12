// Server sent events
var entities = new Array();

$(document).ready(function(){
	
	// Preventing default behavior of a tag
	
	$('#reports_navigation a').click(function(event){
		event.preventDefault();
	});
	// Load the Visualization API and the corechart package.
	google.charts.load('current', {'packages':['corechart']});
	// Creating entitites array
	// Iterating through all panels (each panel can have entities)
	$('#display_devices #room').each(function(){
		//alert($('.panel-body>div',this).size());
		$('.panel-body>div',this).each(function(){
			if($('#control input',this)!=="undefined")
			{
				entities.push($('#control input',this).attr('id'));
			}
		});
		
	});
	
	//alert(entities.length);
	var i=0;
	var entity = "";
	var source = new EventSource("http://iothouse.ddns.net:8123/api/stream");
    source.onmessage = function(event) {
    	
    	if ("ping" === event.data) {
            return;
        }
    	
    	var obj = JSON.parse(event.data);
        var new_state = "";
        var domain = "";
        var brightness = 0; // for light devices
    	console.log('checking' + (i++) + event.data);
    	if(typeof obj.data.new_state!=="undefined")
    	{
    		if(typeof obj.data.new_state.attributes.entity_id!=="undefined")
    		{
    			//alert(obj.data.new_state.attributes.entity_id.length);
    			if(!jQuery.isArray(obj.data.new_state.attributes.entity_id))
    			{
    				//$('#stream_data').text(obj.data.new_state.attributes.entity_id);
    				entity = obj.data.new_state.attributes.entity_id;
    				domain = entity.replace(/(\w+)\.(\w+)/,'$1');
    	    		if(domain=="light") // if the object is light get the brightness information
    	    		{
    	    			if(obj.data.new_state.attributes.brightness!=="undefined")
    	    			{
    	    				brightness = obj.data.new_state.attributes.brightness;
    	    			}
    	    		}
    			}
    		}
	    	else if(typeof obj.data.new_state.entity_id!=="undefined")
	    	{
	    		//alert(obj.data.new_state.entity_id.length);
	    		if(!jQuery.isArray(obj.data.new_state.entity_id))
	    		{	//$('#stream_data').text(obj.data.new_state.entity_id);
	    			entity = obj.data.new_state.entity_id;
	    			domain = entity.replace(/(\w+)\.(\w+)/,'$1');
	        		if(domain=="light") // if the object is light get the brightness information
	        		{
	        			if(obj.data.new_state.attributes.brightness!=="undefined")
	        			{
	        				brightness = obj.data.new_state.attributes.brightness;
	        			}
	        		}
	    		}
	    	}
    		new_state = obj.data.new_state.state;
    	}
    	//alert(entity);
    	//alert(new_state);
    	if(jQuery.inArray(entity,entities)>-1){
    		//alert("inside change");
    		// For Lights
    		if(new_state == "on" || new_state == "locked")
    		{
    			document.getElementById(entity).checked = true;
    			if(domain == "light")
    			{
    				document.getElementById(entity+"_brightness_control").style.display = "inline-block";
    				document.getElementById(entity+"_brightness_control").value = brightness;
    			}
    		}
    		else if(new_state=="off" || new_state=="unlocked")
    		{
    			document.getElementById(entity).checked = false;
    			if(domain == "light")
    			{
    				document.getElementById(entity+"_brightness_control").style.display = "none";
    			}
    		}
    	}
    };
    
});

// function to toggle the light state
function toggleLight(id)
{
	// calling ajax to call php function to toggle the light
	$.ajax({
		url: "includes/restful_commands.php",
		data:{action: "toggleLight", id: id},
		type: "POST",
		success: function(data){
			//alert(data);
		}
	});
}

//function to toggle lock
function toggleLock(id)
{
	var type = "";
	if(document.getElementById(id).checked)
		type = "on";
	else
		type = "off";
	// calling ajax to call php function to toggle the light
	$.ajax({
		url: "includes/restful_commands.php",
		data:{action: "toggleLock", id: id, type: type},
		type: "POST",
		success: function(data){
		}
	});
}

// Changing the brightness of the lights
function changeBrightness(id, brightness){
	// calling ajax to cal php function to change the brightness
	$.ajax({
		url: "includes/restful_commands.php",
		data:{action: "changeBrightness", id: id, brightness: brightness},
		type: "POST",
		success: function(data){
			//alert(data);
		}
	});
}

function getGraph(chart_type){
		$.ajax({
			url: "includes/reports.php",
			data: {action: chart_type},
			type: "POST",
			success: function(row_data){
				row_data = row_data.replace(/\[/g,'');
			    var temp1 = row_data.split('],');
				google.charts.setOnLoadCallback(function(){
					var data = new google.visualization.DataTable();
					data.addColumn('date', 'Day');
					data.addColumn('number', 'Watts consumed');
					for (var i = 0; i < temp1.length-1 ; i++){
			            data.addRow((new Function("return [" + temp1[i]+ "];")()));  // Converting string to array
					}
					
				    // Set chart options
				    var options = {'title':'Watts consumed per day',
				                    'width':400,
				                    'height':300,
				                    'legend': { position: 'top' }};
	
				    // Instantiate and draw our chart, passing in some options.
				    var chart = new google.visualization.ColumnChart(document.getElementById('light_chart_div'));
				    chart.draw(data, options);
				    
				    // Chart 2
				    data = google.visualization.arrayToDataTable([['Location', 'Power Consumption'],['At Home',11],['Away From Home',2],]);
				    // Set chart options
				    options = {'title':'Power Consumption based user home status',
				                    'width':400,
				                    'height':300};
	
				    // Instantiate and draw our chart, passing in some options.
				    chart = new google.visualization.PieChart(document.getElementById('light_chart_div1'));
				    chart.draw(data, options);
				    
				    // Chart 3
				    data = google.visualization.arrayToDataTable([
				                                		          [' Device Location', 'Power Consumption'],
				                                		          ['Living Room',     1000],
				                                		          ['Kitchen',      200],
				                                		          
				                                		        ]);
				    // Set chart options
				    options = {'title':'Power Consumption in each room',
				                    'width':400,
				                    'height':300};
	
				    // Instantiate and draw our chart, passing in some options.
				    chart = new google.visualization.PieChart(document.getElementById('light_chart_div2'));
				    chart.draw(data, options);
				    
				    // Chart 4
				      data = new google.visualization.DataTable();
				      data.addColumn('date', 'Pizza');
				      data.addColumn('number', 'Calories Consumed');
				      data.addColumn('number', 'Calories burnt');
				      data.addRows([
				        [new Date(2016,10,5) , 100 , 200],
				        [new Date(2016,10,4), 150 , 300],
				        [new Date(2016,10,3), 50 , 100],
				        [new Date(2016,10,2), 200 , 300], 
				        [new Date(2016,10,1), 25 , 100],
				      ]);

				      var options = {
				        title: 'Calories Consumed/Burnt',
				        'width':400,
	                                'height':300,
	                    //chartArea: {height: "10%"},
	                    //legend: {position: 'top'}
	                    };

				      var chart = new google.visualization.BarChart(document.getElementById('calories_chart_div'));
				      // Checking if the div is hidden, if yes making it avaialble to the chart
				      chart.draw(data, options);
				      
				      // Chart 5
				      var data = new google.visualization.DataTable();
				      data.addColumn('date', 'Thermostat');
				      data.addColumn('number', ' Avg Temperature');
				      data.addColumn('number', 'Avg Moisture %');
				      data.addRows([
				        [new Date(2016,10,5) , 75 , 45],
				        [new Date(2016,10,4), 78 , 42],
				        [new Date(2016,10,3), 76 , 43],
				        [new Date(2016,10,2), 78 , 41], // Below limit.
				        [new Date(2016,10,1), 79 , 40] // Below limit.
				      ]);

				      var options = {
				        title: 'Thermostat',
				        'width':400,
	                    'height':300
				      };

				      var chart = new google.visualization.LineChart(document.getElementById('temperature_chart_div'));
				      chart.draw(data, options)
				});
			}
		});
				$('a[href="#power_consumption"]').addClass('active');
				$('a[href="#power_consumption"]').siblings('a').removeClass('active');
				$('#power_consumption').show();
				$('#power_consumption').siblings('div').hide();
				
}

function toggleVisibility(element, report_type){
		$(element).addClass('active');
		$(element).siblings('a').removeClass('active');
		var id = $(element).attr('href');
		$(''+id).show();
		$(''+id).siblings('div').hide();
		if(report_type=="calories")
		{
			$('#calories_chart_div>div>div:first-child').css('width',"100%");
			$('#calories_chart_div svg:first-child').css('width',"100%");
			$('#calories_chart_div svg:first-child').children('rect').css("width","100%");
		}
		else if(report_type=="temperature")
		{
			$('#temperature_chart_div>div>div:first-child').css('width',"100%");
			$('#temperature_chart_div svg:first-child').css('width',"100%");
			$('#temperature_chart_div svg:first-child').children('rect').css("width","100%");
		}
		else if(report_type=="home")
		{
			$('#home_link_div>div>div:first-child').css('width',"100%");
			$('#home_link_div svg:first-child').css('width',"100%");
			$('#home_link_div svg:first-child').children('rect').css("width","100%");
		}
		else if(report_type=="kiosk")
		{
			$('#kiosk_link_div>div>div:first-child').css('width',"100%");
			$('#kiosk_link_div svg:first-child').css('width',"100%");
			$('#kiosk_link_div svg:first-child').children('rect').css("width","100%");
		}
		else if(report_type=="fridge")
		{
			$('#fridge_link_div>div>div:first-child').css('width',"100%");
			$('#fridge_link_div svg:first-child').css('width',"100%");
			$('#fridge_link_div svg:first-child').children('rect').css("width","100%");
		}
}
