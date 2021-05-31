<?php

use Codeception\Test\Unit;
use function PHPUnit\Framework\assertEquals;
use PHPMV\VueJS;
use PHPMV\AbstractVueJS;

if (! class_exists('\\AbstractVueJS')) {
    class AbstractVueJSTest extends Unit {
        private ?VueJS $vue;

        protected function _before(){
            $this->vue=new VueJS();
        }

        protected function _after(){
            $this->vue=null;
        }

        public function testAddData(){
            $this->vue->addData("testData",[0,1,2]);
            $script=["data"=>["!!%testData%!!"=>[0,1,2]]];
            $this->assertEquals($script,$this->vue->getData());
        }

        public function testAddDataRaw(){
            $this->vue->addDataRaw("testData","''");
            $script=["data"=>["!!%testData%!!"=>"!!%''%!!"]];
            $this->assertEquals($script,$this->vue->getData());
        }

        public function testAddMethod(){
            $this->vue->addMethod("testMethod","console.log('testMethod')");
            $script=["methods"=>["!!%testMethod%!!"=>"!!%function(){console.log('testMethod')}%!!"]];
            $this->assertEquals($script,$this->vue->getMethods());
        }

        public function testAddComputed(){
            $this->vue->addComputed("testComputed","console.log('testComputed')");
            $this->vue->addComputed("testComputedSet","console.log('testComputed')","var data=v");
            $script=["computeds"=>["!!%testComputed%!!"=>"!!%function(){console.log('testComputed')}%!!","!!%testComputedSet%!!"=>"!!%{ get: function(){console.log('testComputed')}, set: function(v){var data=v} }%!!"]];
            $this->assertEquals($script,$this->vue->getComputeds());
        }

        public function testAddDirective(){
            $this->vue->addDirective('focus',['inserted'=>'el.focus();']);
            $script=["directives"=>["!!%focus%!!"=>["!!%inserted%!!"=>"!!%function(el,binding,vnode,oldVnode){el.focus();}%!!"]]];
            $this->assertEquals($script,$this->vue->getDirectives());
        }

        public function testAddFilter(){
            $this->vue->addFilter('capitalize',"if(!value) return '';value = value.toString();return value.charAt(0).toUpperCase() + value.slice(1);",["value"]);
            $script=["filters"=>["!!%capitalize%!!"=>"!!%function(value){if(!value) return '';value = value.toString();return value.charAt(0).toUpperCase() + value.slice(1);}%!!"]];
            $this->assertEquals($script,$this->vue->getFilters());
        }

        public function testAddWatcher(){
            $this->vue->addWatcher("name","if(this.name==='MyName'){console.log('watcher succeed')}");
            $script = ["watch"=>["!!%name%!!"=>"!!%function(){if(this.name==='MyName'){console.log('watcher succeed')}}%!!"]];
            $this->assertEquals($script,$this->vue->getWatchers());
        }

        public function testAddHook(){
            $this->vue->onBeforeMount("alert('The page is created')");
            $this->vue->onMounted("alert('The page is created')");
            $this->vue->onBeforeCreate("alert('The page is created')");
            $this->vue->onCreated("alert('The page is created')");
            $this->vue->onBeforeUpdate("alert('The page is created')");
            $this->vue->onUpdated("alert('The page is created')");
            $this->vue->onUpdatedNextTick("alert('The page is created')");
            $this->vue->onBeforeDestroy("alert('The page is created')");
            $this->vue->onDestroyed("alert('The page is created')");
            $script=[
                "beforeMount"=>"function(){alert('The page is created')}",
                "mounted"=>"function(){alert('The page is created')}",
                "beforeCreate"=>"function(){alert('The page is created')}",
                "created"=>"function(){alert('The page is created')}",
                "beforeUpdate"=>"function(){alert('The page is created')}",
                "updated"=>"function(){this.\$nextTick(function () {alert('The page is created')})}",
                "beforeDestroy"=>"function(){alert('The page is created')}",
                "destroyed"=>"function(){alert('The page is created')}"
            ];
            $this->assertEquals($script,$this->vue->getHooks());
        }
    }
}