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
	    $script.=JavascriptUtils::arrayToJsObject($this->script);
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
$vue->onBeforeMount("alert('Before mount ok');");
