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
	protected $app="'#app'"; //this is the default identifier the vue wrapper
	protected $useAxios;
	protected $vuetify;
	
	/**
	 * @return string
	 */
	public function getApp():string{
		return $this->app;
	}
	
	/**
	 * @return string
	 */
	public function __toString():string{
	    $this->script['el']=$this->getApp();
	    $script="const app=new Vue(";
	    $script.=JavascriptUtils::arrayToJsObject(array_merge($this->script,$this->hooks));
	    $script=JsUtils::cleanJSONFunctions($script);
	    $script.=")";
	    $script=JavascriptUtils::wrapScript($script);
	    return $script;
	}	
}
$vue=new VueJS();
$vue->addData("testData",array(0,1,2));
$vue->addDataRaw("testData1","[3,4,5]");
$vue->addMethod("testMethod","this.testData=this.testData1");
$vue->addMethod("testMethod1","this.testData=1");
$vue->addComputed("testComputed","this.testData1='newdata'","var names = v");
//$vue->onBeforeMount("alert('Before mount ok');");
