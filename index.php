<?php 

require "vendor/autoload.php";
require "src/PHPMV/VueJS.php";

?>
<!doctype html>
<html lang="fr">
<head>
  	<meta charset="utf-8">
  	<title>Test</title>
  	<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
  	<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
  	<link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
  	<link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
  	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
</head>
<body>
<div id="app">
<v-app>
<template>
  <v-btn 
  v-on:click="testMethod"
  block>
    {{testData}}
  </v-btn>
</template>
<v-app>
</div>
<?php echo $vue; ?>
</body>
</html>