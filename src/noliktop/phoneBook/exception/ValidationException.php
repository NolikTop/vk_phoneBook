<?php

declare(strict_types=1);


namespace noliktop\phoneBook\exception;


use noliktop\phoneBook\response\IResponse;
use noliktop\phoneBook\response\ValidationErrorResponse;
use Throwable;

class ValidationException extends AppException {

	protected string $parameter;

	public function __construct(string $parameter, $message = "", int $httpCode = HTTPCodes::BAD_REQUEST, $code = 0, Throwable $previous = null) {
		parent::__construct($message, $httpCode, $code, $previous);

		$this->parameter = $parameter;
	}

	public function getParameter(): string {
		return $this->parameter;
	}

	public function toResponse(): IResponse{
		return new ValidationErrorResponse($this);
	}

}