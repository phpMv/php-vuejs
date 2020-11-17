<?php
namespace PHPMV\parts;

use PHPMV\js\JavascriptUtils;
/**
 * PHPMV$VueJS
 * This class is part of php-vuejs
 *
 * @author qgorak
 * @version 1.0.0
 *
 */
class VuePart {
    
	protected $elements;

	public function __construct() {
	    $this->elements=array();
	}

	/**
	 * @return array
	 */
	public function getElements():array {
	    return $this->elements;
	}
	
	/**
	 * @param string $name
	 * @param string $value
	 */
	public function put(string $name,string $value):void {
		$this->elements[$name]=$value;
	}

	public function __toString():string{
	    if(empty($this->getElements())){
	        return "";
	    }
	    else{
	        $retour=json_encode(JavascriptUtils::toJson($this->getElements()));
	        $retour=str_replace(['"!!#','!!#"'],"",json_decode($retour));
	        return $retour;
	    }
	}
}

