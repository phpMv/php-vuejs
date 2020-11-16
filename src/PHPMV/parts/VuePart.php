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
class VuePart {
	protected $elements;

	
	
	public function __construct() {
	    $this->elements=array();
	}


	/**
	 * @param string $name
	 * @param string $value
	 */
	public function put(string $name,string $value):void {
		$this->elements[$name]=$value;
	}

	public function __toString():string{
		return str_replace(array('=','&','+'), array(':"','"\n',' '), http_build_query($this->getElements(), null, '",')).'"';
	}

	/**
	 * @return array
	 */
	public function getElements():array {
	    return $this->elements;
	}

}

