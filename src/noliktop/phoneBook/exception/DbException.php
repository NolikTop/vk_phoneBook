<?php

declare(strict_types=1);


namespace noliktop\phoneBook\exception;


use Throwable;

class DbException extends AppException {

	public function __construct($message = "", int $httpCode = HTTPCodes::INTERNAL_SERVER_ERROR, $code = 0, Throwable $previous = null) {
		parent::__construct($message, $httpCode, $code, $previous);
	}

}