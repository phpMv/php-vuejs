<?php
namespace PHPMV\parts;

class VueComputeds extends VuePart {
    
    public function add(string $name,string $get,string $set=null):void{
        $setComputed=null;
        if(!is_null($set)){
            $setComputed=new VueComputed($get, $set);
        }
        parent::put($name, $setComputed);
    }
    
    public function __toString():string{
    	$data=parent::__toString();
    	if($data!=""){
    		$variables=['!data'=>$data];
    		$script=file_get_contents("template/computeds",true);
    		$script=str_replace(array_keys($variables),$variables,$script);
    		return $script;
    }
    
}

