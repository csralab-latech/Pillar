<?php

function getControl($control,$state,$id){
	if($control=="light" || $control=="switch" || $control=="lock")
	{
		if($control=="lock")
			$onclick = "toggleLock";
		else
			$onclick = "toggleLight";
			
		echo '<div class="onoffswitch">';
		if($state=="on" || $state=="locked")
			echo '<input type="checkbox" onclick="' . $onclick . '(\'' . $id . '\');" name="onoffswitch" class="onoffswitch-checkbox" id="' . $id . '" checked>';
		else
			echo '<input type="checkbox" onclick="' . $onclick . '(\'' . $id . '\');" name="onoffswitch" class="onoffswitch-checkbox" id="' . $id . '" >';
		echo '<label class="onoffswitch-label" for="' . $id . '">';
		echo '<span class="onoffswitch-inner"></span>';
		echo '<span class="onoffswitch-switch"></span>';
		echo '</label></div>';
	}	
}