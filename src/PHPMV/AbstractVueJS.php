<?php
namespace PHPMV;

use PHPMV\parts\VueData;
use PHPMV\parts\VueMethods;

use PHPMV\js\JavascriptUtils;

/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 13/11/2020
 * Time: 22:25
 */
class AbstractVueJS {
	protected $data;
	protected $methods;
	protected $computed;
	protected $watcher;
	
	
	public function __construct() {
	    $this->data= new VueData();
	    $this->methods= new VueMethods();
	}


	/**
	 * Adds a data
	 * @param key the name of the data
	 * @param value the value
	 */
	public function addData(string $name,string $value):void {
	    $this->data->put($name, $value);
	}

	/**
	 * @param string $method
	 */
	public function addMethod(string $name,string $body, string $params = null) {
	    $this->methods->add($name, $body, $params);
	}
	
	/**
	 * @return string
	 */
	public function getComputed():string {
		return $this->computed;
	}

	/**
	 * @param string $computed
	 */
	public function setComputed(string $computed) {
		$this->computed = $computed;
	}

	/**
	 * @param string $watcher
	 */
	public function setWatcher(string $watcher) {
		$this->watcher = $watcher;
	}
	
	/**
	 * @return array
	 */
	public function getData():array {
	    return $this->data;
	}
	
	/**
	 * @return string
	 */
	public function getMethod():string {
	    return $this->method;
	}
	
	/**
	 * @return string
	 */
	public function getWatcher():string {
	    return $this->watcher;
	}
}