<?php
namespace PHPMV;

use PHPMV\js\JavascriptUtils;
use PHPMV\utils\JsUtils;

class VueJSComponent extends AbstractVueJS{
    protected $name;
    protected $props=["props"=>[]];
    protected $template;
    
    public function __construct(string $template) {
        parent::__construct();
        $this->template["template"]="'".\str_replace(["\n","\r","\t"]," ",(\file_get_contents($template.'.html',FILE_USE_INCLUDE_PATH))."'");
        $this->name=$template;
    }
    
    public function setProps(string ...$name):void {
        $this->props["props"]=$name;
    }
    
    public function create():string {
        $script="Vue.component('".$this->name."',";
        $script.=JavascriptUtils::arrayToJsObject($this->props + $this->data + $this->methods + $this->computeds + $this->watchers + $this->hooks + $this->template);
        $script=JsUtils::cleanJSONFunctions($script);
        $script.=")";
        \file_put_contents($this->name.".js", $script);
        return $script;
    }
}