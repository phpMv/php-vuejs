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

	protected $useAxios;

	protected $configuration;

	public function __construct(string $app = "#app", bool $vuetify = false, bool $useAxios = false) {
		parent::__construct();
		$this->configuration['el'] = '"' . $app . '"';
		if ($vuetify) {
			$this->configuration['vuetify'] = "new Vuetify()";
		}
		;
		$this->useAxios = $useAxios;
	}

	public function __toString(): string {
		$script = "";
		if ($this->useAxios) {
			$script .= "Vue.prototype.\$http = axios;\n";
		}
		$script .= "const app=new Vue(";
		$script .= JavascriptUtils::arrayToJsObject($this->configuration + $this->data + $this->methods + $this->watchers + $this->computeds + $this->hooks);
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