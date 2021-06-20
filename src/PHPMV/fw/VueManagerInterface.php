<?php


namespace PHPMV\fw;


use PHPMV\VueJSComponent;

/**
 * php-vueJS Manager interface for frameworks.
 *
 * PHPMV\fw$VueManagerInterface
 * This class is part of php-vuejs
 *
 * @author jcheron
 * @version 1.0.0
 * @package PHPMV\fw
 */
interface VueManagerInterface {
	/**
	 * Loads a template file and assigns its content to the component.
	 *
	 * @param string $filename
	 * @param VueJSComponent $component
	 * @param mixed|null $parameters
	 */
	public function addTemplateFileComponent(string $filename, VueJSComponent $component, $parameters = null): void;

	/**
	 * Performs a Javascript compilation and displays a view
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
	public function renderView(string $viewName, $parameters = [], bool $asString = false): ?string;

	/**
	 * Performs a javascript compilation and displays the default view
	 *
	 * @param mixed $parameters
	 *            Variable or associative array to pass to the view <br> If a variable is passed, it will have the name <b> $ data </ b> in the view, <br>
	 *            If an associative array is passed, the view retrieves variables from the table's key names
	 * @param boolean $asString
	 *            If true, the view is not displayed but returned as a string (usable in a variable)
	 * @return string|null
	 * @throws \Exception
	 */
	public function renderDefaultView($parameters = [], bool $asString = false): ?string;
}
