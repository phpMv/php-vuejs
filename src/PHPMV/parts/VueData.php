<?php
namespace PHPMV\parts;
require "VuePart.php";

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
	    $datas=parent::__toString();
	    return "data: function() {return {".$datas." };},";
	}


}

