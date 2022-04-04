<?php

declare(strict_types=1);


namespace noliktop\phoneBook\request;


class IncomingRequest {

	protected string $path;
	protected array $args;

	public function __construct(string $path, array $args) {
		$this->path = $path;
		$this->args = $args;
	}

	public function getPath(): string {
		return $this->path;
	}

	public function getArgs(): array {
		return $this->args;
	}

}