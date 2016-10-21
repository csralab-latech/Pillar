<?php
/*
*
*  Use class to create objects for each entity returned by api/states.
*  Pass it the decoded JSON object(really just its contents in a PHP array), and it will 
*     make it all accessible through the object's vars.
*
*/
class Entity {
	
	var $json_contents;
    
	//////////////////////////
	// entity state variables
	var $attributes;
	var $entity_id_groups;
	var $entity_id;
	var $last_changed;
	var $last_updated;
	var $state;
	//////////////////////////
	
	/////////////////////////
	//	variables for building endpoint (i.e. for localhost:8123/api/states/light.living_room_lamp  )
	var $component_group;	// ex.  light
	var $room;				// ex.  living room
	var $type;				// ex.  lamp
	var $endpoint;			// ex.  light.living_room_lamp
	/////////////////////////
	
	/////////////////////////
	// Attributes of particular use to Pillar
	var $friendly_name;
	/////////////////////////
	
	// Entity constructor to populate variables given a decode json
	function __construct($decoded_json){
		
		$this->json_contents = $decoded_json;     			//Must go first because update function uses $this->json_contents.
		$this->attributes 	 = $decoded_json['attributes'];					
		$this->entity_id 	 = $decoded_json['entity_id'];
		$this->last_changed  = $decoded_json['last_changed'];
		$this->last_update 	 = $decoded_json['last_update'];
		$this->state 		 = $decoded_json['state'];
		$this->endpoint 	 = $this->entity_id;
		array_key_exists('friendly_name', $this->attributes) ?  $this->friendly_name = $this->attributes['friendly_name'] : $this->friendly_name='';
		array_key_exists('entity_id', $this->attributes) ?  $this->entity_id_groups[0] = $this->attributes['entity_id'] : $this->entity_id_groups=[];
			
	}

	
	///////////////////////////////////////////////////////////////////////////
	// 						Begin Update Functions
	///////////////////////////////////////////////////////////////////////////
	
	function update_entity($new_decoded_json){									//	Update entire entity given new decoded_json
		
		$this->json_contents = $new_decoded_json;	
		$this->attributes 	 = $new_decoded_json['attributes'];
		$this->entity_id 	 = $new_decoded_json['entity_id'];
		$this->last_changed  = $new_decoded_json['last_changed'];
		$this->last_update 	 = $new_decoded_json['last_update'];
		$this->state 		 = $new_decoded_json['state'];
		$this->endpoint 	 = $this->entity_id;
		array_key_exists('friendly_name', $this->attributes) ?  $this->friendly_name = $this->attributes['friendly_name'] : $this->friendly_name='';
		return true;
	
	}
	function change_attribute_value($attribute,$new_value){						//	Change local attribute value given attribute name and new value
		$success = $this->change_value($attribute,$new_value);
		return $success;
	}	
	function change_state($new_value){											//	Change local state given new value
		$success = $this->change_value('state',$new_value);
		if($success === true){
			$this->state = $new_value;
		}
		return $success;
	}	
	function change_entity_id($new_value){										//	Change local entity_id given new value
		$success = $this->change_value('entity_id',$new_value);
		if($success === true){
			$this->entity_id = $new_value;
		}
		return $success;
	}
	function change_last_changed($new_value){									//	Change local last_changed given new value
		$success = $this->change_value('last_changed',$new_value);
		return $success;
	}
	function change_last_updated($new_value){									//	Change local last_changed given new value
		$success = $this->change_value('last_updated',$new_value);
		return $success;
	}
	function change_friendly_name($new_value){									//	Change local friendly_name given new value only it existed before 
		$success = $this->change_value('friendly_name',$new_value);
		return $success;
	}
	
	///////////////////////////////////////////////////////////////////////////
	// 						End Update Functions 
	///////////////////////////////////////////////////////////////////////////
	
	
	
	///////////////////////////////////////////////////////////////////////////
	// 						Begin Add/Remove Functions
	///////////////////////////////////////////////////////////////////////////
	
	//function add_attribute(){ return false;}
	//function remove_attribute(){ return false;}
	
	///////////////////////////////////////////////////////////////////////////
	// 						End Add/Remove Functions 
	///////////////////////////////////////////////////////////////////////////
	


	
	///////////////////////////////////////////////////////////////////////////
	// 						Begin Private Functions
	///////////////////////////////////////////////////////////////////////////
	
	private function change_value($parameter,$new_value){					//  Change value of a parameter given a new value, only if it existed before
		
		$success = false;
		if (array_key_exists($parameter, $this->json_contents)){				// first check to see if parameter is part of first level of array
			$this->json_contents[$parameter] = $new_value;						// change value in decoded json
			$this->$parameter = $new_value;
			$success = true;
		}
		elseif(array_key_exists($parameter, $this->json_contents['attributes'])){	// second check to see if parameter is part of json's attribute array
			$this->json_contents['attributes'][$parameter] = $new_value;				// change value in decoded json
			$this->attributes[$parameter] = $new_value;
			$success = true;
		}
		else{																	// failure means it wasn't part of first level of array or attribute.
			print "change_value failed:  " . $this->entity_id . ":  " . $parameter . PHP_EOL;
		}
		return $success;
	}
	
	///////////////////////////////////////////////////////////////////////////
	// 						End Private Functions
	///////////////////////////////////////////////////////////////////////////
	
	function update_local_json(){											// Reload decoded_json from local variable memory
		$decoded_json['attributes'] 	= $this->attributes;
		$decoded_json['entity_id']		= $this->entity_id;
		$decoded_json['last_changed']	= $this->last_changed;
		$decoded_json['last_update']	= $this->last_update;
		$decoded_json['state']			= $this->state;
	
	}
}






?>