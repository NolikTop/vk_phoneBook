<?php

namespace noliktop\phoneBook\config;

use JsonException;

class Config {

	protected string $path;
	protected array $contents;

	/**
	 * @throws ConfigException
	 * @throws JsonException
	 */
	public function __construct(string $path) {
		$this->path = $path;

		$this->contents = $this->load();
	}

	public function get(string $field): array {
		return $this->contents[$field];
	}

	public function exist(string $field): bool {
		return isset($this->contents[$field]);
	}

	public function fillObject(string $field, object $obj): object {
		$data = $this->get($field);

		foreach ($data as $key => $value) {
			$obj->{$key} = $value;
		}

		return $obj;
	}

	/**
	 * @throws ConfigException
	 * @throws JsonException
	 */
	protected function load(): array {
		$contents = file_get_contents($this->path);
		if ($contents === false) {
			throw new ConfigException("Couldn't read config file");
		}

		return json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
	}

}