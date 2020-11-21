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
	protected $configuration=[];
	
	public function __construct(string $app="#app",bool $vuetify=false,bool $useAxios=false) {
	    parent::__construct();
	    $this->configuration['el']='"'.$app.'"';
	    if($vuetify){$this->configuration['vuetify']="new Vuetify()";};
	    $this->useAxios=$useAxios;
	}
	
	/**
	 * @return string
	 */
	public function __toString():string {
	    $script="";
	    if($this->useAxios){$script.="Vue.prototype.\$http = axios;\n";}
	    $script.="const app=new Vue(";
	    $script.=JavascriptUtils::arrayToJsObject(array_merge($this->configuration,$this->data,$this->methods,$this->watchers,$this->computeds,$this->hooks));
	    $script=JsUtils::cleanJSONFunctions($script);
	    $script.=")";
	    $script=JavascriptUtils::wrapScript($script);
	    return $script;
	}	
	
	public function getUseAxios():bool {
	    return $this->useAxios;
	}
	
	public function setUseAxios(bool $useAxios):void {
	    $this->useAxios=$useAxios;
	}
	
	public function getConfiguration():array {
	    return $this->configuration;
	}
	
	public function setConfiguration(array $configuration):void {
	    $this->configuration=$configuration;
	}
}

$vue=new VueJS("v-app",true,false);
$vue->addData("valid",true);
$vue->addDataRaw("name","''");
$vue->addDataRaw("nameRules","[v => !!v || 'Name is required',v => (v && v.length <= 10) || 'Name must be less than 10 characters']");
$vue->addDataRaw("email","''");
$vue->addData("select",null);
$vue->addData("items",['It is great fun','Fun enough to enjoy my day','It wasn\'t, was it ?']);
$vue->addData("checkbox",false);
$vue->addMethod("validate","this.\$refs.form.validate()");
$vue->addMethod("reset","this.\$refs.form.reset()");
$vue->addWatcher("name","if(this.name=='Guillaume'){console.log('watcher succeed')}");
$vue->addComputed("testComputed","console.log('ok')");
$vue->onMounted("alert('The page is created');");