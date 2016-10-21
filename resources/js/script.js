// Server sent events

$(document).ready(function(){
	/*$.ajax({
		url: "includes/getUpdates.php",
		type: "GET",
		success: function(data){
			alert(data);
		}
	});*/
	
	var source = new EventSource("includes/getUpdates.php");
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