<?php
namespace PHPMV;

/**
 * Created by PhpStorm.
 * User: qgorak
 * Date: 19/11/2020
 * Time: 14:20
 */
class AbstractVueJS {
    static private $removeQuote = ["start"=>"!!%","end"=>"%!!"];
    static protected $global;
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
	    $this->hooks[$name] = AbstractVueJS::generateFunction($body);
	}
	
	public function onBeforeCreate(string $body):void {
		$this->addHook("beforeCreate", $body);
	}
	
	public function onCreated(string $body):void {
		$this->addHook("created", $body);
	}
	
	public function onBeforeMount(string $body):void {
		$this->addHook("beforeMount", $body);
	}
	
	public function onMounted(string $body):void {
		$this->addHook("mounted", $body);
	}
	
	public function onBeforeUpdate(string $body):void {
		$this->addHook("beforeUpdate", $body);
	}
	
	public function onUpdated(string $body):void {
		$this->addHook("updated", $body);
	}
	
	public function onUpdatedNextTick(string $body):void {
		$this->addHook("updated", "this.\$nextTick(".AbstractVueJS::generateFunction($body).")");
	}
	
	public function onBeforeDestroy(string $body):void {
		$this->addHook("beforeDestroy", $body);
	}
	
	public function onDestroyed(string $body):void {
		$this->addHook("destroyed", $body);
	}

	public function addData(string $name,$value):void {
        $this->data["data"][$name]=$value;
	}

	public function addDataRaw(string $name,string $value):void {
        $this->data["data"][$name]=AbstractVueJS::$removeQuote["start"].$value.AbstractVueJS::$removeQuote["end"];
	}
	
	public function addMethod(string $name,string $body, array $params = []):void {
        $this->methods["methods"][$name]=AbstractVueJS::generateFunction($body,$params);
	}
	
	public function addComputed(string $name,string $get,string $set=null):void {
	    $vc=(is_null($set)) ? AbstractVueJS::generateFunction($get) : AbstractVueJS::$removeQuote["start"]."{ get: ".AbstractVueJS::generateFunction($get).", set: ".AbstractVueJS::generateFunction($set,["v"])." }".AbstractVueJS::$removeQuote["end"];
	    $this->computeds["computeds"][AbstractVueJS::$removeQuote["start"].$name.AbstractVueJS::$removeQuote["end"]]=$vc;
	}
	
	public function addWatcher(string $var,string $body,array $params=[]):void {
	    $this->watchers["watch"][$var]=AbstractVueJS::generateFunction($body,$params);
	}

	public function addFilter(string $name,string $body, array $params = []):void {
        $this->methods["filters"][AbstractVueJS::$removeQuote["start"].$name.AbstractVueJS::$removeQuote["end"]]=AbstractVueJS::generateFunction($body,$params);
    }

    static function addGlobalFilter(string $name,string $body, array $params = []):void {
        AbstractVueJS::$global[]=AbstractVueJS::$removeQuote["start"]."Vue.filter('".$name."',".AbstractVueJS::generateFunction($body,$params).");".AbstractVueJS::$removeQuote["end"];
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

	static function generateFunction(string $body, array $params = []):string {
        return AbstractVueJS::$removeQuote["start"]."function(".implode(",",$params)."){".$body."}".AbstractVueJS::$removeQuote["end"];
    }
}