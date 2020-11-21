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
class VueMethod {
    protected $name;
    protected $body;
    protected $params;
    
    public function __construct(string $body,array $params) {
        $this->setBody($body);
        $this->setParams($params);
    }
    
    public function __toString():string {
        return "!!%function(".implode(",",$this->getParams())."){".$this->getBody()."}%!!";
    }
    
    public function getName():string {
        return $this->name;
    }

    public function setName(string $name):void {
        $this->name = $name;
    }
    
    public function getBody():string {
        return $this->body;
    }

    public function setBody(string $body):void {
        $this->body = $body;
    }
    
    public function getParams():array {
        return $this->params;
    }
    
    public function setParams(array $params):void {
        $this->params = $params;
    }
}

