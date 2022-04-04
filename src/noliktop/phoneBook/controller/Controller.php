<?php

declare(strict_types=1);


namespace noliktop\phoneBook\controller;


use noliktop\phoneBook\exception\HTTPCodes;
use noliktop\phoneBook\response\IResponse;
use noliktop\phoneBook\response\SuccessResponse;

abstract class Controller {

	abstract public function getName(): string;

	protected final function response(array $data, int $httpCode = HTTPCodes::OK): IResponse {
		return new SuccessResponse($data, $httpCode);
	}

}