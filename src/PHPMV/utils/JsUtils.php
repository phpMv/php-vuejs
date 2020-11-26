<?php
namespace PHPMV\utils;

class JsUtils
{
    public function cleanJSONFunctions(string $json):string {
        return \str_replace([
            '"!!%',
            '%!!"',
            '%!!',
            '!!%'
        ], '', $json);
    }
}

