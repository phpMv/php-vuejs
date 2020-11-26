<?php
namespace PHPMV\utils;

class JsUtils
{
    public static function cleanJSONFunctions(string $json):string {
        return \str_replace([
            '"!!%',
            '%!!"',
            '%!!',
            '!!%'
        ], '', $json);
    }
}

