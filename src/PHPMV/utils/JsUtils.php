<?php
namespace PHPMV\utils;

class JsUtils{
    static private array $removeQuote = ["start"=>"!!%","end"=>"%!!"];

    public static function cleanJSONFunctions(string $json):string {
        $pattern='/(("|\')!!%)|(%!!("|\'))/';
        return \preg_replace($pattern, '', $json);
    }

    public static function removeQuotes(string $body):string{
        return self::$removeQuote["start"].$body.self::$removeQuote["end"];
    }

    public static function generateFunction(string $body, array $params = [], bool $needRemoveQuote = true):string {
        if($needRemoveQuote){
            return self::removeQuotes("function(".implode(",",$params)."){".$body."}");
        }
        return "function(".implode(",",$params)."){".$body."}";
    }
}

