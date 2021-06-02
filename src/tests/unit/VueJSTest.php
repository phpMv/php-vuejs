<?php

use Codeception\Test\Unit;
use PHPMV\VueJS;
use function PHPUnit\Framework\assertEquals;
use PHPMV\core\VueLibrary;

if (!class_exists('\\VueJS')) {

	class VueJSTest extends Unit {
		private ?VueJS $vue;

		protected function assertEqualsIgnoreNewLines($expected, $actual) {
			$this->assertEquals(\str_replace("'", '"', \preg_replace('/\s+/', '', \trim(\preg_replace('/\R+/', '', $expected)))), \str_replace("'", '"', \preg_replace('/\s+/', '', \trim(\preg_replace('/\R+/', '', $actual)))));
		}

		protected function _before() {
			$this->vue = new VueJS();
		}

		protected function _after() {
			$this->vue = null;
		}

		public function testVueLibrary() {
			$library = new VueLibrary();
			$library = explode('\\', $library->getTemplateFolder());
			$this->assertEquals('/home/scrutinizer/build/src/PHPMV/core/templates/rev1', end($library));
		}

		public function testVueJSToString() {
			$this->vue->addDataRaw("email", "''");
			$this->vue->addData("select", null);
			$this->vue->addMethod("validate", "this.\$refs.form.validate()");
			$this->vue->addWatcher("name", "if(this.name==='MyName'){console.log('watcher succeed')}");
			$this->vue->addComputed("testComputed", "console.log('ok')");
			$this->vue->onMounted("alert('The page is created');");
			$script = "const app = new Vue({el: '#app',data: {email: '',select: null},computeds: {testComputed: function(){console.log('ok')}},watch: {name: function(){if(this.name==='MyName'){console.log('watcher succeed')}}},mounted: function(){alert('The page is created');},methods: {validate: function(){this.\$refs.form.validate()}}});";
			$this->assertEqualsIgnoreNewLines($script, $this->vue->__toString());
		}
	}
}