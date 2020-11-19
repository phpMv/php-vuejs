<?php
namespace PHPMV;

/**
 * PHPMV$VueJS
 * This class is part of php-vuejs
 *
 * @author jc
 * @version 1.0.0
 *
 */
use PHPMV\js\JavascriptUtils;

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
	    $variables=['!app'=>$this->getApp(),'!vuetify'=>',vuetify: new Vuetify()','!data'=>$this->data,'!methods'=>$this->methods,'!computeds'=>$this->computeds];
	    $script=file_get_contents("template/vuejs",true);
	    $script=str_replace(array_keys($variables),$variables,$script);
	    $script=JavascriptUtils::wrapScript($script);
	    return $script;
	}	
}
$vue=new VueJS();
$vue->addData("testData","value");
$vue->addMethod("testMethod","console.log('ok')");
