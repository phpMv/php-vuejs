<?php
namespace PHPMV;

use PHPMV\parts\VueData;
use PHPMV\parts\VueMethods;
use PHPMV\parts\VueHooks;
use PHPMV\parts\VueComputeds;
use PHPMV\parts\VueHook;

/**
 * Created by PhpStorm.
 * User: qgorak
 * Date: 19/11/2020
 * Time: 14:20
 */
class AbstractVueJS {
	protected $data;
	protected $methods;
	protected $computed;
	protected $watcher;
	protected $directives;
	protected $filters;
	protected $hooks;
	protected $script;
	
	public function __construct() {
	    $this->data= new VueData();
	    $this->methods= new VueMethods();
	    $this->computeds=new VueComputeds();
	    $this->watcher=null;
	    $this->directives=null;
	    $this->filters=null;
	    $this->hooks=new VueHooks();
	    $this->script=array("el"=>null,"vuetify"=>"new Vuetify()");
	}
	
	public function addHook(string $name,string $body) {
		$this->hooks->add($name,$body);
	}
	
	/**
	 * Adds code (body) for the beforeCreate hook
	 * @param body the code to execute
	 */
	public function onBeforeCreate(string $body) {
	    $vh=new VueHook($body);
		$this->addHook("beforeCreate", $body);
		$this->script['beforeCreate']=$vh->__toString();
	}
	
	/**
	 * Adds code (body) for the created hook
	 * @param body the code to execute
	 */
	public function onCreated(string $body) {
	    $vh=new VueHook($body);
		$this->addHook("created", $body);
		$this->script['created']=$vh->__toString();
	}
	
	/**
	 * Adds code (body) for the beforeMount hook
	 * @param body the code to execute
	 */
	public function onBeforeMount(string $body) {
	    $vh=new VueHook($body);
		$this->addHook("beforeMount", $body);
		$this->script['beforeMount']=$vh->__toString();
	}
	
	/**
	 * Adds code (body) for the mounted hook
	 * @param body the code to execute
	 */
	public function onMounted(string $body) {
	    $vh=new VueHook($body);
		$this->addHook("mounted", body);
		$this->script['mounted']=$vh->__toString();
	}
	
	/**
	 * Adds code (body) for the beforeUpdate hook
	 * @param body the code to execute
	 */
	public function onBeforeUpdate(string $body) {
	    $vh=new VueHook($body);
		$this->addHook("beforeUpdate", $body);
		$this->script['beforeUpdate']=$vh->__toString();
	}
	
	/**
	 * Adds code (body) for the updated hook
	 * @param body the code to execute
	 */
	public function onUpdated(string $body) {
	    $vh=new VueHook($body);
		$this->addHook("updated", $body);
		$this->script['updated']=$vh->__toString();
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
	    $vh=new VueHook($body);
		$this->addHook("beforeDestroy", $body);
		$this->script['beforeDestroy']= $vh->__toString();
	}
	
	/**
	 * Adds code (body) for the destroyed hook
	 * @param body the code to execute
	 */
	public function onDestroyed(string $body) {
	    $vh=new VueHook($body);
		$this->addHook("destroyed", body);
		$this->script['destroyed']=$vh->__toString();
	}

	public function addData(string $name,$value):void {
	    $this->data->put($name, $value);
	    $this->script['data']=$this->data->__toString();
	}

	public function addDataRaw(string $name,string $value):void {
	    $this->data->put($name,$value."");
	    $this->script['data']=$this->data->__toString();
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