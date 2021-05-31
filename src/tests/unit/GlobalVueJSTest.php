<?php

use Codeception\Test\Unit;
use PHPMV\GlobalVueJS;
use function PHPUnit\Framework\assertEquals;

if (! class_exists('\\GlobalVueJS')) {
    class GlobalVueJSTest extends Unit{
        private ?GlobalVueJS $global;

        protected function _before(){
            $this->global = GlobalVueJS::getInstance();
        }

        protected function _after(){
            GlobalVueJS::deleteInstance();
        }

        public function testAddGlobalObservable(){
            $this->global->addGlobalObservable("state",["count"=>0]);
            $script=["const state = Vue.observable({count: 0});\n"];
            $this->assertEquals($script,$this->global->getGlobal());
        }

        public function testAddGlobalFilter(){
            $this->global->addGlobalFilter('capitalize',"if(!value) return '';value = value.toString();return value.charAt(0).toUpperCase() + value.slice(1);",["value"]);
            $script=["Vue.filter('capitalize',function(value){if(!value) return '';value = value.toString();return value.charAt(0).toUpperCase() + value.slice(1);});\n"];
            $this->assertEquals($script,$this->global->getGlobal());
        }

        public function testAddGlobalDirective(){
            $this->global->addGlobalDirective('focus',['inserted'=>'el.focus();']);
            $script=["Vue.directive('focus',{inserted: function(el,binding,vnode,oldVnode){el.focus();}});\n"];
            $this->assertEquals($script,$this->global->getGlobal());
        }
    }
}