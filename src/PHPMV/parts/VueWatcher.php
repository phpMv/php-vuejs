<?php
namespace PHPMV\parts;

class VueWatcher
{
    protected $var;
    protected $body;
    protected $params;
    
    public function __construct(string $var,string $body,array $params){
        $this->var=$var;
        $this->body=$body;
        $this->params=$params;
    }
    
    public function __toString(){
        return "!!%function(".implode(',',$this->params)."){".$this->body."}%!!";
    }
}

