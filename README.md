

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
## Creation of Vue Manager
It manages the whole script injected , you must instantiate it
```php
$vueManager = VueManager::getInstance();
``` 

VueManager methods
 - [addGlobalDirective](#global-directives)
 - [addGlobalFilter](#global-filters)
 - [addGlobalExtend](#global-extends)
 - [addGlobalMixin](#global-mixins)
 - [addGlobalObservable](#global-observables)
 - [addGlobalComponent](#global-components)
 - [importComponentObject](#local-components)
 - [setAxios](#)
 - [addVue](#)

## Creation of Vue object

```php
$vue = new VueJs();
```
VueJS arguments
 1. (associative array) configuration = ["el" => "#app"] **[optionnal]**
 2. (string) variable name = "app" **[optionnal]**
 3. (boolean) enable [vuetify](https://vuetifyjs.com/en/) = false **[optionnal]**

VueJS methods

 - [addData](#object-data)
 - [addDataRaw](#object-data)
 - [addMethod](#object-methods)
 - [addComputed](#object-computeds)
 - [addWatcher](#object-watchers)
 - [addDirective](#object-directives)
 - [addFilter](#object-filters)
 - [addHook](#object-hooks)

## Creation of Component Object

```php
$component = new VueJSComponent('component-one');
```
VueJSComponent argument's
 1. (string) name
 2. (string) variable name = null **[optionnal]**
 
 Component name must respect kebab-case principle, if you don't provide a variable name, it would be name converted to PascalCase

VueJSComponent methods

 - [addProps](#add-props)
 - [setInheritAttrs](#)
 - [setModel](#)
 - [addData](#object-data)
 - [extends](#extends)
 - [addDataRaw](#object-data)
 - [addMethod](#object-methods)
 - [addComputed](#object-computeds)
 - [addWatcher](#object-watchers)
 - [addDirective](#object-directives)
 - [addFilter](#object-filters)
 - [addTemplate](#add-template)
 - [generateFile](#generate-file)
 - [addHook](#object-hooks)

### Object Data
```php
$object->addData('name',true);
$object->addDataRaw('name','true');
```
addData, addDataRaw arguments
 1. (string) name
 2. (string) value

These two lines of code generate exactly the same Vue data
```js
data: { "name": true }
```

### Object Methods
```php
$object->addMethod('greet','window.alert(`Hello ${name}`);',['name']);
```
addMethod arguments
 1. (string) name
 2. (string) function's body
 3. (array) function's argument(s) **[optionnal]**
 
This will generate the content below
```js
methods: {
	"greet":
		function(name){
			window.alert(`Hello ${name}`);
		}
}
```

### Object Computeds
```php
$object->addComputed('reversedMessage',"return this.message.split('').reverse().join('');");
```
addComputed arguments
 1. (string) name
 2. (string) get function's body
 3. (string) set function's body by default the function argument is v **[optionnal]**

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
$object->addComputed(
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

### Object Watchers

```php
$object->addWatcher(
	"title",
	"console.log('Title change from '+ oldTitle +' to '+ newTitle)",
	['newTitle','oldTitle']);
```
addWatcher arguments
 1. (string) data to watch
 2. (string) function body
 3. (array) function argument(s) **[optionnal]**

This generates the content below
```js
watch: {
	"title":
		function(newTitle,oldTitle){
			console.log('Title change from '+ oldTitle +' to '+ newTitle)
		}
}
```

### Object Hooks
These are all the methods available to run a function at a given lifecycle

 - onBeforeCreate
 - onCreated
 - onBeforeMount
 - onMounted
 - onBeforeUpdate
 - onUpdated
 - onBeforeDestroy
 - onDestroyed
 - onActivated
 - onDeactivated

All hooks work in the same way, the underneath example can be applied for each hooks

hooks arguments
 1. (string) function's body

```php
$object->onCreated("console.log('Page has been created !');");
```
This generates the content below
```js
created:
	function(){
		console.log('Page has been created !');
	}
```

### Directives
addDirective, addGlobalDirective arguments
 1. (string) directive's name
 2. (associative array) [hook => hook's function]
#### Object Directives

```php
$object->addDirective('focus',['inserted'=>"el.focus()"]);
```
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
#### Global directives
```php
$vueManager->addGlobalDirective('focus',['inserted'=>"el.focus()"]);
```
This generates the content below
```js
Vue.directive('focus',{
	inserted:
		function(el,binding,vnode,oldVnode){
			el.focus()
		}
	});
```

### Filters

addFilter, addGlobalFilter arguments
 1. (string) name
 2. (string) function body
 3. (array) function arguments **[optionnal]**

#### Object Filters

```php
$object->addFilter(
	'capitalize',   
	"if(!value){"
		."return '';"
	."}"
	."value = value.toString();"  
	."return value.charAt(0).toUpperCase() + value.slice(1);",  
	["value"]);
```
This generates the content below
```js
filters: {
	capitalize: function(value){
		if(!value){return '';}
		value = value.toString();
		return value.charAt(0).toUpperCase() + value.slice(1);
	}
}
```
#### Global Filters

```php
$vueManager->addGlobalFilter(
	'capitalize',   
	"if(!value){"
		."return '';"
	."}"
	."value = value.toString();"  
	."return value.charAt(0).toUpperCase() + value.slice(1);",  
	["value"]);
```
This generates the content below
```js
Vue.filter('capitalize',
	function(value){
		if(!value){return '';}
		value = value.toString();
		return value.charAt(0).toUpperCase() + value.slice(1);
	});
```

### Components

addComponent, addGlobalComponent, importComponentObject arguments
 1. (VueJSComponent) component object
 
#### Vue Components
```php
$component = new VueJSComponent('component-one');
$component->addData('framework','ubiquity');
$vue->addComponent($component);
```
This generates the content below
```js
components: { "component-one": ComponentOne }
```

#### Local Components
```php
$component = new VueJSComponent('component-one');
$component->addData('framework','ubiquity');
$vueManager->importComponentObject($component);
```
This generates the content below
```js
const ComponentOne = {
	data:
		function(){
			return {framework: "ubiquity"}
		}
	};
```
#### Global Components
```php
$component = new VueJSComponent('component-one');
$component->addData('framework','ubiquity');
$vueManager->addGlobalComponent($component);
```
This generates the content below
```js
Vue.component('component-one',{
	data:
		function(){
			return {framework: "ubiquity"}
		}
	});
```
### Global Observables
addGlobalObservable arguments
 1. (string) variable name
 2. (array) object
```php
$vueManager->addGlobalObservable("state", ["count" => 0]);
```
This generates the content below
```js
const state = Vue.observable({count: 0});
```

### Global Mixins
addGlobalMixin argument

 1. (VueJSComponent) mixin object
```php
$mixin = new VueJSComponent('mixin-one');
$mixin->addData('framework','ubiquity');
$vueManager->addGlobalMixin($mixin);
```
This generates the content below
```js
Vue.mixin({
	data: 
		function(){
			return {framework: "ubiquity"}
		}
	});
```

### Global Extends
addGlobalExtend argument

 1. (VueJSComponent) extend object
```php
$extend = new VueJSComponent('extend-one');
$extend->addData('framework','ubiquity');
$vueManager->addGlobalMixin($extend);
```
This generates the content below
```js
Vue.extend({
	data:
		function(){
			return {framework: "ubiquity"}
		}
	});
```