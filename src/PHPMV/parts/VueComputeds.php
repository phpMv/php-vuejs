<?php
namespace PHPMV\parts;

class VueComputeds extends VuePart {
    
    public function add(string $name,string $get,string $set=null):void{
        $vm = new VueComputed($get,$set);
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

