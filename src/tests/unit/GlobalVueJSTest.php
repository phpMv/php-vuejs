<?php

use Codeception\Test\Unit;
use PHPMV\VueManager;
use PHPMV\GlobalVueJS;
use function PHPUnit\Framework\assertEquals;

if (! class_exists('\\GlobalVueJS')) {
    class GlobalVueJSTest extends Unit{
        private ?VueManager $vueManager;

        protected function _before(){
            $this->vueManager = VueManager::getInstance();
        }

        protected function _after(){
            VueManager::deleteInstance();
        }

        public function testAddGlobalObservable(){
            GlobalVueJS::addGlobalObservable("state",["count"=>0]);
            GlobalVueJS::addGlobalDirective('focus',['inserted'=>'el.focus();']);
            GlobalVueJS::addGlobalFilter('capitalize',"if(!value) return '';value = value.toString();return value.charAt(0).toUpperCase() + value.slice(1);",["value"]);
            $observable=["const state = Vue.observable({count: 0});\n"];
            $directive=["Vue.directive('focus',{inserted: function(el,binding,vnode,oldVnode){el.focus();}});\n"];
            $filter=["Vue.filter('capitalize',function(value){if(!value) return '';value = value.toString();return value.charAt(0).toUpperCase() + value.slice(1);});\n"];

            //$this->assertEquals($script,$this->global->getGlobal());
        }
    }
}