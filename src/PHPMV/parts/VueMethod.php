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
    
    /**
     * @return string
     */
    public function getName():string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }
    
    /**
     * @return string
     */
    public function getBody():string
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body)
    {
        $this->body = $body;
    }
    
    /**
     * @return array
     */
    public function getParams():array
    {
        return $this->params;
    }
    
    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }
    
    /**
     * 
     * @param string $body
     * @param array $params
     */
    public function __construct(string $body,array $params) {
        $this->setBody($body);
        $this->setParams($params);
    }
    
	public function __toString():string{
	    return "!!#function(".implode(",",$this->getParams())."){".$this->getBody()."}!!#";
	}
}

