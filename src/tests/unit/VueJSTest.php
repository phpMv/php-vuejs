<?php
use PHPMV\VueJS;
use PHPMV\VueJSComponent;
use function PHPUnit\Framework\assertEquals;
use PHPMV\core\VueLibrary;
use PHPMV\AbstractVueJS;

if (! class_exists('\\VueJS')) {

    class VueJSTest extends \Codeception\Test\Unit{
        private $vue;
        private $component;
        
        protected function assertEqualsIgnoreNewLines($expected, $actual){
            $this->assertEquals(\str_replace("'",'"',\preg_replace('/\s+/', '',\trim(\preg_replace('/\R+/', '', $expected)))),\str_replace("'",'"',\preg_replace('/\s+/', '',\trim(\preg_replace('/\R+/', '', $actual)))));
        }
        
        protected function _before(){
            $this->vue=new VueJS('v-app',true,false);
        }

        protected function _after(){
            $this->vue=null;
            PHPMV\AbstractVueJS::$global=[];
        }
        
        public function testVueLibrary(){
            $library=new VueLibrary();
            $library=explode('\\',$library->getTemplateFolder());
            $this->assertEquals('/home/scrutinizer/build/src/PHPMV/core/templates/rev1',end($library));
        }
        
        public function testAddData(){
            $this->vue->addData("testData",[0,1,2]);
            $script=["data"=>["testData"=>[0,1,2]]];
            $this->assertEquals($script,$this->vue->getData());
        }
        
        public function testAddDataRaw(){
            $this->vue->addDataRaw("testData","''");
            $script=["data"=>["testData"=>"!!%''%!!"]];
            $this->assertEquals($script,$this->vue->getData());
        }
        
        public function testAddMethod(){
            $this->vue->addMethod("testMethod","console.log('testMethod')");
            $script=["methods"=>["testMethod"=>"!!%function(){console.log('testMethod')}%!!"]];
            $this->assertEquals($script,$this->vue->getMethods());
        }
        
        public function testAddComputed(){
            $this->vue->addComputed("testComputed","console.log('testComputed')");
            $this->vue->addComputed("testComputedSet","console.log('testComputed')","var data=v");
            $script=["computeds"=>["!!%testComputed%!!"=>"!!%function(){console.log('testComputed')}%!!","!!%testComputedSet%!!"=>"!!%{ get: !!%function(){console.log('testComputed')}%!!, set: !!%function(v){var data=v}%!! }%!!"]];
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
                "beforeMount"=>"!!%function(){alert('The page is created')}%!!",
                "mounted"=>"!!%function(){alert('The page is created')}%!!",
                "beforeCreate"=>"!!%function(){alert('The page is created')}%!!",
                "created"=>"!!%function(){alert('The page is created')}%!!",
                "beforeUpdate"=>"!!%function(){alert('The page is created')}%!!",
                "updated"=>"!!%function(){this.\$nextTick(function () {alert('The page is created')})}%!!",
                "beforeDestroy"=>"!!%function(){alert('The page is created')}%!!",
                "destroyed"=>"!!%function(){alert('The page is created')}%!!"
            ];
            $this->assertEquals($script,$this->vue->getHooks());
        }

        public function testAddGlobalObservable(){
            \PHPMV\AbstractVueJS::addGlobalObservable("state",["count"=>0]);
            $script=["!!%const state = Vue.observable({count: 0});%!!"];
            $this->assertEquals($script,\PHPMV\AbstractVueJS::$global);
        }

        public function testAddGlobalFilter(){
            \PHPMV\AbstractVueJS::addGlobalFilter('capitalize',"if(!value) return '';value = value.toString();return value.charAt(0).toUpperCase() + value.slice(1);",["value"]);
            $script=["!!%Vue.filter('capitalize',!!%function(value){if(!value) return '';value = value.toString();return value.charAt(0).toUpperCase() + value.slice(1);}%!!);%!!"];
            $this->assertEquals($script,\PHPMV\AbstractVueJS::$global);
        }

        public function testAddGlobalDirective(){
            \PHPMV\AbstractVueJS::addGlobalDirective('focus',['inserted'=>'el.focus();']);
            $script=["!!%Vue.directive('focus',!!%{inserted: !!%function(el,binding,vnode,oldVnode){el.focus();}%!!}%!!);%!!"];
            $this->assertEquals($script,\PHPMV\AbstractVueJS::$global);
        }

        public function testVueJSGetters(){
            $newVue=new VueJS();
            $newVue->setUseAxios(true);
            $newVue->setConfiguration(["el"=>'"v-app"',"vuetify"=>"new Vuetify()"]);
            $this->assertEquals(["el"=>'"v-app"',"vuetify"=>"new Vuetify()"],$newVue->getConfiguration());
            $this->assertEquals(true,$newVue->getUseAxios());
        }
        
        public function testVueJSToString() {
            $this->vue->setUseAxios(true);
            $this->vue->addDataRaw("email","''");
            $this->vue->addData("select",null);
            $this->vue->addMethod("validate","this.\$refs.form.validate()");
            $this->vue->addWatcher("name","if(this.name=='MyName'){console.log('watcher succeed')}");
            $this->vue->addComputed("testComputed","console.log('ok')");
            $this->vue->onMounted("alert('The page is created');");
            $script='<script>Vue.prototype.$http=axios;constapp=newVue({el:"v-app",vuetify:newVuetify(),data:{"email":"","select":null},methods:{"validate":function(){this.$refs.form.validate()}},watch:{"name":function(){if(this.name=="MyName"){console.log("watchersucceed")}}},computeds:{testComputed:function(){console.log("ok")}},mounted:function(){alert("Thepageiscreated");}})</script>';
            $this->assertEqualsIgnoreNewLines($script,$this->vue->__toString());
        }
        
        public function testAbstractVueJSGetters(){
            $this->vue->addData("select",null);
            $this->vue->addMethod("validate","this.\$refs.form.validate()");
            $this->vue->addComputed("testComputed","console.log('ok')");
            $this->vue->addWatcher("name","if(this.name=='MyName'){console.log('watcher succeed')}");
            $this->assertEquals(["data"=>["select"=>NULL]],$this->vue->getData());
            $this->assertEquals(["methods"=>["validate"=>"!!%function(){this.\$refs.form.validate()}%!!"]],$this->vue->getMethods());
            $this->assertEquals(["computeds"=>["!!%testComputed%!!"=>"!!%function(){console.log('ok')}%!!"]],$this->vue->getComputeds());
            $this->assertEquals(["watch"=>["name"=>"!!%function(){if(this.name=='MyName'){console.log('watcher succeed')}}%!!"]],$this->vue->getWatchers());     
        }
        
        public function testVueJSComponent(){
        	$template='<form method="post">
            <input type="text" placeholder="test"/>
            <input type="submit" value="Send"/>
            </form>';
        	file_put_contents("test.html",$template);
        	$this->component=new VueJSComponent("test");
        	$this->component->setProps('test','test1');
            $this->component->addMethod("methodTest","console.log('ok')");
            $script="Vue.component('test',{props: ['test','test1'],methods: {
            'methodTest': function(){console.log('ok')}
            },template: '<form method='post'>  <input type='text' placeholder='test'/>  <input type='submit' value='Send'/>  </form>'})";
            $this->assertEqualsIgnoreNewLines($script,$this->component->create());
            $this->assertEqualsIgnoreNewLines($script,$this->component->createGlobal());
            $this->assertEqualsIgnoreNewLines($script,$this->component->createGlobal());
        }
        
        public function testAbstractVueJSSetters(){
            $abstract=new AbstractVueJS();
            $abstract->setData(['test']);
            $abstract->setMethods(['test']);
            $abstract->setComputeds(['test']);
            $abstract->setHooks(['test']);
            $abstract->setWatchers(['test']);
            $abstract->setDirectives(['test']);
            $abstract->setFilters(['test']);
            $this->assertEquals(['test'],$abstract->getData());
            $this->assertEquals(['test'],$abstract->getMethods());
            $this->assertEquals(['test'],$abstract->getComputeds());
            $this->assertEquals(['test'],$abstract->getHooks());
            $this->assertEquals(['test'],$abstract->getWatchers());
            $this->assertEquals(['test'],$abstract->getDirectives());
            $this->assertEquals(['test'],$abstract->getFilters());
        }
    }
}