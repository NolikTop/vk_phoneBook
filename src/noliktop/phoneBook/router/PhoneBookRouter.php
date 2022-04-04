<?php

declare(strict_types=1);


namespace noliktop\phoneBook\router;


use noliktop\phoneBook\exception\AppException;
use noliktop\phoneBook\exception\ErrorCodes;
use noliktop\phoneBook\exception\HTTPCodes;

class PhoneBookRouter extends Router {

	/**
	 * @throws AppException
	 */
	public function getControllerAndMethodNameFromPath(string $path): array {
		$parts = explode('.', $path, 3);
		if (!isset($parts[1])) {
			throw new AppException("No method or controller specified", HTTPCodes::BAD_REQUEST, ErrorCodes::METHOD_NOT_FOUND);
		}
		[$controllerName, $methodName] = $parts;

		return [$controllerName, $methodName];
	}

}