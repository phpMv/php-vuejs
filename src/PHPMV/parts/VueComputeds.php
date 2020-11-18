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
        return parent::__toString();
    }
    
}

