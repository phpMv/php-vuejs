<?php
namespace PHPMV\utils;

class JsUtils{
    static private array $removeQuote = ["start"=>"!!%","end"=>"%!!"];

    public static function cleanJSONFunctions(string $json):string {
        $pattern='/(("|\')'.self::$removeQuote['start'].')|('.self::$removeQuote['end'].'("|\'))/';
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

    public static function declareVariable(string $type, string $name, $value,bool $lineBreak = true):string {
        $declaration = $type." ".$name." = ".$value.";";
        if ($lineBreak) $declaration .= PHP_EOL;
        return $declaration;
    }

    public static function kebabToPascal(string $string):string {
        $string[0] = \strtoupper($string[0]);
        $pattern='/(-\w{1})/';
        return \preg_replace_callback($pattern,
            function ($matches){
                return \strtoupper($matches[1][1]);

        },$string);
    }
}

