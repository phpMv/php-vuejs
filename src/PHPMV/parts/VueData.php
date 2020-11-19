<?php
namespace PHPMV\parts;

use PHPMV\core\VueLibrary;
use PHPMV\core\TemplateParser;

/**
 * PHPMV$VueJS
 * This class is part of php-vuejs
 *
 * @author qgorak
 * @version 1.0.0
 *
 */
class VueData extends VuePart {

	public function __toString():string{
		$data=parent::__toString();
		if($data!=""){
		    $this->renderTemplate = new TemplateParser(); //load the template file
		    $this->renderTemplate->loadTemplatefile(VueLibrary::getTemplateFolder() . '/data'); //parse the template with some variables
		    $result=$this->renderTemplate->parse(['data'=>$data]);
		    return $result;
		}
		return "";
	}
}