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

	protected function getTemplateComponentDirectory(): string {
		return $this->config['templateDir'] ?? 'vuejs/';
	}

	public static function getInstance(?object $container = null): ?VueManager {
		if (!isset(self::$instance)) {
			self::$instance = new static();
		}
		if (isset($container)) {
			self::$instance->container = $container;
		}
		return self::$instance;
	}

	public static function deleteInstance(): void {
		VueManager::$instance = null;
	}

	public function importComponentObject(VueJSComponent $component): void { //component, mixin, or extend
		$this->imports[] = $component;
	}

	public function addGlobalDirective(string $name, array $hookFunction) {
		foreach ($hookFunction as $key => $value) {
			$hookFunction[$key] = JavascriptUtils::generateFunction($value, ['el', 'binding', 'vnode', 'oldVnode']);
		}
		$this->imports[] = "Vue.directive('$name'," . JavascriptUtils::arrayToJsObject($hookFunction) . ");";
	}

	public function addGlobalFilter(string $name, string $body, array $params = []): void {
		$this->imports[] = "Vue.filter('$name'," . JavascriptUtils::generateFunction($body, $params, false) . ");";
	}

	public function addGlobalObservable(string $varName, array $object): void {
		$this->imports[] = JavascriptUtils::declareVariable('const', $varName, "Vue.observable(" . JavascriptUtils::arrayToJsObject($object) . ")", false);
	}

	public function addGlobalMixin(VueJSComponent $mixin): void {
		$mixin->setTypeAndGlobal('mixin');
		$this->imports[] = $mixin;
	}

	public function addGlobalExtend(VueJSComponent $extend): void {
		$extend->setTypeAndGlobal('extend');
		$this->imports[] = $extend;
	}

	public function addGlobalComponent(VueJSComponent $component): void {
		$component->setGlobal(true);
		$this->imports[] = $component;
	}

	public function addVue(VueJS $vue): void {
		$this->vues[] = $vue;
	}

	/**
	 * Creates, adds and returns a new VueJS instance.
	 *
	 * @param string $element The dom selector associated with this Vue
	 * @param string|null $varName The vue variable name
	 * @param false $useVuetify True if Vuutify is used
	 * @return VueJS The created vue
	 */
	public function createVue(string $element, ?string $varName = null, bool $useVuetify = false): VueJS {
		$config = $this->config;
		$config['el'] = $element;
		$varName ??= 'app' . (\count($this->vues) + 1);
		return $this->vues[] = new VueJS($config, $varName, $useVuetify);
	}

	/**
	 * Creates, adds and returns a new VueJSComponent instance (component, mixin or extend).
	 * @param string $name The component name
	 * @param string|null $varName The component varName
	 * @param string $type The object type : one of component (default value), extend or mixin
	 * @return VueJSComponent The created component
	 */
	public function createComponent(string $name, string $varName = null, string $type='component'): VueJSComponent {
		$compo=new VueJSComponent($name,$varName,$type);
		$this->addGlobalComponent($compo);
		return $compo;
	}

	public function __toString(): string {
		$script = '';
		if ($this->useAxios) {
			$script = 'Vue.prototype.$http = axios;' . \PHP_EOL;
		}

		foreach ($this->imports as $import) {
			$script .= \implode(\PHP_EOL, $import);
		}
		foreach ($this->vues as $vue) {
			$script .= \implode(\PHP_EOL, $vue);
		}

		$script = JavascriptUtils::cleanJSONFunctions($script);
		return JavascriptUtils::wrapScript($script);
	}

	public function setAxios(bool $useAxios): void {
		$this->useAxios = $useAxios;
	}

	/**
	 * Sets the global VueJS configuration array with el, delimiters, useAxios, templateDir.
	 *
	 * @param array $config
	 */
	public function setConfig(array $config): void {
		if (isset($config['useAxios'])) {
			$this->setAxios($config['useAxios']);
			unset($config['useAxios']);
		}
		$this->config = $config;
	}
}