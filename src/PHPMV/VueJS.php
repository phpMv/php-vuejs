<?php
namespace PHPMV;

use PHPMV\js\JavascriptUtils;


/**
 * PHPMV$VueJS
 * This class is part of php-vuejs
 *
 * @author jc
 * @version 1.0.0
 *
 */
class VueJS extends AbstractVueJS{
	protected $app="#app"; //this is the default identifier the vue wrapper
	
	/**
	 * @return string
	 */
	public function getApp():string{
		return $this->app;
	}
	
	/**
	 * @return string
	 */
	public function __toString():string{
		return "
		<script>
		const app = new Vue({
			el:'".$this->getApp()."',
			vuetify: new Vuetify(),
				".$this->data."
                ".$this->methods."
		});
		</script>";
	}	
}

$vue=new VueJS();
$vue->addData("testData","Ceci est une donnee"); //erreur si un accent est présent
$vue->addMethod("test","let self=this;this.testData='moi'");
$vue->addMethod("testParam","this.testData=i","i");