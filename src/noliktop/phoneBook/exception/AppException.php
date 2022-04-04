<?php

declare(strict_types=1);


namespace noliktop\phoneBook\exception;


use Exception;
use noliktop\phoneBook\response\AppErrorResponse;
use noliktop\phoneBook\response\IResponse;
use Throwable;

class AppException extends Exception {

	protected int $httpCode;

	public function __construct($message = "", int $httpCode = HTTPCodes::BAD_REQUEST, $code = 0, Throwable $previous = null) {
		$this->httpCode = $httpCode;
		parent::__construct($message, $code, $previous);
	}

	public function getHttpCode(): int{
		return $this->httpCode;
	}

	public function toResponse(): IResponse{
		return new AppErrorResponse($this);
	}

}