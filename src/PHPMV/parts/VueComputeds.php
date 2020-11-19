<?php
namespace PHPMV\parts;

use PHPMV\core\VueLibrary;
use PHPMV\js\JavascriptUtils;
use PHPMV\core\TemplateParser;

class VueComputeds extends VuePart {
    
    public function add(string $name,string $get,string $set=null):void{
        $setComputed=null;
        if(!is_null($set)){
            $setComputed=new VueComputed($get, $set);
        }
        parent::put($name, $setComputed);
    }
    
    public function __toString():string{
    	$data=parent::__toString();
    	if($data!=""){
    	    $this->renderTemplate = new TemplateParser(); //load the template file
    	    $this->renderTemplate->loadTemplatefile(VueLibrary::getTemplateFolder() . '/computeds'); //parse the template with some variables
    	    $result=$this->renderTemplate->parse(['data'=>$data]);
    	    return $result;
    	}
    	return "";
    }
}

