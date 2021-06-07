<?php

use Codeception\Test\Unit;
use function PHPUnit\Framework\assertEquals;
use PHPMV\utils\PhpUtils;

if (!class_exists('\\PhpUtils')) {
	class PhpUtilsTest extends Unit {

		protected function assertEqualsIgnoreNewLines($expected, $actual) {
			$this->assertEquals(\str_replace("'", '"', \preg_replace('/\s+/', '', \trim(\preg_replace('/\R+/', '', $expected)))), \str_replace("'", '"', \preg_replace('/\s+/', '', \trim(\preg_replace('/\R+/', '', $actual)))));
		}

		public function testImportFromFile(): void {
			$template = '<form method="post">
            <input type="text" placeholder="test"/>
            <input type="submit" value="Send"/>
            </form>';
			file_put_contents("importTest.html", $template);
			$script = '<form method="post"><input type="text" placeholder="test"/><input type="submit" value="Send"/></form>';
			$this->assertEqualsIgnoreNewLines($script, PhpUtils::importFromFile('importTest', 'html'));
		}

		public function testGetParsed() {
			$variable = 'Hello World !';
			$template = "---created---"
						."console.log('{{ variable }}');"
						."alert(this.message);"
						."this.\$set(this, 'snackbar', true);"
						."setTimeout(()=>{"
						."this.\$set(this, 'snackbar', false);"
						."},3000);"
						."---end---";
			file_put_contents("jsToParse.js", $template);
			PhpUtils::parseFile('jsToParse','js');
			$script = "console.log('Hello World !');"
					."alert(this.message);"
					."this.\$set(this, 'snackbar', true);"
					."setTimeout(()=>{"
					."this.\$set(this, 'snackbar', false);"
					."},3000);";
			$script = "console.log('Hello World !');alert(this.message);this.\$set(this, 'snackbar', true);setTimeout(()=>{this.\$set(this, 'snackbar', false);},3000);";
			$this->assertEqualsIgnoreNewLines($script, PhpUtils::getParsedJs('created',['variable' => $variable]));
		}
	}
}