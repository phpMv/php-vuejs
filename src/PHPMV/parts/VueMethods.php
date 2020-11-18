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
        $test = new VueMethod($body,explode(",",$params));   
        parent::put($name,$test->__toString());
    }
    
	public function __toString():string{
	    $data=parent::__toString();
	    $variables=['!data'=>$data];
	    $script=file_get_contents("template/methods",true);
	    $script=str_replace(array_keys($variables),$variables,$script);
	    return $script;
	}
}

