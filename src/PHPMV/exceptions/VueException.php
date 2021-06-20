<?php


namespace PHPMV\exceptions;



class VueException extends \Exception {
	public function __construct($message = '', $code = 0, \Throwable $previous = null) {
		parent::__construct($message, $code, $previous);
	}

	public function __toString(): string {
		return \get_class($this) . " '{$this->message}' in {$this->file}({$this->line})".\PHP_EOL
			. "{$this->getTraceAsString()}";
	}

}