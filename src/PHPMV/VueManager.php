<?php
namespace PHPMV;

use PHPMV\js\JavascriptUtils;
use PHPMV\utils\JsUtils;

class VueManager{

    private static ?VueManager $instance = null;
    protected array $imports;
    protected array $vues;

    protected function __construct() {
        $this->imports = [];
        $this->vues = [];
    }

    public static function getInstance():?VueManager {
        if (!isset(self::$instance)) {
            self::$instance = new VueManager();
        }
        return self::$instance;
    }

    public static function deleteInstance():void {
        VueManager::$instance = null;
    }

    public function addImport($import):void {
        $this->imports[] = $import;
    }

    protected function importComponentObject(VueJSComponent $component):void {
        $this->addImport(JsUtils::declareVariable('const', $component->getVarName(), $component->generateObject(), false));
    }

    public function importComponent(VueJSComponent $component):void {
        $this->importComponentObject($component);
    }

    public function importMixin(VueJSComponent $mixin):void {
        $this->importComponentObject($mixin);
    }

    public function importExtend(VueJSComponent $extend):void {
        $this->importComponentObject($extend);
    }

    protected function addGlobal(string $type, string $name, string $body):void {
        $this->addImport("Vue.".$type."('".$name."',".$body.");");
    }

    protected function addDirective(string $name, array $hookFunction, string $type, array $configuration = []){
        foreach($configuration as $key => $value){
            $configuration[$key] = $value;
        }
        foreach ($hookFunction as $key => $value){
            $hookFunction[$key] = JsUtils::generateFunction($value,['el', 'binding', 'vnode', 'oldVnode'],false);
        }
        $this->addGlobal($type, $name, JavascriptUtils::arrayToJsObject($configuration + $hookFunction));
    }

    public function addGlobalDirective(string $name, array $hookFunction, array $configuration = []):void {
        $this->addDirective($name, $hookFunction, 'directive', $configuration);
    }

    public function addGlobalElementDirective(string $name, array $hookFunction, array $configuration = []):void {
        $this->addDirective($name, $hookFunction, 'elementDirective', $configuration);
    }

    public function addGlobalFilter(string $name,string $body, array $params = []):void {
        $this->addGlobal('filter', $name, JsUtils::generateFunction($body,$params,false));
    }

    public function addGlobalExtend(VueJSComponent $extend):void {
        $this->addGlobal('extend', $extend->getName(), $extend->generateObject());
    }

    public function addGlobalMixin(VueJSComponent $mixin):void {
        $this->addGlobal('mixin',$mixin->getName(), $mixin->generateObject());
    }

    public function addGlobalObservable(string $varName, array $object):void {
        $this->addImport(JsUtils::declareVariable('const',$varName,"Vue.observable(". JavascriptUtils::arrayToJsObject($object) .")", false));
    }

    public function addGlobalComponent(VueJSComponent $component):void {
        $this->addImport($component->generateGlobalScript());
    }

    public function addVue(VueJS $vue):void {
        $this->vues[] = $vue;
    }

    public function __toString():string {
        $script = implode("\n",$this->imports);
        $script .= implode("\n",$this->vues);
        return JavascriptUtils::wrapScript($script);
    }

    public function getImports():array {
        return $this->imports;
    }
}