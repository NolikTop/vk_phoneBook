<?php

declare(strict_types=1);


namespace noliktop\phoneBook\response;


use noliktop\phoneBook\exception\AppException;

class AppErrorResponse extends ErrorResponse {

	public function __construct(AppException $e) {
		parent::__construct($e);
	}

	public function getHttpCode(): int {
		/** @var AppException $e */
		$e = $this->exception;

		return $e->getHttpCode();
	}

}