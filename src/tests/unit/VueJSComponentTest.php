<?php

use Codeception\Test\Unit;
use PHPMV\VueJSComponent;
use function PHPUnit\Framework\assertEquals;

if (! class_exists('\\VueJS')) {
    class VueJSComponentTest extends Unit{
        private ?VueJSComponent $component;

        protected function assertEqualsIgnoreNewLines($expected, $actual){
            $this->assertEquals(\str_replace("'",'"',\preg_replace('/\s+/', '',\trim(\preg_replace('/\R+/', '', $expected)))),\str_replace("'",'"',\preg_replace('/\s+/', '',\trim(\preg_replace('/\R+/', '', $actual)))));
        }

        protected function _before(){
            $template="<form method='post'>
            <input type='text' placeholder='test'/>
            <input type='submit' value='Send'/>
            </form>";
            file_put_contents("test.html",$template);
            $this->component=new VueJSComponent('test');
        }

        protected function _after(){
            $this->component=null;
        }

        public function testAddHook(){
            $this->testVueJSComponent();
            $this->component->onActivated("console.log('component activated');");
            $this->component->onDeactivated("console.log('component deactivated')");
            $script="Vue.component('test',{props: ['test','test1'],methods: {
            methodTest: function(){console.log('ok')}
            },activated: function(){console.log('component activated');},deactivated: function(){console.log('component deactivated')},
            template: '<form method='post'>  <input type='text' placeholder='test'/>  <input type='submit' value='Send'/>  </form>'})";
            $this->assertEqualsIgnoreNewLines($script,$this->component->create());
        }

        public function testVueJSComponent(){
            $this->component->setProps('test','test1');
            $this->component->addMethod("methodTest","console.log('ok')");
            $script="Vue.component('test',{props: ['test','test1'],methods: {
            methodTest: function(){console.log('ok')}
            },template: '<form method='post'>  <input type='text' placeholder='test'/>  <input type='submit' value='Send'/>  </form>'})";
            $this->assertEqualsIgnoreNewLines($script,$this->component->create());
            $this->assertEqualsIgnoreNewLines($script,$this->component->createGlobal());
        }
    }
}