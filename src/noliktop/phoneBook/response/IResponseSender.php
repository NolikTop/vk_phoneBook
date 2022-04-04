<?php

declare(strict_types=1);


namespace noliktop\phoneBook\response;


interface IResponseSender {

	public function send(IResponse $response): void;

}