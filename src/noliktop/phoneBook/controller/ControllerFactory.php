<?php

declare(strict_types=1);


namespace noliktop\phoneBook\controller;


use noliktop\phoneBook\exception\ErrorCodes;
use noliktop\phoneBook\exception\HTTPCodes;

abstract class ControllerFactory {

	/** @var array<string, Controller> */
	protected array $controllers = [];

	public function __construct() {
		$this->registerAllControllers();
	}

	abstract public function registerAllControllers(): void;

	protected function register(Controller $controller) {
		$this->controllers[$controller->getName()] = $controller;
	}

	/**
	 * @throws ControllerException
	 */
	public function getController(string $controllerName): Controller {
		if (!isset($this->controllers[$controllerName])) {
			throw new ControllerException("No controller with name $controllerName", HTTPCodes::NOT_FOUND, ErrorCodes::CONTROLLER_NOT_FOUND);
		}

		return $this->controllers[$controllerName];
	}

}