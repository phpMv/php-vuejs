<?php

namespace PHPMV\utils;

class PhpUtils {
	static private string $delimiter = '---';
	static private array $parsedJs = [];

	public static function importJsFromFile(string $filename): string {
		return \file_get_contents($filename . '.js', true);
	}

	public static function parseFile(string $filename, string $extension): array {
		$pattern = '/' . self::$delimiter . '(.+?)' . self::$delimiter . '(.+?)' . self::$delimiter . 'end' . self::$delimiter . '/s';
		$templateString = self::importFile($filename, $extension);
		\preg_match_all($pattern, $templateString, $templateArray);
		for ($i = 0; $i < count($templateArray[0]); $i++) {
			self::$parsedJs[$templateArray[1][$i]] = $templateArray[2][$i];
		}
		return $templateArray;
	}

	public static function getParsedJs(string $name): string {
		return self::$parsedJs[$name];
	}
}

