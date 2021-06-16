<?php


namespace PHPMV\php\ubiquity;


use PHPMV\VueManager;

class UVueManager extends VueManager {
	public static function start(array &$config):UVueManager{
		$instance=self::getInstance();
		$instance->config=$config['vuejs']??['delimiters'=>['${','}']];
	}
	/**
	 * Performs Javascript compilation and displays a view
	 *
	 * @param string $viewName
	 * @param mixed $parameters
	 *            Variable or associative array to pass to the view <br> If a variable is passed, it will have the name <b> $ data </ b> in the view, <br>
	 *            If an associative array is passed, the view retrieves variables from the table's key names
	 * @param boolean $asString
	 *            If true, the view is not displayed but returned as a string (usable in a variable)
	 * @return string|null
	 * @throws \Exception
	 */
	public function renderView($viewName, $parameters = [], $asString = false): ?string {
		if (isset($this->container)) {
			$view = $this->container->getView();
			$parameters['script_foot']=$this->getInstance()->__toString();
			if (isset($parameters))
				$view->setVars($parameters);
			return $view->render($viewName, $asString);
		}
		throw new \Exception(get_class() . " instance is not properly instancied : you omitted the second parameter \$controller!");
	}

	/**
	 * Performs javascript compilation and displays the default view
	 *
	 * @param mixed $parameters
	 *            Variable or associative array to pass to the view <br> If a variable is passed, it will have the name <b> $ data </ b> in the view, <br>
	 *            If an associative array is passed, the view retrieves variables from the table's key names
	 * @param boolean $asString
	 *            If true, the view is not displayed but returned as a string (usable in a variable)
	 * @return string|null
	 * @throws \Exception
	 */
	public function renderDefaultView($parameters = [], $asString = false):?string {
		return $this->renderView($this->container->getDefaultViewName(), $parameters, $asString);
	}

}