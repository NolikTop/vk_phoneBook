<?php

declare(strict_types=1);


namespace noliktop\phoneBook\view;


class JsonView implements IView {

	protected array $data;

	public function __construct(array $data) {
		$this->data = $data;
	}

	public function getHeaders(): array {
		return [
			"Content-type: application/json"
		];
	}

	public function render(): string {
		return json_encode($this->getData());
	}

	public function getData(): array {
		return $this->data;
	}

}