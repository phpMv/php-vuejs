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
class VueHook {
	
	protected $body;
	

	public function getBody():string
	{
		return $this->body;
	}
	
	public function setBody(string $body)
	{
		$this->body = $body;
	}
	

	
	public function __construct(string $body) {
		$this->setBody($body);

	}
	
	public function __toString():string{
		return "!!#function(){".$this->getBody()."} !!#";
	}
}