<?php
include_once 'tests/acceptance/BaseAcceptance.php';

if (! class_exists('\\VueJSCest')) {

	class VueJSCest extends BaseAcceptance {

		public function _before(AcceptanceTester $I) {}

		// tests
		public function tryToHelloMessage(AcceptanceTester $I) {
			$I->amOnPage('/HelloMessage');
			$I->wait(100);
			$I->canSee('Salut Thierry', 'body');
		}
	}
}