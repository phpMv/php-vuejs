<?php
namespace PHPMV;

use PHPMV\parts\VueData;
use PHPMV\parts\VueMethods;
use PHPMV\parts\VueComputeds;
use PHPMV\parts\VueHook;

/**
 * Created by PhpStorm.
 * User: qgorak
 * Date: 19/11/2020
 * Time: 14:20
 */
class AbstractVueJS {
	protected $methods;
	protected $computeds;
	protected $watcher;
	protected $directives;
	protected $filters;
	protected $hooks;
	protected $script;
	
	public function __construct() {
	    $this->methods= new VueMethods();
	    $this->computeds=new VueComputeds();
	    $this->watcher=null;
	    $this->directives=null;
	    $this->filters=null;
	    $this->hooks=array();
	    $this->script=array("el"=>null,"vuetify"=>"new Vuetify()","data"=>"{}");
	}
	
	public function addHook(string $name,string $body) {
	    $this->hooks[$name] = "%!! function(){ $body } !!%";;
	}
	
	/**
	 * Adds code (body) for the beforeCreate hook
	 * @param body the code to execute
	 */
	public function onBeforeCreate(string $body) {
		$this->addHook("beforeCreate", $body);
	}
	
	/**
	 * Adds code (body) for the created hook
	 * @param body the code to execute
	 */
	public function onCreated(string $body) {
		$this->addHook("created", $body);
	}
	
	/**
	 * Adds code (body) for the beforeMount hook
	 * @param body the code to execute
	 */
	public function onBeforeMount(string $body) {
		$this->addHook("beforeMount", $body);
	}
	
	/**
	 * Adds code (body) for the mounted hook
	 * @param body the code to execute
	 */
	public function onMounted(string $body) {
		$this->addHook("mounted", body);
	}
	
	/**
	 * Adds code (body) for the beforeUpdate hook
	 * @param body the code to execute
	 */
	public function onBeforeUpdate(string $body) {
		$this->addHook("beforeUpdate", $body);
	}
	
	/**
	 * Adds code (body) for the updated hook
	 * @param body the code to execute
	 */
	public function onUpdated(string $body) {
		$this->addHook("updated", $body);
	}
	
	/**
	 * Adds code (body) for the updated hook
	 * wait until the entire view has been re-rendered with $nextTick
	 * @param body the code to execute
	 */
	public function onUpdatedNextTick(string $body) {
		$this->addHook("updated", "this.\$nextTick(function () {".body."})");
	}
	
	/**
	 * Adds code (body) for the beforeDestroy hook
	 * @param body the code to execute
	 */
	public function onBeforeDestroy(string $body) {
		$this->addHook("beforeDestroy", $body);
	}
	
	/**
	 * Adds code (body) for the destroyed hook
	 * @param body the code to execute
	 */
	public function onDestroyed(string $body) {
		$this->addHook("destroyed", body);
	}

	public function addData(string $name,$value):void {
	    $this->data[$name]= $value;
	    $this->script['data']=$this->data;
	}

	public function addDataRaw(string $name,string $value):void {
	    $this->data[$name]="!!%$value%!!";
	    $this->script['data']=$this->data;
	}
	
	public function addMethod(string $name,string $body, string $params = null) {
	    $this->methods->add($name, $body, $params);
	    $this->script['methods']=$this->methods->__toString();
	}
	
	public function addComputed(string $name,string $get,string $set=null) {
	    $this->computeds->add($name, $get, $set);
	    $this->script['computeds']=$this->computeds->__toString();
	}
	
	public function getComputeds():string {
		return $this->computeds;
	}

	public function setComputeds(string $computeds) {
		$this->computeds = $computeds;
	}

	public function setWatcher(string $watcher) {
		$this->watcher = $watcher;
	}
	
	public function getData():array {
	    return $this->data;
	}
	
	public function getMethod():string {
	    return $this->method;
	}
	
	public function getWatcher():string {
	    return $this->watcher;
	}
	
	public function getHooks() {
		return $this->hooks;
	}
	
	public function setHooks($hooks) {
		$this->hooks = $hooks;
	}
}