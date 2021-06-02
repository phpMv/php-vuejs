<?php

use PHPMV\VueJS;
use PHPMV\VueManager;

$vueAcceptance = new VueJS();
$vueAcceptance->addData('hello', 'Hello World !');
$vueManager = VueManager::getInstance();
$vueManager->addVue($vueAcceptance);