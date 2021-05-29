<?php

use Codeception\Test\Unit;
use PHPMV\VueJS;
use function PHPUnit\Framework\assertEquals;
use PHPMV\core\VueLibrary;

if (! class_exists('\\VueJS')) {

    class VueJSTest extends Unit{
        private ?VueJS $vue;
        
        protected function assertEqualsIgnoreNewLines($expected, $actual){
            $this->assertEquals(\str_replace("'",'"',\preg_replace('/\s+/', '',\trim(\preg_replace('/\R+/', '', $expected)))),\str_replace("'",'"',\preg_replace('/\s+/', '',\trim(\preg_replace('/\R+/', '', $actual)))));
        }
        
        protected function _before(){
            $this->vue=new VueJS('v-app',true,false);
        }

        protected function _after(){
            $this->vue=null;
        }
        
        public function testVueLibrary(){
            $library=new VueLibrary();
            $library=explode('\\',$library->getTemplateFolder());
            $this->assertEquals('/home/scrutinizer/build/src/PHPMV/core/templates/rev1',end($library));
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
            $this->vue->addWatcher("name","if(this.name==='MyName'){console.log('watcher succeed')}");
            $this->vue->addComputed("testComputed","console.log('ok')");
            $this->vue->onMounted("alert('The page is created');");
            $script='<script>Vue.prototype.$http=axios;constapp=newVue({el:"v-app",vuetify:newVuetify(),data:{email:"",select:null},computeds:{testComputed:function(){console.log("ok")}},watch:{"name":function(){if(this.name==="MyName"){console.log("watchersucceed")}}},mounted:function(){alert("Thepageiscreated");},methods:{validate:function(){this.$refs.form.validate()}}})</script>';
            $this->assertEqualsIgnoreNewLines($script,$this->vue->__toString());
        }
    }
}