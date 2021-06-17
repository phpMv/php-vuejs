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

	protected function addImport($import): void {
		$this->imports[] = $import;
	}

	protected function addGlobal(string $type, string $body, string $name = null): void {
		if ($name) {
			$this->addImport("Vue.$type('$name',$body);");
		} else {
			$this->addImport("Vue.$type($body);");
		}
	}

	public function importComponentObject(VueJSComponent $component): void { //component, mixin, or extend
		$this->addImport(JavascriptUtils::declareVariable('const', $component->getVarName(), $component->generateObject(), false));
	}

	public function addGlobalDirective(string $name, array $hookFunction) {
		foreach ($hookFunction as $key => $value) {
			$hookFunction[$key] = JavascriptUtils::generateFunction($value, ['el', 'binding', 'vnode', 'oldVnode']);
		}
		$this->addGlobal('directive', JavascriptUtils::arrayToJsObject($hookFunction), $name);
	}

	public function addGlobalFilter(string $name, string $body, array $params = []): void {
		$this->addGlobal('filter', JavascriptUtils::generateFunction($body, $params, false), $name);
	}

	public function addGlobalExtend(VueJSComponent $extend): void {
		$this->addGlobal('extend', $extend->generateObject());
	}

	public function addGlobalMixin(VueJSComponent $mixin): void {
		$this->addGlobal('mixin', $mixin->generateObject());
	}

	public function addGlobalObservable(string $varName, array $object): void {
		$this->addImport(JavascriptUtils::declareVariable('const', $varName, "Vue.observable(" . JavascriptUtils::arrayToJsObject($object) . ")", false));
	}

	public function addGlobalComponent(VueJSComponent $component): void {
		$this->addImport($component->generateGlobalScript());
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
	public function createVue(string $element,?string $varName=null,$useVuetify=false): VueJS {
		$config=$this->config;
		$config['el']=$element;
		$varName??='app'.(\count($this->vues)+1);
		return $this->vues[]=new VueJS($config,$varName,$useVuetify);
	}

	public function __toString(): string {
		$script = '';
		if ($this->useAxios) $script = 'Vue.prototype.$http = axios;' . PHP_EOL;
		$script .= \implode(PHP_EOL, $this->imports);
		$script .= PHP_EOL . \implode(PHP_EOL, $this->vues);
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