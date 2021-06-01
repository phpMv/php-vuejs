<?php
namespace PHPMV;

use PHPMV\utils\JsUtils;

class VueJSGlobalComponent extends VueJSComponent {

    public function generateGlobalScript():string {
        $script = "Vue.component('".$this->name."',";
        $script .= $this->generateObject();
        $script .= ")";
        return $script;
    }

    public function generateFile(bool $inVariable = false, bool $global = false):void {
        $script = ($inVariable) ? JsUtils::declareVariable("const", $this->varName, $this->generateGlobalScript(), false) : $this->generateGlobalScript().";";
        if (!$global){
            \file_put_contents($this->name.".js",$script);
        }
        elseif(file_exists("components.js")){
            \file_put_contents("components.js",PHP_EOL . $script,FILE_APPEND);
        }
        else{
            \file_put_contents("components.js",$script);
        }
    }
}