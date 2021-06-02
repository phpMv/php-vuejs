<?php

namespace PHPMV;

use PHPMV\utils\JsUtils;

/**
 * Created by PhpStorm.
 * User: qgorak
 * Date: 19/11/2020
 * Time: 14:20
 */
abstract class AbstractVueJS {
	protected array $data;
	protected array $methods;
	protected array $computeds;
	protected array $watchers;
	protected array $components;
	protected array $directives;
	protected array $filters;
	protected array $hooks;
	protected array $mixins;

	protected function __construct() {
		$this->data = [];
		$this->methods = [];
		$this->computeds = [];
		$this->watchers = [];
		$this->components = [];
		$this->directives = [];
		$this->filters = [];
		$this->hooks = [];
		$this->mixins = [];
	}

	protected function addHook(string $name, string $body): void {
		$this->hooks[$name] = JsUtils::generateFunction($body, [], false);
	}

	public function onBeforeCreate(string $body): void {
		$this->addHook("beforeCreate", $body);
	}

	public function onCreated(string $body): void {
		$this->addHook("created", $body);
	}

	public function onBeforeMount(string $body): void {
		$this->addHook("beforeMount", $body);
	}

	public function onMounted(string $body): void {
		$this->addHook("mounted", $body);
	}

	public function onBeforeUpdate(string $body): void {
		$this->addHook("beforeUpdate", $body);
	}

	public function onUpdated(string $body): void {
		$this->addHook("updated", $body);
	}

	public function onUpdatedNextTick(string $body): void {
		$this->addHook("updated", "this.\$nextTick(function () {" . $body . "})");
	}

	public function onBeforeDestroy(string $body): void {
		$this->addHook("beforeDestroy", $body);
	}

	public function onDestroyed(string $body): void {
		$this->addHook("destroyed", $body);
	}

	public function addData(string $name, $value): void {
		$name = JsUtils::removeQuotes($name);
		$this->data["data"][$name] = $value;
	}

	public function addDataRaw(string $name, string $value): void {
		$name = JsUtils::removeQuotes($name);
		$this->data["data"][$name] = JsUtils::removeQuotes($value);
	}

	public function addMethod(string $name, string $body, array $params = []): void {
		$name = JsUtils::removeQuotes($name);
		$this->methods["methods"][$name] = JsUtils::generateFunction($body, $params);
	}

	public function addComputed(string $name, string $get, string $set = null): void {
		$name = JsUtils::removeQuotes($name);
		$vc = (is_null($set)) ? JsUtils::generateFunction($get) : JsUtils::removeQuotes("{ get: " . JsUtils::generateFunction($get, [], false) . ", set: " . JsUtils::generateFunction($set, ["v"], false) . " }");
		$this->computeds["computeds"][$name] = $vc;
	}

	public function addComponent(VueJSComponent $component): void {
		$this->components['components'][$component->getName()] = JsUtils::removeQuotes($component->getVarName());
	}

	public function addWatcher(string $var, string $body, array $params = []): void {
		$var = JsUtils::removeQuotes($var);
		$this->watchers["watch"][$var] = JsUtils::generateFunction($body, $params);
	}

	public function addMixin(VueJSComponent $mixin): void {
		$varName = JsUtils::removeQuotes($mixin->getVarName());
		$this->mixins["mixins"][] = $varName;
	}

	public function addFilter(string $name, string $body, array $params = []): void {
		$name = JsUtils::removeQuotes($name);
		$this->filters["filters"][$name] = JsUtils::generateFunction($body, $params);
	}

	public function addDirective(string $name, array $hookFunction, array $configuration = []): void {
		$name = JsUtils::removeQuotes($name);
		foreach ($configuration as $key => $value) {
			$key = JsUtils::removeQuotes($key);
			$this->directives["directives"][$name][$key] = $value;
		}
		foreach ($hookFunction as $key => $value) {
			$key = JsUtils::removeQuotes($key);
			$this->directives["directives"][$name][$key] = JsUtils::generateFunction($value, ['el', 'binding', 'vnode', 'oldVnode']);
		}
	}
}