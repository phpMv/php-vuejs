<?php
namespace PHPMV;

/**
 * Created by PhpStorm.
 * User: qgorak
 * Date: 19/11/2020
 * Time: 14:20
 */
class AbstractVueJS {
	protected $data;
	protected $methods;
	protected $computeds;
	protected $watchers;
	protected $directives;
	protected $filters;
	protected $hooks;
	
	public function __construct() {
	    $this->data=[];
	    $this->methods=[];
	    $this->computeds=[];
	    $this->watchers=[];
	    $this->directives=[];
	    $this->filters=[];
	    $this->hooks=[];
	}
	
	public function addHook(string $name,string $body):void {
	    $this->hooks[$name] = "%!!function(){ $body }!!%";
	}
	
	/**
	 * Adds code (body) for the beforeCreate hook
	 * @param body the code to execute
	 */
	public function onBeforeCreate(string $body):void {
		$this->addHook("beforeCreate", $body);
	}
	
	/**
	 * Adds code (body) for the created hook
	 * @param body the code to execute
	 */
	public function onCreated(string $body):void {
		$this->addHook("created", $body);
	}
	
	/**
	 * Adds code (body) for the beforeMount hook
	 * @param body the code to execute
	 */
	public function onBeforeMount(string $body):void {
		$this->addHook("beforeMount", $body);
	}
	
	/**
	 * Adds code (body) for the mounted hook
	 * @param body the code to execute
	 */
	public function onMounted(string $body):void {
		$this->addHook("mounted", $body);
	}
	
	/**
	 * Adds code (body) for the beforeUpdate hook
	 * @param body the code to execute
	 */
	public function onBeforeUpdate(string $body):void {
		$this->addHook("beforeUpdate", $body);
	}
	
	/**
	 * Adds code (body) for the updated hook
	 * @param body the code to execute
	 */
	public function onUpdated(string $body):void {
		$this->addHook("updated", $body);
	}
	
	/**
	 * Adds code (body) for the updated hook
	 * wait until the entire view has been re-rendered with $nextTick
	 * @param body the code to execute
	 */
	public function onUpdatedNextTick(string $body):void {
		$this->addHook("updated", "this.\$nextTick(function () {".body."})");
	}
	
	/**
	 * Adds code (body) for the beforeDestroy hook
	 * @param body the code to execute
	 */
	public function onBeforeDestroy(string $body):void {
		$this->addHook("beforeDestroy", $body);
	}
	
	/**
	 * Adds code (body) for the destroyed hook
	 * @param body the code to execute
	 */
	public function onDestroyed(string $body):void {
		$this->addHook("destroyed", body);
	}

	public function addData(string $name,$value):void {
        $this->data["data"][$name]=$value;
	}

	public function addDataRaw(string $name,string $value):void {
        $this->data["data"][$name]="!!%$value%!!";
	}
	
	public function addMethod(string $name,string $body, array $params = []):void {
        $this->methods["methods"][$name]="!!%function(".implode(",",$params)."){".$body."}%!!";
	}
	
	public function addComputed(string $name,string $get,string $set=null):void {
	    $vc=(is_null($set)) ? "!!%function(){".$get."}%!!" : "!!%function(){".$get."}, set: function(v){".$set."}%!!";
	    $this->computeds["computeds"][$name]=$vc;
	}
	
	public function addWatcher(string $var,string $body,array $params=[]):void {
	    $this->watchers["watch"][$var]="!!%function(".implode(',',$params)."){".$body."}%!!";
	}
	
	public function getData():array {
	    return $this->data;
	}
	
	public function setData(array $data):void {
	    $this->data=$data;
	}
	
	public function getMethods():array {
	    return $this->methods;
	}
	
	public function setMethods(array $methods):void {
	    $this->methods=$methods;
	}
	
	public function getComputeds():array {
		return $this->computeds;
	}

	public function setComputeds(array $computeds):void {
		$this->computeds = $computeds;
	}

	public function getWatchers():array {
	    return $this->watchers;
	}
	
	public function setWatchers(array $watchers):void {
		$this->watchers = $watchers;
	}
	
	public function getHooks():array {
		return $this->hooks;
	}
	
	public function setHooks(array $hooks):void {
		$this->hooks = $hooks;
	}
}