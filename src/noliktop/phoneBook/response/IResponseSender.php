<?php

declare(strict_types=1);


namespace noliktop\phoneBook\response;


use noliktop\phoneBook\view\IView;

interface IResponseSender {

	public function send(IResponse $response): void;

}