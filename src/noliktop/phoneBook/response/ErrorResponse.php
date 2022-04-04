<?php

declare(strict_types=1);


namespace noliktop\phoneBook\response;


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

		$t = [
			'code' => $e->getCode(),
			'message' => $e->getMessage(),
		];

		$t = array_merge($t, $this->getAdditionalData());

		return [
			'error' => $t
		];

	}

	protected function getAdditionalData(): array {
		return [];
	}

}