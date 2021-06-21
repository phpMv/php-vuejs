<?php

namespace PHPMV\utils;

/**
 * Some php utilities.
 *
 * PHPMV\utilsPhpUtils
 * This class is part of php-vuejs
 *
 * @author jguillaumesio
 * @version 1.0.0
 * @package PHPMV\utils
 */

class PhpUtils {
	static private string $delimiter = '-';
	static private array $parsedJs = [];

	public static function importFromFile(string $filename, string $extension): string {
		return \str_replace(["\n","\r","\t"],"",\file_get_contents("$filename.$extension", true));
	}

	public static function parseFile(string $filename, string $extension): array {
		$pattern = '/\/\/' . self::$delimiter . '{3,}(.+?)' . self::$delimiter . '{3,}(.+?)\/\/' . self::$delimiter . '{3,}end' . self::$delimiter . '{3,}/s';
		$templateString = self::importFromFile($filename, $extension);
		\preg_match_all($pattern, $templateString, $templateArray);
		$iterationNumber = \count($templateArray[0]);
		for ($i = 0; $i < $iterationNumber; $i++) {
			self::$parsedJs[$templateArray[1][$i]] = $templateArray[2][$i];
		}
		return $templateArray;
	}

	public static function getParsedJs(string $name,array $variables = []): string {
		$element=self::$parsedJs[$name];
		foreach($variables as $key => $value){
			$element = \str_replace("{{ $key }}",$value, $element);
		}

		return $element;
	}
}
