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
	       $variables=['!data'=>$data];
	       $script=file_get_contents("template/data",true);
	       $script=str_replace(array_keys($variables),$variables,$script);
	       return $script;
	    }
	    return "";
	}
}