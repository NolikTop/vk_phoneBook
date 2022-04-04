<?php

declare(strict_types=1);


namespace noliktop\phoneBook;


use JsonException;
use noliktop\phoneBook\config\Config;
use noliktop\phoneBook\config\ConfigException;
use noliktop\phoneBook\controller\ControllerFactory;
use noliktop\phoneBook\controller\PhoneBookControllerFactory;
use noliktop\phoneBook\db\Db;
use noliktop\phoneBook\db\DbCredentials;
use noliktop\phoneBook\request\IncomingRequestFactory;
use noliktop\phoneBook\request\RequestHandler;
use noliktop\phoneBook\response\IResponseSender;
use noliktop\phoneBook\response\JsonResponseSender;
use noliktop\phoneBook\router\PhoneBookRouter;
use noliktop\phoneBook\router\Router;

class App {

	protected Config $config;

	/**
	 * @throws ConfigException
	 * @throws JsonException
	 */
	public function __construct() {
		$this->loadConfig();
		$this->loadDb();
	}

	/**
	 * @throws ConfigException
	 * @throws JsonException
	 */
	protected function loadConfig(): void {
		$ds = DIRECTORY_SEPARATOR;

		$allConfigsPath = __DIR__ . "$ds..$ds..$ds..{$ds}config$ds";
		$configPath = $allConfigsPath . "config.json";

		$this->config = new Config($configPath);
	}

	protected function loadDb(): void {
		$credentials = $this->getDbCredentials();
		Db::init($credentials);
	}

	protected function getDbCredentials(): DbCredentials {
		$credentials = new DbCredentials();
		$this->config->fillObject('db', $credentials);

		return $credentials;
	}

	public function handleRequest(): void {
		$request = IncomingRequestFactory::getRequestFromGlobals();

		$requestHandler = $this->getRequestHandler();
		$response = $requestHandler->handle($request);

		$responseSender = $this->getResponseSender();
		$responseSender->send($response);
	}

	protected function getRequestHandler(): RequestHandler {
		return new RequestHandler($this->getRouter());
	}

	protected function getRouter(): Router {
		return new PhoneBookRouter($this->getControllerFactory());
	}

	protected function getControllerFactory(): ControllerFactory {
		return new PhoneBookControllerFactory();
	}

	protected function getResponseSender(): IResponseSender {
		return new JsonResponseSender();
	}

}