<?php

namespace PHPMV;

use PHPMV\js\JavascriptUtils;

/**
 * php-vueJS Manager class.
 *
 * PHPMV$VueManager
 * This class is part of php-vuejs
 *
 * @author jguillaumesio
 * @version 1.0.0
 *
 */
class VueManager {
	protected static ?VueManager $instance = null;
	protected ?object $container;
	protected array $imports;
	protected array $vues;
	protected bool $useAxios;
	protected array $config;

	protected function __construct() {
		$this->imports = [];
		$this->vues = [];
		$this->useAxios = false;
	}

	public static function getInstance(?object $container=null): ?VueManager {
		if (!isset(self::$instance)) {
			self::$instance = new static();
		}
		if(isset($container)){
			self::$instance->container=$container;
		}
		return self::$instance;
	}

	public static function deleteInstance(): void {
		VueManager::$instance = null;
	}

	protected function addImport($import): void{
			$this->imports['imports'][] = $import;
	}

	public function importComponentObject(VueJSComponent $component): void { //component, mixin, or extend
		$this->addImport($component);
	}

	public function addGlobalDirective(string $name, array $hookFunction) {
		foreach ($hookFunction as $key => $value) {
			$hookFunction[$key] = JavascriptUtils::generateFunction($value, ['el', 'binding', 'vnode', 'oldVnode']);
		}
		$this->addImport("Vue.directive('$name',".JavascriptUtils::arrayToJsObject($hookFunction).");");
	}

	public function addGlobalFilter(string $name, string $body, array $params = []): void {
		$this->addImport("Vue.filter('$name',". JavascriptUtils::generateFunction($body, $params, false) .");");
	}

	public function addGlobalObservable(string $varName, array $object): void {
		$this->addImport(JavascriptUtils::declareVariable('const', $varName, "Vue.observable(" . JavascriptUtils::arrayToJsObject($object) . ")", false));
	}

	public function addGlobalMixin(VueJSComponent $mixin): void {
		$mixin->setTypeAndGlobal('mixin');
		$this->addImport($mixin);
	}

	public function addGlobalExtend(VueJSComponent $extend): void {
		$extend->setTypeAndGlobal('extend');
		$this->addImport($extend);
	}

	public function addGlobalComponent(VueJSComponent $component): void {
		$component->setGlobal(true);
		$this->addImport($component);
	}

	public function addVue(VueJS $vue): void {
		$this->vues[] = $vue;
	}

	/**
	 * Creates and returns a new VueJS instance.
	 *
	 * @param string $element
	 * @param string|null $varName
	 * @param false $useVuetify
	 * @return VueJS
	 */
	public function createVue(string $element,?string $varName=null,bool $useVuetify=false): VueJS {
		$config=$this->config;
		$config['el']=$element;
		$varName??='app'.(\count($this->vues)+1);
		return $this->vues[]=new VueJS($config,$varName,$useVuetify);
	}

	public function __toString(): string {
		$script = '';
		if ($this->useAxios){
			$script = 'Vue.prototype.$http = axios;' . \PHP_EOL;
		}

		foreach($this->imports as $import) {
			$script.=$import.\PHP_EOL;
		}
		foreach($this->vues as $vue) {
			$script.=$vue.\PHP_EOL;
		}

		$script = JavascriptUtils::cleanJSONFunctions($script);
		return JavascriptUtils::wrapScript($script);
	}

	public function setAxios(bool $useAxios): void {
		$this->useAxios = $useAxios;
	}

	/**
	 * Sets the global VueJS configuration array with el, delimiters, useAxios.
	 *
	 * @param array $config
	 */
	public function setConfig(array $config): void {
		if(isset($config['useAxios'])) {
			$this->setAxios($config['useAxios']);
			unset($config['useAxios']);
		}
		$this->config=$config;
	}
}