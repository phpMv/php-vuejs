<?php

namespace PHPMV\utils;

class PhpUtils {
	static private string $delimiter = '---';
	static private array $parsedJs = [];

	public static function importFromFile(string $filename, string $extension): string {
		return \file_get_contents("$filename.$extension", true);
	}

	public static function parseFile(string $filename, string $extension): array {
		$pattern = '/' . self::$delimiter . '(.+?)' . self::$delimiter . '(.+?)' . self::$delimiter . 'end' . self::$delimiter . '/s';
		$templateString = self::importFromFile($filename, $extension);
		\preg_match_all($pattern, $templateString, $templateArray);
		$iterationNumber = count($templateArray[0]);
		for ($i = 0; $i < $iterationNumber; $i++) {

			self::$parsedJs[$templateArray[1][$i]] = \str_replace(["\n","\r","\t"]," ",$templateArray[2][$i]);
		}
		return $templateArray;
	}

	public static function getParsedJs(string $name): string {
		return self::$parsedJs[$name];
	}
}

