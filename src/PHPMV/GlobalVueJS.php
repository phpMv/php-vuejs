<?php
namespace PHPMV;


use PHPMV\js\JavascriptUtils;
use PHPMV\utils\JsUtils;

class GlobalVueJS{

    private static ?GlobalVueJS $instance = null;
    protected array $global;

    protected function __construct() {
        $this->global = [];
    }

    public static function getInstance():?GlobalVueJS {
        if (!isset(self::$instance)) {
            self::$instance = new GlobalVueJS();
        }
        return self::$instance;
    }

    public static function deleteInstance():void {
        self::$instance = null;
    }

    public function addGlobalDirective(string $name,array $hookFunction):void {
        foreach ($hookFunction as $key => $value){
            $hookFunction[$key] = JsUtils::generateFunction($value,['el', 'binding', 'vnode', 'oldVnode'],false);
        }
        $this->global[] = "Vue.directive('".$name."',".JavascriptUtils::arrayToJsObject($hookFunction).");\n";
    }

    public function addGlobalFilter(string $name,string $body, array $params = []):void {
        $this->global[] = "Vue.filter('".$name."',".JsUtils::generateFunction($body,$params,false).");\n";
    }

    public function addGlobalObservable(string $varName, array $object):void {
        $this->global[] = JsUtils::declareVariable('const',$varName,"Vue.observable(". JavascriptUtils::arrayToJsObject($object) .")");
    }

    public function addGlobalComponent(VueJSComponent $component):void {
        $this->global[] = $component->generateGlobalScript();
    }

    public function __toString():string {
        $script="";
        if(!empty($this->global)){
            foreach($this->global as $global){
                $script .= $global."\n";
            }
        }
        $script = JsUtils::cleanJSONFunctions($script);
        $script = JavascriptUtils::wrapScript($script);
        return $script;
    }

    public function getGlobal():array {
        return $this->global;
    }
}