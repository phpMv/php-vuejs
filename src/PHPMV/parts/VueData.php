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
class VueData extends VuePart {

	public function __toString():string{
	    $data=parent::__toString();
	    if(!is_null($data)){
	       return "data: function() {return ".$data."}";
	    }
	    return "";
	}
}