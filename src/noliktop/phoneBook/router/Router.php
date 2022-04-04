<?php

declare(strict_types=1);


namespace noliktop\phoneBook\router;


use noliktop\phoneBook\controller\ControllerException;
use noliktop\phoneBook\controller\ControllerFactory;
use noliktop\phoneBook\exception\ErrorCodes;
use noliktop\phoneBook\exception\HTTPCodes;

abstract class Router {

	protected ControllerFactory $controllerFactory;

	public function __construct(ControllerFactory $controllerFactory) {
		$this->controllerFactory = $controllerFactory;
	}

	/**
	 * @throws RouterException
	 * @throws ControllerException
	 */
	public function getCallableMethod(string $path): callable {
		[$controllerName, $methodName] = $this->getControllerAndMethodNameFromPath($path);
		$controller = $this->controllerFactory->getController($controllerName);

		$callable = [$controller, $methodName];
		if (!is_callable($callable)) {
			throw new RouterException("No method $controllerName.$methodName", HTTPCodes::NOT_FOUND, ErrorCodes::CONTROLLER_NOT_FOUND);
		}

		return $callable;
	}

	abstract public function getControllerAndMethodNameFromPath(string $path): array;

}