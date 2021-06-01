<?php

use Codeception\Test\Unit;
use PHPMV\VueManager;
use PHPMV\VueJSGlobal;
use function PHPUnit\Framework\assertEquals;

if (! class_exists('\\VueJSGlobal')) {
    class GlobalVueJSTest extends Unit{
        private ?VueManager $vueManager;

        protected function _before(){
            $this->vueManager = VueManager::getInstance();
        }

        protected function _after(){
            VueManager::deleteInstance();
        }

        public function testAddGlobalObservable(){
            VueJSGlobal::addGlobalObservable("state",["count"=>0]);
            VueJSGlobal::addGlobalDirective('focus',['inserted'=>'el.focus();']);
            VueJSGlobal::addGlobalFilter('capitalize',"if(!value) return '';value = value.toString();return value.charAt(0).toUpperCase() + value.slice(1);",["value"]);
            $observable=["const state = Vue.observable({count: 0});\n"];
            $directive=["Vue.directive('focus',{inserted: function(el,binding,vnode,oldVnode){el.focus();}});\n"];
            $filter=["Vue.filter('capitalize',function(value){if(!value) return '';value = value.toString();return value.charAt(0).toUpperCase() + value.slice(1);});\n"];

            //$this->assertEquals($script,$this->global->getGlobal());
        }
    }
}