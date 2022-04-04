<?php

declare(strict_types=1);


namespace noliktop\phoneBook\request;


use noliktop\phoneBook\exception\AppException;
use noliktop\phoneBook\exception\HTTPCodes;
use noliktop\phoneBook\response\ErrorResponse;
use noliktop\phoneBook\response\IResponse;
use noliktop\phoneBook\router\Router;
use Throwable;

class RequestHandler {

	protected Router $router;

	public function __construct(Router $router){
		$this->router = $router;
	}

	public function handle(IncomingRequest $request): IResponse {
		$router = $this->router;
		$args = $request->getArgs();
		$path = $request->getPath();

		try {
			$callable = $router->getCallableMethod($path);
			$response = $this->callMethod($callable, $args);
		} catch (AppException $e) {
			$response = $e->toResponse();
		} catch (Throwable $e) {
			$response = new ErrorResponse($e);
		}

		return $response;
	}

	/**
	 * @throws AppException
	 */
	protected function callMethod(callable $callable, array $args): IResponse {
		$response = $callable($args);

		if (!$response instanceof IResponse) {
			throw new AppException("Wrong return type of method", HTTPCodes::INTERNAL_SERVER_ERROR);
		}

		return $response;
	}

}