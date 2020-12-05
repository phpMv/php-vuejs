<?php
    define('ROOT', __DIR__ . DIRECTORY_SEPARATOR);
    require ROOT . 'vendor/autoload.php';
?>
<!doctype html>
<html lang="fr" style="overflow-y:hidden;">
<head>
  	<meta charset="utf-8">
  	<title>Acceptance Test</title>
  	<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
  	<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
  	<link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
  	<link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
  	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
</head>
<body>
<?php
$inc = $_GET['c'] ?? '';
if ($inc != '') {
	include ROOT . 'include/' . $inc . '.php';
	{{hello}}
} else {
	echo "Hello VueJS !";
}
?>
<?php echo $vueAcceptance; ?>
</body>
</html>