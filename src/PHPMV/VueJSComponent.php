<?php

namespace PHPMV;

use PHPMV\js\JavascriptUtils;
use PHPMV\utils\JsUtils;

class VueJSComponent extends AbstractVueJS {
    protected string $name;
    protected array $props;
    protected array $template;
    protected array $extends;
    protected ?string $varName;

    public function __construct(string $name,string $varName = null) {
        parent::__construct();
        $this->name = $name;
        $this->props = [];
        $this->extends = [];
        $this->template = [];
        if(!$varName){
            $varName = JsUtils::kebabToPascal($name);
        }
        $this->varName = $varName;
    }

    public function extends(VueJSComponent $component):void {
        $this->extends['extends'] = $component->getVarName();
    }

    public function generateObject():string {
        $script = JavascriptUtils::arrayToJsObject($this->props + $this->components + $this->filters + $this->extends + $this->mixins + $this->data + $this->computeds + $this->watchers + $this->hooks + $this->methods + $this->template);
        $script = JsUtils::cleanJSONFunctions($script);
        return $script;
    }

    public function generateGlobalScript():string {
        $script = "Vue.component('".$this->name."',";
        $script .= $this->generateObject();
        $script .= ");";
        return $script;
    }

    public function generateFile(bool $inVariable = false, bool $global = false):void {
        $script = ($inVariable) ? JsUtils::declareVariable("const", $this->varName, $this->generateGlobalScript(), false) : $this->generateGlobalScript();
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

    public function addProps(string ...$props):void {
        $this->props["props"] = $props;
    }

    public function addTemplate(string $template):void {
        $this->template["template"] = $template;
    }

    public function importTemplate(string $template):void {
        $this->template["template"] = "'".\str_replace(["\n","\r","\t"]," ",(\file_get_contents($template.'.html',true))."'");
    }

    public function onActivated(string $body):void {
        $this->addHook("activated", $body);
    }

    public function onDeactivated(string $body):void {
        $this->addHook("deactivated", $body);
    }

    public function getName():string {
        return $this->name;
    }

    public function getVarName():string {
        return $this->varName;
    }
}