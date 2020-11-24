<?php
namespace test;

use PHPMV\VueJS;

class VueJSTest extends \Codeception\Test\Unit{
    private $vue;
    
    protected function assertEqualsIgnoreNewLines($expected, $actual){
        $this->assertEquals(str_replace("'",'"',preg_replace('/\s+/', '',trim(preg_replace('/\R+/', '', $expected)))),str_replace("'",'"',preg_replace('/\s+/', '',trim(preg_replace('/\R+/', '', $actual)))));
    }
    
    protected function _before(){
        $this->vue=new VueJS("v-app",true,false);
    }

    protected function _after(){
        $this->vue=null;
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
        $script=["computeds"=>["testComputed"=>"!!%function(){console.log('testComputed')}%!!","testComputedSet"=>"!!%function(){console.log('testComputed')}, set: function(v){var data=v}%!!"]];
        $this->assertEquals($script,$this->vue->getComputeds());
    }
    
    public function testAddHook(){
        $this->vue->onMounted("alert('The page is created')");
        $script=["mounted"=>"%!!function(){ alert('The page is created') }!!%"];
        $this->assertEquals($script,$this->vue->getHooks());
    }
   
    public function testVueJSToString() {
        $this->vue->addDataRaw("email","''");
        $this->vue->addData("select",null);
        $this->vue->addMethod("validate","this.\$refs.form.validate()");
        $this->vue->addWatcher("name","if(this.name=='MyName'){console.log('watcher succeed')}");
        $this->vue->addComputed("testComputed","console.log('ok')");
        $this->vue->onMounted("alert('The page is created');");
        $script='<script>const app=new Vue({el: "v-app",vuetify: new Vuetify(),data:{"email": "","select": null},
        methods: {"validate": function(){this.$refs.form.validate()}},
        watch: {"name": function(){if(this.name=="MyName"){console.log("watcher succeed")}}},
        computeds: {"testComputed": function(){console.log("ok")}},
        mounted:  function(){ alert("The page is created"); } })</script>';
        $this->assertEqualsIgnoreNewLines($script,$this->vue->__toString());
    }
}