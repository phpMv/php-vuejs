<?php

use Codeception\Test\Unit;
use PHPMV\VueJSComponent;
use function PHPUnit\Framework\assertEquals;

if (! class_exists('\\VueJS')) {
    class VueJSComponentTest extends Unit{
        private ?VueJSComponent $component;

        protected function assertEqualsIgnoreNewLines($expected, $actual):void {
            $this->assertEquals(\str_replace("'",'"',\preg_replace('/\s+/', '',\trim(\preg_replace('/\R+/', '', $expected)))),\str_replace("'",'"',\preg_replace('/\s+/', '',\trim(\preg_replace('/\R+/', '', $actual)))));
        }

        protected function _before():void {
            $template="<form method='post'>
            <input type='text' placeholder='test'/>
            <input type='submit' value='Send'/>
            </form>";
            file_put_contents("test.html", $template);
            $this->component = new VueJSComponent('test', ['test','test1']);
        }

        protected function _after():void {
            $this->component = null;
        }

        public function testVueJSComponent():void {
            $this->component->onActivated("console.log('component activated');");
            $this->component->onDeactivated("console.log('component deactivated')");
            $scriptLocal = "{props: ['test','test1'],activated: function(){console.log('component activated');},deactivated: function(){console.log('component deactivated')},template: '<form method='post'><input type='text' placeholder='test'/><input type='submit' value='Send'/></form>'}";
            $scriptGlobal = "Vue.component('test',{props: ['test','test1'],activated: function(){console.log('component activated');},deactivated: function(){console.log('component deactivated')},template: '<form method='post'><input type='text' placeholder='test'/><input type='submit' value='Send'/></form>'});";
            $this->assertEqualsIgnoreNewLines($scriptLocal, $this->component->generateLocalScript());
            $this->assertEqualsIgnoreNewLines($scriptGlobal, $this->component->generateGlobalScript());
        }

        public function testGetName():void {
            $script = "test";
            $this->assertEquals($script, $this->component->getName());
        }
    }
}