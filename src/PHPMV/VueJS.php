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
class VueJS extends AbstractVueJS {

	protected string $varName;

	public function __construct(array $configuration = ['el' => '#app'], string $varName = "app", bool $useVuetify = false) {
		parent::__construct($configuration);
		$this->varName = $varName;
		if ($useVuetify) {
			$this->configuration['vuetify'] = JavascriptUtils::removeQuotes("new Vuetify()");
		}
	}

	protected function generateVueObject(string $object): string {
		return "new Vue($object)";
	}

	public function __toString(): string {
		$script = $this->generateVueObject(JavascriptUtils::arrayToJsObject($this->configuration + $this->components + $this->directives + $this->filters + $this->mixins + $this->data + $this->computeds + $this->watchers + $this->hooks + $this->methods));
		$script = JavascriptUtils::declareVariable('const', $this->varName, $script);
		return $script;
	}
}