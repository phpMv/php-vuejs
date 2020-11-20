<?php
namespace PHPMV\parts;

/**
 * PHPMV$VueJS
 * This class is part of php-vuejs
 *
 * @author qgorak
 * @version 1.0.0
 *
 */
class VueHooks extends VuePart {
	
	public function add(string $name, string $body){
		$vh = new VueHook($body);
		parent::put($name,$vh->__toString());
	}
	
	public function __toString():string{
	    $data=parent::__toString();
	    if($data!=""){
	        return $data;
	    }
	    return "";
	}
}