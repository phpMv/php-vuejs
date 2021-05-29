<?php
namespace PHPMV;

use PHPMV\utils\JsUtils;

/**
 * Created by PhpStorm.
 * User: qgorak
 * Date: 19/11/2020
 * Time: 14:20
 */
class AbstractVueJS {
	protected array $data;
	protected array $methods;
	protected array $computeds;
	protected array $watchers;
	protected array $directives;
	protected array $filters;
	protected array $hooks;
	
	public function __construct() {
	    $this->data=[];
	    $this->methods=[];
	    $this->computeds=[];
	    $this->watchers=[];
	    $this->directives=[];
	    $this->filters=[];
	    $this->hooks=[];
	}
	
	private function addHook(string $name,string $body):void {
	    $this->hooks[$name] = JsUtils::generateFunction($body,[],false);
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
	    $name=JsUtils::removeQuotes($name);
        $this->data["data"][$name]=$value;
	}

	public function addDataRaw(string $name,string $value):void {
        $name=JsUtils::removeQuotes($name);
        $this->data["data"][$name]=JsUtils::removeQuotes($value);
	}
	
	public function addMethod(string $name,string $body, array $params = []):void {
        $name=JsUtils::removeQuotes($name);
        $this->methods["methods"][$name]=JsUtils::generateFunction($body,$params);
	}
	
	public function addComputed(string $name,string $get,string $set=null):void {
        $name=JsUtils::removeQuotes($name);
	    $vc=(is_null($set)) ? JsUtils::generateFunction($get) : JsUtils::removeQuotes("{ get: ".JsUtils::generateFunction($get,[],false).", set: ".JsUtils::generateFunction($set,["v"],false)." }");
	    $this->computeds["computeds"][$name]=$vc;
	}
	
	public function addWatcher(string $var,string $body,array $params=[]):void {
	    $this->watchers["watch"][$var]=JsUtils::generateFunction($body,$params);
	}

	public function addFilter(string $name,string $body, array $params = []):void {
	    $name=JsUtils::removeQuotes($name);
        $this->filters["filters"][$name]=JsUtils::generateFunction($body,$params);
    }

    public function addDirective(string $name,array $hookFunction):void {
	    $name = JsUtils::removeQuotes($name);
	    foreach ($hookFunction as $key=>$value){
            $key = JsUtils::removeQuotes($key);
            $this->directives["directives"][$name][$key] = JsUtils::generateFunction($value,['el', 'binding', 'vnode', 'oldVnode']);
        }
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

    public function getFilters():array {
        return $this->filters;
    }

    public function setFilters(array $filters):void {
        $this->filters = $filters;
    }
	
	public function getHooks():array {
		return $this->hooks;
	}
	
	public function setHooks(array $hooks):void {
		$this->hooks = $hooks;
	}
}