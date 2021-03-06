<?php


namespace PHPMV\fw\ubiquity;


use PHPMV\fw\VueManagerInterface;
use PHPMV\utils\PhpUtils;
use PHPMV\VueJSComponent;
use PHPMV\VueManager;

/**
 * php-vueJS Manager class for Ubiquity framework.
 *
 * PHPMV\jw$UVueManager
 * This class is part of php-vuejs
 *
 * @author jcheron
 * @version 1.0.0
 * @package PHPMV\fw\ubiquity
 *
 */
class UVueManager extends VueManager implements VueManagerInterface {

	public static function start(array &$config): void {
		self::getInstance()->setConfig($config['vuejs'] ?? ['delimiters' => ['${', '}']]);
	}

	public function renderView(string $viewName, $parameters = [], bool $asString = false): ?string {
		if (isset($this->container)) {
			$view = $this->container->getView();
			$parameters['script_foot'] = $this->getInstance()->__toString();
			if (isset($parameters)) {
				$view->setVars($parameters);
			}
			return $view->render($viewName, $asString);
		}
		throw new \Exception(get_class() . " instance is not properly instancied : you omitted the second parameter \$controller!");
	}

	public function renderDefaultView($parameters = [], bool $asString = false): ?string {
		return $this->renderView($this->container->getDefaultViewName(), $parameters, $asString);
	}

	public function addTemplateFileComponent(string $filename, VueJSComponent $component, $parameters = null): void {
		$filename = \rtrim($this->getTemplateComponentDirectory(), '/') . '/' . $filename;
		$view = $this->container->getView();
		if (isset($parameters)) {
			$view->setVars($parameters);
		}
		$content=\str_replace(["\n","\t"],"",$view->render($filename, true));
		$content=\str_replace('"','\"',$content);
		$component->addTemplate($content);
	}

	public function parseFile(string $fileName): void {
		PhpUtils::parseFile(\ROOT.'..'.\DS.'public'.\DS.'assets'.\DS.'js'.\DS.$fileName,'js');
	}
}
