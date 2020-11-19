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
use PHPMV\core\TemplateParser;
use PHPMV\core\VueLibrary;
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
	    $this->renderTemplate = new TemplateParser(); //load the template file
	    $this->renderTemplate->loadTemplatefile(VueLibrary::getTemplateFolder() . '/vuejs'); //parse the template with some variables
	    $result=$this->renderTemplate->parse([
	        'app'=>$this->getApp(),
	        'vuetify'=>',vuetify: new Vuetify()',
	        'data'=>$this->data,
	        'hooks'=>$this->hooks,
	        'methods'=>$this->methods,
	        'computeds'=>$this->computeds]);
	    $result=str_replace(['!!#{','}!!#','"!!#','!!#"'],"",$result);
	    $result=JavascriptUtils::wrapScript($result);
	    return $result;
	    
	}	
}
$vue=new VueJS();
$vue->addData("testData","value");
$vue->addMethod("testMethod","console.log('ok')");
$vue->onBeforeMount("alert('Before mount ok');");
