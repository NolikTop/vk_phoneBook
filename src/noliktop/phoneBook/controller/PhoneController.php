<?php

declare(strict_types=1);


namespace noliktop\phoneBook\controller;


use noliktop\phoneBook\response\IResponse;

class PhoneController extends Controller {

	public function getName(): string {
		return 'phone';
	}

	public function lol(array $args): IResponse {
		return $this->response([
			'test' => 'lol',
			'args' => $args
		]);
	}

}