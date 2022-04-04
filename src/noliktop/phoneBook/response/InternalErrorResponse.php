<?php

declare(strict_types=1);


namespace noliktop\phoneBook\response;


use Throwable;

class InternalErrorResponse extends ErrorResponse {

	public function __construct(Throwable $e) {
		parent::__construct($e);
	}

	public function getAdditionalData(): array {
		$e = $this->exception;

		return [
			'fullMessage' => $e->__toString()
		];
	}

}