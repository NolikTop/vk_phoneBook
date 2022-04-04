<?php

declare(strict_types=1);


namespace noliktop\phoneBook\response;


interface IResponse {

	public function getHttpCode(): int;

	public function getData(): array;

}