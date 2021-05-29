# PHP-VueJS
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/phpMv/php-vuejs/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/phpMv/php-vuejs/?branch=main) [![Code Coverage](https://scrutinizer-ci.com/g/phpMv/php-vuejs/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/phpMv/php-vuejs/?branch=main) [![Build Status](https://scrutinizer-ci.com/g/phpMv/php-vuejs/badges/build.png?b=main)](https://scrutinizer-ci.com/g/phpMv/php-vuejs/build-status/main) [![Code Intelligence Status](https://scrutinizer-ci.com/g/phpMv/php-vuejs/badges/code-intelligence.svg?b=main)](https://scrutinizer-ci.com/code-intelligence)  
VueJS integration for PHP frameworks
`PHP-VueJS` adds `VueJS` to any `PHP` projects, it could be native, or even `PHP Framework` projects

# :round_pushpin: Get started
The recommended way to use this library is using `Composer`, run this command from the directory where is located `composer.json`
```console
composer require phpmv/php-vuejs
```

# :closed_book: Guide
This section is mandatory to read before starting, it contains examples for everything you will be using with this library !

## Creation of Vue object

```php
$vue = new VueJs();
```
VueJS can take 3 arguments, they all are optionnal, these are their default values
 1. (string) el = "#app" **[optionnal]**
 2. (boolean) enable [vuetify](https://vuetifyjs.com/en/) = false **[optionnal]**
 3. (boolean) enable [axios](https://vuejs.org/v2/cookbook/using-axios-to-consume-apis.html) = false **[optionnal]**

## Vue Data
These are the two possible methods to add data in your Vue object
```php
$vue->addData('name',true);
$vue->addDataRaw('name','true');
```
These two lines of code generate exactly the same Vue data
```js
data: { "name": true }
```

## Vue Methods
This is the only possible method to add methods in your Vue object
```php
$vue->addMethod('greet','window.alert(`Hello ${name}`);',['name']);
```
addMethod has 2 required arguments, and an optionnal one
 1. (string) method's name
 2. (string) method's body
 3. (array) method's argument(s) **[optionnal]**
 
This will generate the content below
```js
methods: {
	"greet":
		function(name){
			window.alert(`Hello ${name}`);
		}
}
```

## Vue Computeds
This is the only possible method to add computed methods in your Vue object
```php
$vue->addComputed('reversedMessage',"return this.message.split('').reverse().join('');");
```
addComputed has 2 required arguments, and an optionnal one
 1. (string) computed's name
 2. (string) computed's get body
 3. (string) computed's set body by default his argument is v **[optionnal]**

This will generate the content below
```js
computeds: {
	reversedMessage:
		function(){
			return this.message.split('').reverse().join('');
		}
}
```
Here is an example with getter and setter
```php
$vue->addComputed(
	'fullName',
	"return this.firstName+' '+this.lastName",
	"this.firstName=v[0];this.lastName=v[1]");
```

This code generates this content
```js
computeds: {
	fullName: {
		get: function(){
			return this.firstName+' '+this.lastName
		},
		set: function(v){
			this.firstName=v[0];
			this.lastName=v[1]
		}
	}
}
```

## Vue Watchers
This is the only possible method to add watchers in your Vue object

```php
$vue->addWatcher(
	"title",
	"console.log('Title change from '+ oldTitle +' to '+ newTitle)",
	['newTitle','oldTitle']);
```
addWatcher has 2 required arguments, and an optionnal one
 1. (string) data to watch
 2. (string) watcher's function body
 3. (array) watcher's function argument(s) [optionnal]

This generates the content below
```js
watch: {
	"title":
		function(newTitle,oldTitle){
			console.log('Title change from '+ oldTitle +' to '+ newTitle)
		}
}
```

## Vue Directives
This is the only possible method to add directives in your Vue object

```php
$vue->addDirective('focus',['inserted'=>"el.focus()"]);
```
addWatcher has 2 required arguments
 1. (string) directive's name
 2. (associative array) [hook => hook's function]

This generates the content below
```js
directives: {
	focus: {
		inserted:
			function(el,binding,vnode,oldVnode){
				el.focus()
			}
	}
}
```

## Vue Filters
This is the only possible method to add filters in your Vue object

```php
$vue->addFilter(
	'capitalize',   
	"if(!value){"
		."return '';"
		."value = value.toString();"  
		."return value.charAt(0).toUpperCase() + value.slice(1);}",  
	["value"]);
```
addFilter has 2 required arguments, and an optionnal one
 1. (string) filter's name
 2. (string) filter's function body
 3. (array) filter's function arguments **[optionnal]**

This generates the content below
```js
filters: {
	capitalize:
		function(value){
			if(!value){
				return '';
				value = value.toString();
				return value.charAt(0).toUpperCase() + value.slice(1);
			}
		}
}
```
## Vue Hooks
These are all the methods available to run a function at a given lifecycle

 - onBeforeCreate
 - onCreated
 - onBeforeMount
 - onMounted
 - onBeforeUpdate
 - onUpdated
 - onBeforeDestroy
 - onDestroyed

All of these methods work in the same way, so the example below can be applied for any of the above methods

```php
$vue->onCreated("console.log('Page has been created !');");
```
This generates the content below
```js
created:
	function(){
		console.log('Page has been created !');
	}
```