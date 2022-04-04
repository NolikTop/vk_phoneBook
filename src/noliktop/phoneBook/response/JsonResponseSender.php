<?php

declare(strict_types=1);


namespace noliktop\phoneBook\response;


use noliktop\phoneBook\view\JsonView;

class JsonResponseSender implements IResponseSender {

	public function send(IResponse $response): void {
		$json = new JsonView($response->getData());

		echo $json->render();
		foreach ($json->getHeaders() as $header){
			header($header);
		}

		http_response_code($response->getHttpCode());
	}

}