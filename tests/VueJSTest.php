<?php

namespace test;

class VueJSTest extends \Codeception\Test\Unit
{

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * Tests TemplateParser->parse()
     */
    public function testParse() {
        $this->templateParser->loadTemplatefile('renderComponent');
        
        $res = $this->templateParser->parse([
            'selector' => 'id',
            'component' => 'myCompo'
        ]);
        $this->assertEquals("const domContainer = document.querySelector('id');
ReactDOM.render(e(myCompo), domContainer);", $res);
    }
}