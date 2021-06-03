<?php

namespace PHPMV;

use PHPMV\js\JavascriptUtils;

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
		$this->hooks[$name] = JavascriptUtils::generateFunction($body);
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
		$name = JavascriptUtils::removeQuotes($name);
		$this->data["data"][$name] = $value;
	}

	public function addDataRaw(string $name, string $value): void {
		$name = JavascriptUtils::removeQuotes($name);
		$this->data["data"][$name] = JavascriptUtils::removeQuotes($value);
	}

	public function addMethod(string $name, string $body, array $params = []): void {
		$name = JavascriptUtils::removeQuotes($name);
		$this->methods["methods"][$name] = JavascriptUtils::generateFunction($body, $params);
	}

	public function addComputed(string $name, string $get, string $set = null): void {
		$name = JavascriptUtils::removeQuotes($name);
		$vc = (is_null($set)) ? JavascriptUtils::generateFunction($get) : JavascriptUtils::removeQuotes("{ get: " . JavascriptUtils::generateFunction($get, [], false) . ", set: " . JavascriptUtils::generateFunction($set, ["v"], false) . " }");
		$this->computeds["computeds"][$name] = $vc;
	}

	public function addComponent(VueJSComponent $component): void {
		$this->components['components'][$component->getName()] = JavascriptUtils::removeQuotes($component->getVarName());
	}

	public function addWatcher(string $var, string $body, array $params = []): void {
		$var = JavascriptUtils::removeQuotes($var);
		$this->watchers["watch"][$var] = JavascriptUtils::generateFunction($body, $params);
	}

	public function addMixin(VueJSComponent $mixin): void {
		$varName = JavascriptUtils::removeQuotes($mixin->getVarName());
		$this->mixins["mixins"][] = $varName;
	}

	public function addFilter(string $name, string $body, array $params = []): void {
		$name = JavascriptUtils::removeQuotes($name);
		$this->filters["filters"][$name] = JavascriptUtils::generateFunction($body, $params);
	}

	public function addDirective(string $name, array $hookFunction): void {
		$name = JavascriptUtils::removeQuotes($name);
		foreach ($hookFunction as $key => $value) {
			$key = JavascriptUtils::removeQuotes($key);
			$this->directives["directives"][$name][$key] = JavascriptUtils::generateFunction($value, ['el', 'binding', 'vnode', 'oldVnode']);
		}
	}
}