<?php 

require "vendor/autoload.php";
require "src/PHPMV/VueJS.php";

?>
<!doctype html>
<html lang="fr" style="overflow-y:hidden;">
<head>
  	<meta charset="utf-8">
  	<title>Test</title>
  	<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
  	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
</head>
<body>
<div id="app" style="width:80%;margin:50px auto;">
    <p>ok</p>
    <input v-focus>
</div>
<?php
$test = new PHPMV\VueJS();
\PHPMV\AbstractVueJS::addGlobalObservable("state",["count"=>0]);
$test->addComputed("testComputed","console.log('testComputed')");
$test->addComputed("testComputedSet","console.log('testComputed')","var data=v");
$test->addDirective('focus',['inserted'=>'el.focus();']);
var_dump($test->getDirectives());
print $test;
?>
</body>
</html>