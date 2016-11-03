// Server sent events

$(document).ready(function(){
	/*$.ajax({
		url: "includes/getUpdates.php",
		type: "GET",
		success: function(data){
			alert(data);
		}
	});*/
	
<<<<<<< Updated upstream
	var source = new EventSource("includes/getUpdates.php");
=======
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
>>>>>>> Stashed changes
    source.onmessage = function(event) {
        alert(event.data);
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