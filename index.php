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
	<script src="test.js"></script>
  	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
</head>
<body>
<div id="app" style="width:80%;margin:50px auto;">
<test hello="Salut thierry"></test>
</div>
<?php
echo $vue;
?>
</body>
</html>