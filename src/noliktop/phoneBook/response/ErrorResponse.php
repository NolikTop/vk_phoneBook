<?php

declare(strict_types=1);


namespace noliktop\phoneBook\response;


use noliktop\phoneBook\exception\AppException;
use noliktop\phoneBook\exception\HTTPCodes;
use Throwable;

class ErrorResponse implements IResponse {

	protected Throwable $exception;

	public function __construct(Throwable $e) {
		$this->exception = $e;
	}

	public function getHttpCode(): int {
		return HTTPCodes::BAD_REQUEST;
	}

	public function getData(): array {
		$e = $this->exception;

		return [
			'error' => [
				'code' => $e->getCode(),
				'message' => $e->getMessage(),
				...$this->getAdditionalData()
			]
		];

	}

	protected function getAdditionalData(): array{
		return [];
	}

}