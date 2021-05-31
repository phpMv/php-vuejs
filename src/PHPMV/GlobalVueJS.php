<?php
namespace PHPMV;


use PHPMV\js\JavascriptUtils;
use PHPMV\utils\JsUtils;

class GlobalVueJS{

    protected static function addImport($import):void {
        $vueManager = VueManager::getInstance();
        $vueManager->addImport($import);
    }

    public static function addGlobalDirective(string $name,array $hookFunction):void {
        foreach ($hookFunction as $key => $value){
            $hookFunction[$key] = JsUtils::generateFunction($value,['el', 'binding', 'vnode', 'oldVnode'],false);
        }
        GlobalVueJS::addImport("Vue.directive('".$name."',".JavascriptUtils::arrayToJsObject($hookFunction).");\n");
    }

    public static function addGlobalFilter(string $name,string $body, array $params = []):void {
        GlobalVueJS::addImport("Vue.filter('".$name."',".JsUtils::generateFunction($body,$params,false).");\n");
    }

    public static function addGlobalObservable(string $varName, array $object):void {
        GlobalVueJS::addImport(JsUtils::declareVariable('const',$varName,"Vue.observable(". JavascriptUtils::arrayToJsObject($object) .")"));
    }

    public static function addGlobalComponent(VueJSComponent $component):void {
        GlobalVueJS::addImport($component->generateGlobalScript());
    }
}