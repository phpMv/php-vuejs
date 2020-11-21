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
class VueComputed extends VuePart {
    
    protected $get;
    protected $set;
    
    public function __construct(string $get,string $set=null) {
        $this->setGet($get);
        $this->setSet($set);
    }
    
    public function __toString():string {
        if(is_null($this->set)){
            return "!!%function(){".$this->get."}%!!";
        }
        return "!!%function(){".$this->get."}, set: function(v){".$this->set."}%!!";
    }
    
    public function getGet():string {
        return $this->get;
    }

    public function setGet(string $get):void {
        $this->get = $get;
    }
    
    public function getSet():string {
        return $this->set;
    }

    public function setSet(string $set):void {
        $this->set = $set;
    }
}