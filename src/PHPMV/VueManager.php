<?php

namespace PHPMV;

use PHPMV\js\JavascriptUtils;
use PHPMV\utils\JsUtils;

class VueManager {

	private static ?VueManager $instance = null;
	protected array $imports;
	protected array $vues;
	protected bool $useAxios;

	protected function __construct() {
		$this->imports = [];
		$this->vues = [];
		$this->useAxios = false;
	}

	public static function getInstance(): ?VueManager {
		if (!isset(self::$instance)) {
			self::$instance = new VueManager();
		}
		return self::$instance;
	}

	public static function deleteInstance(): void {
		VueManager::$instance = null;
	}

	protected function addImport($import): void {
		$this->imports[] = $import;
	}

	protected function importComponentObject(VueJSComponent $component): void {
		$this->addImport(JsUtils::declareVariable('const', $component->getVarName(), $component->generateObject(), false));
	}

	public function importComponent(VueJSComponent $component): void {
		$this->importComponentObject($component);
	}

	public function importMixin(VueJSComponent $mixin): void {
		$this->importComponentObject($mixin);
	}

	public function importExtend(VueJSComponent $extend): void {
		$this->importComponentObject($extend);
	}

	protected function addGlobal(string $type, string $name, string $body): void {
		$this->addImport("Vue." . $type . "('" . $name . "'," . $body . ");");
	}

	public function addGlobalDirective(string $name, array $hookFunction, array $configuration = []) {
		foreach ($configuration as $key => $value) {
			$configuration[$key] = $value;
		}
		foreach ($hookFunction as $key => $value) {
			$hookFunction[$key] = JsUtils::generateFunction($value, ['el', 'binding', 'vnode', 'oldVnode'], false);
		}
		$this->addGlobal('directive', $name, JavascriptUtils::arrayToJsObject($configuration + $hookFunction));
	}

	public function addGlobalFilter(string $name, string $body, array $params = []): void {
		$this->addGlobal('filter', $name, JsUtils::generateFunction($body, $params, false));
	}

	public function addGlobalExtend(VueJSComponent $extend): void {
		$this->addGlobal('extend', $extend->getName(), $extend->generateObject());
	}

	public function addGlobalMixin(VueJSComponent $mixin): void {
		$this->addGlobal('mixin', $mixin->getName(), $mixin->generateObject());
	}

	public function addGlobalObservable(string $varName, array $object): void {
		$this->addImport(JsUtils::declareVariable('const', $varName, "Vue.observable(" . JavascriptUtils::arrayToJsObject($object) . ")", false));
	}

	public function addGlobalComponent(VueJSComponent $component): void {
		$this->addImport($component->generateGlobalScript());
	}

	public function addVue(VueJS $vue): void {
		$this->vues[] = $vue;
	}

	public function __toString(): string {
		$script = "";
		if ($this->useAxios) $script = "Vue.prototype.\$http = axios;\n";
		$script .= implode(PHP_EOL, $this->imports);
		$script .= PHP_EOL . implode(PHP_EOL, $this->vues);
		return JavascriptUtils::wrapScript($script);
	}

	public function setAxios(bool $useAxios): void {
		$this->useAxios = $useAxios;
	}
}