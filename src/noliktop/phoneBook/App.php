<?php

declare(strict_types=1);


namespace noliktop\phoneBook;


use noliktop\phoneBook\controller\ControllerFactory;
use noliktop\phoneBook\controller\PhoneBookControllerFactory;
use noliktop\phoneBook\request\IncomingRequestFactory;
use noliktop\phoneBook\request\RequestHandler;
use noliktop\phoneBook\response\IResponseSender;
use noliktop\phoneBook\response\JsonResponseSender;
use noliktop\phoneBook\router\PhoneBookRouter;
use noliktop\phoneBook\router\Router;

class App {

	public function handleRequest(): void{
		$request = IncomingRequestFactory::getRequestFromGlobals();

		$requestHandler = $this->getRequestHandler();
		$response = $requestHandler->handle($request);

		$responseSender = $this->getResponseSender();
		$responseSender->send($response);
	}

	protected function getRequestHandler(): RequestHandler{
		return new RequestHandler($this->getRouter());
	}

	protected function getRouter(): Router{
		return new PhoneBookRouter($this->getControllerFactory());
	}

	protected function getControllerFactory(): ControllerFactory{
		return new PhoneBookControllerFactory();
	}

	protected function getResponseSender(): IResponseSender{
		return new JsonResponseSender();
	}

}