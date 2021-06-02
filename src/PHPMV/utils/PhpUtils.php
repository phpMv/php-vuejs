<?php

namespace PHPMV\utils;

class PhpUtils {
	static private string $delimiter = '---';
	static private array $parsedFunctions = [];

	public static function parseFile(string $filename, string $extension): array {
		$pattern = '/' . self::$delimiter . '(.+?)'. self::$delimiter . '(.+?)' . self::$delimiter . 'end' . self::$delimiter . '/s';
		$templateString = \file_get_contents($filename . '.' . $extension, true);
		\preg_match_all($pattern, $templateString, $templateArray);
		for($i=0;$i<count($templateArray[0]);$i++){
			self::$parsedFunctions[$templateArray[1][$i]] = $templateArray[2][$i];
		}
		return $templateArray;
	}

	public static function getParsedFunctions():array {
		return self::$parsedFunctions;
	}
}

