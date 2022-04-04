<?php

namespace noliktop\phoneBook\db;

use mysqli;

class Db {

	/** @var mysqli */
	protected static mysqli $db;

	public static function init(DbCredentials $credentials): void {
		self::$db = new mysqli(
			$credentials->host,
			$credentials->user, $credentials->password,
			$credentials->database,
			$credentials->port
		);
	}

	public static function get(): mysqli {
		return self::$db;
	}

}