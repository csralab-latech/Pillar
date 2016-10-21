<?php

function getControl($control,$state,$id){
	
	if($control=="light")
	{
		echo '<div class="onoffswitch">';
		if($state=="on")
			echo '<input type="checkbox" onclick="toggleLight(\'' . $id . '\');" name="onoffswitch" class="onoffswitch-checkbox" id="' . $id . '" checked>';
		else
			echo '<input type="checkbox" onclick="toggleLight(\'' . $id . '\');" name="onoffswitch" class="onoffswitch-checkbox" id="' . $id . '" >';
		echo '<label class="onoffswitch-label" for="' . $id . '">';
		echo '<span class="onoffswitch-inner"></span>';
		echo '<span class="onoffswitch-switch"></span>';
		echo '</label></div>';
	}	
}