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
class VueMethods extends VuePart {
    
    public function add(string $name, string $body, string $params=null){
        $vm = new VueMethod($body,explode(",",$params));   
        parent::put($name,$vm->__toString());
    }
    
	public function __toString():string{
		$data=parent::__toString();
		if($data!=""){
		    return $data;
		}
		return "";
	}
}

