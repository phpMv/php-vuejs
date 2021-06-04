<?php

namespace PHPMV\utils;

class PhpUtils {
	static private string $delimiter = '---';
	static private array $parsedJs = [];

	public static function importFromFile(string $filename, string $extension): string {
		return \str_replace(["\n","\r","\t"],"",\file_get_contents("$filename.$extension", true));
	}

	public static function parseFile(string $filename, string $extension): array {
		$pattern = '/' . self::$delimiter . '(.+?)' . self::$delimiter . '(.+?)' . self::$delimiter . 'end' . self::$delimiter . '/s';
		$templateString = self::importFromFile($filename, $extension);
		\preg_match_all($pattern, $templateString, $templateArray);
		$iterationNumber = count($templateArray[0]);
		for ($i = 0; $i < $iterationNumber; $i++) {

			self::$parsedJs[$templateArray[1][$i]] = $templateArray[2][$i];
		}
		return $templateArray;
	}

	public static function getParsedJs(string $name,array $variables = []): string {
		if(isset($variables)){
			foreach($variables as $key => $value){
				self::$parsedJs[$name] = \str_replace("{{ $key }}",$value, self::$parsedJs[$name]);
			}
		}
		return self::$parsedJs[$name];
	}
}

