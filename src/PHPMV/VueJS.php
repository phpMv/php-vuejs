<?php
namespace PHPMV;

use PHPMV\js\JavascriptUtils;

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
	    $script=str_replace("!!#","",$script);
	    $script.=")";
	    $script=JavascriptUtils::wrapScript($script);
	    return $script;
	}	
}
$vue=new VueJS();
$vue->addData("testData","'value'");
$vue->addData("testData1", "'valueModified'");
$vue->addMethod("testMethod","this.testData=this.testData1");
$vue->addComputed("testComputed","this.testData1='newdata'","var names = v");
$vue->onBeforeMount("alert('Before mount ok');");
