<?php

declare(strict_types=1);


namespace noliktop\phoneBook\response;


use noliktop\phoneBook\exception\ValidationException;

class ValidationErrorResponse extends AppErrorResponse {

	public function __construct(ValidationException $e) {
		parent::__construct($e);
	}

	public function getAdditionalData(): array {
		/** @var ValidationException $e */
		$e = $this->exception;

		return [
			'parameter' => $e->getParameter()
		];
	}

}