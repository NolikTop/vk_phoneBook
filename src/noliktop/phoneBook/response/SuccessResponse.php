<?php

declare(strict_types=1);


namespace noliktop\phoneBook\response;


use noliktop\phoneBook\exception\HTTPCodes;

class SuccessResponse implements IResponse {

	protected array $data;
	protected int $httpCode;

	public function __construct(array $data, int $httpCode = HTTPCodes::OK) {
		$this->data = $data;
		$this->httpCode = $httpCode;
	}

	public function getHttpCode(): int {
		return $this->httpCode;
	}

	public function getData(): array {
		return [
			"response" => $this->data
		];
	}

}