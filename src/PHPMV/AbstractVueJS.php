<?php
namespace PHPMV;

use PHPMV\js\JavascriptUtils;

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
	    $this->hooks[$name] = self::generateFunction($body);
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
		$this->addHook("updated", "this.\$nextTick(function () {".$body."})");
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
        $this->data["data"][$name]=self::removeQuotes($value);
	}
	
	public function addMethod(string $name,string $body, array $params = []):void {
        $this->methods["methods"][$name]=self::generateFunction($body,$params);
	}
	
	public function addComputed(string $name,string $get,string $set=null):void {
        $name=self::removeQuotes($name);
	    $vc=(is_null($set)) ? self::generateFunction($get) : self::removeQuotes("{ get: ".self::generateFunction($get).", set: ".self::generateFunction($set,["v"])." }");
	    $this->computeds["computeds"][$name]=$vc;
	}
	
	public function addWatcher(string $var,string $body,array $params=[]):void {
	    $this->watchers["watch"][$var]=self::generateFunction($body,$params);
	}

	public function addFilter(string $name,string $body, array $params = []):void {
	    $name=self::removeQuotes($name);
        $this->methods["filters"][$name]=self::generateFunction($body,$params);
    }

    public function addDirective(string $name,array $hookFunction):void {
	    $name = self::removeQuotes($name);
	    foreach ($hookFunction as $key=>$value){
	        $hookFunction[$key] = self::generateFunction($value,['el', 'binding', 'vnode', 'oldVnode']);
        }
	    $this->directives["directives"][$name] = self::removeQuotes(JavascriptUtils::arrayToJsObject($hookFunction));
    }

    public static function addGlobalDirective(){}

    public static function addGlobalFilter(string $name,string $body, array $params = []):void {
        self::$global[]=self::removeQuotes("Vue.filter('".$name."',".self::generateFunction($body,$params).");");
    }

    public static function addGlobalObservable(string $varName, array $object){
        self::$global[]=self::removeQuotes("const ".$varName." = Vue.observable(". JavascriptUtils::arrayToJsObject($object) .");");
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

    public function getDirectives():array {
        return $this->directives;
    }

    public function setDirectives(array $directives):void {
        $this->directives = $directives;
    }
	
	public function getHooks():array {
		return $this->hooks;
	}
	
	public function setHooks(array $hooks):void {
		$this->hooks = $hooks;
	}

	public static function removeQuotes(string $body):string{
        return self::$removeQuote["start"].$body.self::$removeQuote["end"];
    }

	public static function generateFunction(string $body, array $params = []):string {
        return self::removeQuotes("function(".implode(",",$params)."){".$body."}");
    }
}