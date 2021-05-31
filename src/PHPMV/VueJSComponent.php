<?php
namespace PHPMV;

use PHPMV\js\JavascriptUtils;
use PHPMV\utils\JsUtils;

class VueJSComponent extends AbstractVueJS{
    protected string $name;
    protected array $props;
    protected array $template;

    public function __construct(string $template, array $props = []) {
        parent::__construct();
        $this->props["props"] = $props;
        $this->template["template"] = "'".\str_replace(["\n","\r","\t"]," ",(\file_get_contents($template.'.html',true))."'");
        $this->name = $template;
    }

    public function generateLocalScript():string {
        $script = JavascriptUtils::arrayToJsObject($this->props + $this->components + $this->filters +$this->data + $this->computeds + $this->watchers = $this->hooks + $this->methods + $this->template);
        $script = JsUtils::cleanJSONFunctions($script);
        return $script;
    }

    public function generateGlobalScript():string {
        $script = "Vue.component('".$this->name."',";
        $script .= $this->generateLocalScript();
        $script .= ");\n";
        return $script;
    }

    public function generateFile($global = false):void {
        $script = $this->generateGlobalScript();
        if(!$global){
            \file_put_contents($this->name.".js",$script);
        }
        else{
            if(file_exists("components.js")){
                \file_put_contents("components.js",PHP_EOL . $script,FILE_APPEND);
            }
            else{
                \file_put_contents("components.js",$script);
            }
        }
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
}