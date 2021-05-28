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
        $this->template["template"]="'".\str_replace(["\n","\r","\t"]," ",(\file_get_contents($template.'.html',true))."'");
        $this->name=$template;
    }
    
    public function setProps(string ...$name):void {
        $this->props["props"]=$name;
    }
    
    public function create(bool $global=false):string {
        $script="Vue.component('".$this->name."',";
        $script.=JavascriptUtils::arrayToJsObject($this->props + $this->data + $this->methods + $this->computeds + $this->watchers + $this->filters + $this->hooks + $this->template);
        $script=JsUtils::cleanJSONFunctions($script);
        $script.=")";
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
        return $script;
    }
    
    public function createGlobal():string{
        return $this->create(true);   
    }
}