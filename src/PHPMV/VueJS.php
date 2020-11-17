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
	    $variables=['!app'=>$this->getApp(),'!vuetify'=>'new Vuetify()','!data'=>$this->data,'!methods'=>$this->methods]; //liste des variables à remplacé dans le fichier template
	    $script=file_get_contents("template_vuejs");
	    $script=str_replace(array_keys($variables),$variables,$script);
	    $script=JavascriptUtils::wrapScript($script);
	    return $script;
	}	
}

$vue=new VueJS();
$vue->addData("testData",array("1","2"));
$vue->addDataRaw("testRaw","[]");
$vue->addMethod("test","let self=this;this.testData='test'");
$vue->addMethod("testParam","this.testData=i","i");