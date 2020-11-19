<?php
namespace PHPMV\core;

class VueLibrary
{
    public static $revision = 1;
    
    public const VERSION = '0.0.0';
    
    public static function getTemplateFolder() {
        return \dirname(__FILE__) . '/templates/rev' . self::$revision;
    }
}
?>