<?php

namespace noliktop\phoneBook\model;

use noliktop\phoneBook\exception\AppException;
use noliktop\phoneBook\exception\HTTPCodes;
use Throwable;

class ModelNotFoundException extends AppException {

	public function __construct($message = "Not found", $code = 0, Throwable $previous = null) {
		parent::__construct($message, HTTPCodes::NOT_FOUND, $code, $previous);
	}

}