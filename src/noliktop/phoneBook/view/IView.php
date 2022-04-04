<?php

declare(strict_types=1);


namespace noliktop\phoneBook\view;


interface IView {

	public function getHeaders(): array;

	public function render(): string;

}