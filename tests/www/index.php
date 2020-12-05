<?php
    define('ROOT', __DIR__ . DIRECTORY_SEPARATOR);
    require ROOT . 'vendor/autoload.php';
?>
<!doctype html>
<html lang="fr">
<head>
  	<meta charset="utf-8">
  	<title>Acceptance Test</title>
  	<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
  	<script src="test.js"></script>
  	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
</head>
<body>
<?php
$inc = $_GET['c'] ?? '';
if ($inc != '') {
	include ROOT . 'include/' . $inc . '.php';
} else {
	echo "Hello VueJS !";
}
?>
<div id="app">
<test hello="Salut Thierry"></test>
</div>
<?php 
echo $vueAcceptance;
?>
</body>
</html>