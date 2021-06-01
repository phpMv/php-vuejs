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

    public function importComponent(VueJSComponent $component):void {
        $varName = $component->getVarName();
        if(!$varName){
            $varName = JsUtils::kebabToPascal($component->getName());
        }
        $this->addImport(JsUtils::declareVariable('const', $varName, $component->generateObject(), false));
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