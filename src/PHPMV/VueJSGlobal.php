<?php
namespace PHPMV;


use PHPMV\js\JavascriptUtils;
use PHPMV\utils\JsUtils;

class VueJSGlobal{

    protected static function addImport($import):void {
        $vueManager = VueManager::getInstance();
        $vueManager->addImport($import);
    }

    public static function addGlobalDirective(string $name,array $hookFunction):void {
        foreach ($hookFunction as $key => $value){
            $hookFunction[$key] = JsUtils::generateFunction($value,['el', 'binding', 'vnode', 'oldVnode'],false);
        }
        VueJSGlobal::addImport("Vue.directive('".$name."',".JavascriptUtils::arrayToJsObject($hookFunction).");");
    }

    public static function addGlobalFilter(string $name,string $body, array $params = []):void {
        VueJSGlobal::addImport("Vue.filter('".$name."',".JsUtils::generateFunction($body,$params,false).");");
    }

    public static function addGlobalObservable(string $varName, array $object):void {
        VueJSGlobal::addImport(JsUtils::declareVariable('const',$varName,"Vue.observable(". JavascriptUtils::arrayToJsObject($object) .")", false));
    }

    public static function addGlobalComponent(VueJSGlobalComponent $component):void {
        VueJSGlobal::addImport($component->generateGlobalScript());
    }
}