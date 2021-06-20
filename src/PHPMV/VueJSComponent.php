<?php

namespace PHPMV;

use PHPMV\exceptions\TemplateFileNotFoundException;
use PHPMV\js\JavascriptUtils;

class VueJSComponent extends AbstractVueJS {
	protected string $name;
	protected array $props;
	protected array $template;
	protected array $extends;
	protected string $varName;
	protected string $type;
	protected bool $global;

	public function __construct(string $name, string $varName = null, string $type='component') {
		parent::__construct([]);
		$this->name = $name;
		$this->props = [];
		$this->extends = [];
		$this->template = [];
		$this->varName = $varName??JavascriptUtils::kebabToPascal($name);
		$this->type=$type;
		$this->global=false;
	}

	public function addData(string $name, $value): void {
		$this->data['data'][$name] = $value;
	}

	public function addDataRaw(string $name, string $value): void {
		$this->data['data'][$name] = JavascriptUtils::removeQuotes($value);
	}

	public function extends(VueJSComponent $component): void {
		$this->extends['extends'] = $component->getVarName();
	}

	public function generateObject(): string {
		$data = [];
		if (isset($this->data['data'])) {
			$data['data'] = JavascriptUtils::generateFunction("return " . JavascriptUtils::arrayToJsObject($this->data["data"]));
		}
		return JavascriptUtils::arrayToJsObject($this->components + $this->filters + $this->extends + $this->mixins + $this->configuration + $this->props + $data + $this->computeds + $this->watchers + $this->hooks + $this->methods + $this->template);
	}

	public function generateGlobalScript(): string {
		return "Vue.{$this->type}(\"{$this->name}\",{$this->generateObject()});";

	}

	public function generateFile(bool $inVariable = false, bool $global = false): void {
		$script = ($inVariable) ? JavascriptUtils::declareVariable("const", $this->varName, $this->generateGlobalScript(), false) : $this->generateGlobalScript();
		if (!$global) {
			\file_put_contents("$this->name.js", $script);
		} elseif (\file_exists("components.js")) {
			\file_put_contents("components.js", \PHP_EOL . $script, \FILE_APPEND);
		} else {
			\file_put_contents("components.js", $script);
		}
	}

	public function addProps(string ...$props): void {
		$this->props['props'] = $props;
	}

	public function setInheritAttrs(bool $inheritAttrs): void {
		$this->configuration['inheritAttrs'] = $inheritAttrs;
	}

	public function setModel(string $prop, string $event): void {
		$this->configuration['model'] = JavascriptUtils::removeQuotes("{ prop: '$prop', event: '$event ' }");
	}

	public function addTemplate(string $template): void {
		$this->template['template'] = $template;
	}

	public function addTemplateFile(string $templateFileName): void{
		if(\file_exists($templateFileName)){
			$this->template['template']=\file_get_contents($templateFileName);
		}else{
			throw new TemplateFileNotFoundException("The file $templateFileName does not exist!");
		}
	}

	public function onActivated(string $body): void {
		$this->addHook('activated', $body);
	}

	public function onDeactivated(string $body): void {
		$this->addHook('deactivated', $body);
	}

	public function getName(): string {
		return $this->name;
	}

	public function getVarName(): string {
		return $this->varName;
	}

	/**
	 * @param string $type
	 * @param bool $global
	 */
	public function setTypeAndGlobal(string $type='component',bool $global=true): void {
		$this->type = $type;
		$this->global=$global;
	}

	/**
	 * @param bool $global
	 */
	public function setGlobal(bool $global=true): void {
		$this->global = $global;
	}

	public function __toString(): string {
		if($this->global){
			return $this->generateGlobalScript();
		}
		return JavascriptUtils::declareVariable('const', $this->varName, $this->generateObject(), false);
	}

}