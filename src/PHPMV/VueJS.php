<?php
namespace PHPMV;

use PHPMV\js\JavascriptUtils;
use PHPMV\utils\JsUtils;

/**
 * PHPMV$VueJS
 * This class is part of php-vuejs
 *
 * @author jc
 * @version 1.0.0
 *
 */
class VueJS extends AbstractVueJS{
	protected $useAxios;
	protected $configuration=array();
	
	/**
	 * @return string
	 */
	public function __toString():string{
	    $script="";
	    if($this->useAxios){$script.="Vue.prototype.\$http = axios;\n";}
	    $script.="const app=new Vue(";
	    $script.=JavascriptUtils::arrayToJsObject(array_merge($this->configuration,$this->data,$this->methods,$this->computeds,$this->hooks));
	    $script=JsUtils::cleanJSONFunctions($script);
	    $script.=")";
	    $script=JavascriptUtils::wrapScript($script);
	    return $script;
	}	
	
	public function __construct(string $app="#app",bool $vuetify=false,bool $useAxios=false){
	    parent::__construct();
	    $this->configuration['el']='"'.$app.'"';
	    if($vuetify){$this->configuration['vuetify']="new Vuetify()";}; 
	    $this->useAxios=$useAxios;
	}
}
$vue=new VueJS("#app",true,false);
$vue->addData("testData",array(0,1,2));
$vue->addDataRaw("testData1","[3,4,5]");
$vue->addMethod("testMethod","this.testData=this.testData1");
$vue->addMethod("testMethod1","this.testData=1");
$vue->addComputed("testComputed","this.testData1='newdata'","var names = v");
$vue->onBeforeMount("alert('Before mount ok');");
