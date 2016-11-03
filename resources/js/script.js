// Server sent events

$(document).ready(function(){
	/*$.ajax({
		url: "includes/getUpdates.php",
		type: "GET",
		success: function(data){
			alert(data);
		}
	});*/
	
	var entities = new Array();
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
	var source = new EventSource("http://192.168.1.12:8123/api/stream");
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
    		if(new_state == "on")
    		{
    			document.getElementById(entity).checked = true;
    			if(domain == "light")
    			{
    				document.getElementById(entity+"_brightness_control").style.display = "inline-block";
    				document.getElementById(entity+"_brightness_control").value = brightness;
    			}
    		}
    		else if(new_state=="off")
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