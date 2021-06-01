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
class VueJS extends AbstractVueJS {

	protected array $configuration;
	protected bool $useAxios;
	protected string $varName;

	public function __construct(array $configuration = ['el'=>'#app'], string $varName = "app", bool $useAxios = false, bool $useVuetify = false) {
		parent::__construct();
		$this->useAxios = $useAxios;
		$this->varName = $varName;
		$configuration['el'] = "'". $configuration['el'] ."'";
		$this->configuration = $configuration;
        if ($useVuetify){
            $this->configuration['vuetify'] = "new Vuetify()";
        }
	}

    protected function generateVueObject(string $object):string {
        $vueObject = "new Vue(".$object.")";
        return JsUtils::cleanJSONFunctions($vueObject);
    }

	public function __toString():string {
        $script = $this->generateVueObject(JavascriptUtils::arrayToJsObject($this->configuration + $this->components + $this->directives + $this->filters + $this->mixins + $this->data + $this->computeds + $this->watchers + $this->hooks + $this->methods));
        $script = JsUtils::declareVariable('const',$this->varName,$script);
        $script .= ($this->useAxios) ? $this->varName.".prototype.\$http = axios;\n" : "";
		return $script;
	}
}