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

	protected bool $useAxios;

	protected array $configuration;

	public function __construct(array $configuration = ['el'=>'#app'], bool $useVuetify=false, bool $useAxios=false) {
		parent::__construct();
		$configuration['el'] = '"'. $configuration['el'] .'"';
		$this->configuration = $configuration;
        if ($useVuetify){
            $this->configuration['vuetify'] = "new Vuetify()";
        }
        $this->useAxios = $useAxios;
	}

	public function __toString(): string {
        $script = "";
		if ($this->useAxios) {
			$script .= "Vue.prototype.\$http = axios;\n";
		}
		$script .= "const app=new Vue(";
		$script .= JavascriptUtils::arrayToJsObject($this->configuration + $this->directives + $this->filters + $this->data + $this->computeds + $this->watchers + $this->hooks + $this->methods);
		$script = JsUtils::cleanJSONFunctions($script);
		$script .= ")";
		$script = JavascriptUtils::wrapScript($script);
		return $script;
	}

	public function getUseAxios(): bool {
		return $this->useAxios;
	}

	public function setUseAxios(bool $useAxios): void {
		$this->useAxios = $useAxios;
	}

	public function getConfiguration(): array {
		return $this->configuration;
	}

	public function setConfiguration(array $configuration): void {
		$this->configuration = $configuration;
	}
}