<?php
namespace PHPMV\parts;
require "vueMethod.php";

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
        if($params == null){
        parent::put($name,$body);
        }else{
        
            $test = new VueMethod($body, $params);
            
        parent::put($name,$test->__toString());
        }
    }
	public function __toString():string{
	    $datas=parent::__toString();
	    return "methods: {".$datas."},";
	}


}

