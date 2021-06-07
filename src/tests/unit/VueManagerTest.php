<?php

use Codeception\Test\Unit;
use PHPMV\VueJS;
use function PHPUnit\Framework\assertEquals;
use PHPMV\VueManager;
use PHPMV\VueJSComponent;
use PHPMV\core\VueLibrary;

if (!class_exists('\\VueManager')) {

	class VueManagerTest extends Unit {
		private ?VueManager $vueManager;

		protected function assertEqualsIgnoreNewLines($expected, $actual) {
			$this->assertEquals(\str_replace("'", '"', \preg_replace('/\s+/', '', \trim(\preg_replace('/\R+/', '', $expected)))), \str_replace("'", '"', \preg_replace('/\s+/', '', \trim(\preg_replace('/\R+/', '', $actual)))));
		}

		protected function _before() {
			$this->vueManager = VueManager::getInstance();
		}

		protected function _after() {
			VueManager::deleteInstance();
		}

		public function testVueLibrary() {
			$library = new VueLibrary();
			$library = explode('\\', $library->getTemplateFolder());
			$this->assertEquals('/home/scrutinizer/build/src/PHPMV/core/templates/rev1', end($library));
		}

		public function testInjectedScript() {
			$template = '<p>{{ message }}</p>';

			$component = new VueJSComponent('component-one');
			$component->generateFile();
			$component->addProps('first', 'second');
			$component->setInheritAttrs(true);
			$component->setModel('checked', 'change');
			$component->onActivated('console.log("ok");');
			$component->onDeactivated('console.log("ok");');

			$componentOne = new VueJSComponent('component-two');
			$componentOne->extends($component);
			$componentOne->addData('message', 'Hello World !');
			$componentOne->addDataRaw('raw', "'Hello World !'");
			$componentOne->addTemplate($template);

			$mixinOne = new VueJSComponent('mixin-one');
			$mixinOne->addTemplate($template);

			$extendOne = new VueJSComponent('extend-one', 'ExtendOne');
			$extendOne->addMixin($mixinOne);

			$vue = new VueJS(['el' => '#app'], 'app', true);
			$vue->addData('message', "Hello World !");
			$vue->addDataRaw('raw', "'Hello World !'");
			$vue->addMethod('alertUser', "alert('Welcome ' + user);", ['user']);
			$vue->addDirective('focus', ['inserted' => 'el.focus();']);
			$vue->addWatcher(
				"title",
				"console.log('Title change from '+ oldTitle +' to '+ newTitle)",
				['newTitle', 'oldTitle']);
			$vue->addComponent($componentOne);
			$vue->addComputed(
				'fullName',
				"return this.firstName+' '+this.lastName",
				"this.firstName=v[0];this.lastName=v[1]");
			$vue->addFilter('capitalize', ''
				. 'if(!value) return'
				. 'value = value.toString();'
				. 'return value.charAt(0).toUpperCase() + value.slice(1);', ["value"]);
			$vue->onMounted('console.log("hook");');
			$vue->onBeforeMount('console.log("hook");');
			$vue->onBeforeCreate('console.log("hook");');
			$vue->onCreated('console.log("hook");');
			$vue->onBeforeDestroy('console.log("hook");');
			$vue->onBeforeUpdate('console.log("hook");');
			$vue->onDestroyed('console.log("hook");');
			$vue->onUpdated('console.log("hook");');
			$vue->onUpdatedNextTick('console.log("hook");');

			$script = "<script> Vue.prototype.\$http = axios; const state = Vue.observable({count: 0}); Vue.directive('focus',{inserted: function(el,binding,vnode,oldVnode){el.focus();}}); Vue.filter('capitalize',function(value){if(!value) return '';value = value.toString();return value.charAt(0).toUpperCase() + value.slice(1);}); const MixinOne = {template: '<p>{{ message }}</p>'}; Vue.component('component-one',{inheritAttrs: true,model: { prop: 'checked', event: 'change ' },props: [ 'first', 'second' ],activated: function(){console.log('ok');},deactivated: function(){console.log('ok');}}); Vue.mixin({template: '<p>{{ message }}</p>'}); Vue.extend({mixins: [ MixinOne ]}); const ComponentOne = {inheritAttrs: true,model: { prop: 'checked', event: 'change ' },props: [ 'first', 'second' ],activated: function(){console.log('ok');},deactivated: function(){console.log('ok');}}; const ComponentTwo = {extends: 'ComponentOne',data: function(){return {message: 'Hello World !',raw: 'Hello World !'}},template: '<p>{{ message }}</p>'}; const ExtendOne = {mixins: [ MixinOne ]}; const app = new Vue({el: '#app',vuetify: new Vuetify(),components: { 'component-two': ComponentTwo },directives: { focus: { inserted: function(el,binding,vnode,oldVnode){el.focus();} } },filters: { capitalize: function(value){if(!value) returnvalue = value.toString();return value.charAt(0).toUpperCase() + value.slice(1);} },data: { message: 'Hello World !', raw: 'Hello World !' },computeds: { fullName: { get: function(){return this.firstName+' '+this.lastName}, set: function(v){this.firstName=v[0];this.lastName=v[1]} } },watch: { title: function(newTitle,oldTitle){console.log('Title change from '+ oldTitle +' to '+ newTitle)} },mounted: function(){console.log('hook');},beforeMount: function(){console.log('hook');},beforeCreate: function(){console.log('hook');},created: function(){console.log('hook');},beforeDestroy: function(){console.log('hook');},beforeUpdate: function(){console.log('hook');},destroyed: function(){console.log('hook');},updated: function(){this.\$nextTick(function () { console.log('hook'); })},methods: { alertUser: function(user){alert('Welcome ' + user);} }}); </script>";
			$this->assertEqualsIgnoreNewLines($script, $this->vueManager->__toString());
		}
	}
}