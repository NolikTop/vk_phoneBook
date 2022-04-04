<?php

namespace noliktop\phoneBook\model;

use mysqli_stmt;
use noliktop\phoneBook\db\Db;
use noliktop\phoneBook\exception\AuthException;
use noliktop\phoneBook\exception\DbException;
use noliktop\phoneBook\exception\ErrorCodes;
use noliktop\phoneBook\exception\HTTPCodes;

class Token {

	public string $token;
	public int $userId;

	/**
	 * @throws DbException
	 * @throws AuthException
	 */
	public static function get(string $token): self {
		$db = Db::get();

		$p = $db->prepare("select * from tokens where token = ?");
		if (!$p) {
			throw new DbException($db->error);
		}

		$p->bind_param("s", $token);

		if (!$p->execute()) {
			throw new DbException($db->error);
		}

		$q = $p->get_result();
		if ($q->num_rows === 0) {
			throw new AuthException("No such user with given token", HTTPCodes::UNAUTHORIZED, ErrorCodes::WRONG_TOKEN);
		}

		$row = $q->fetch_assoc();

		return self::fromRow($row);
	}

	public static function fromRow(array $row): self {
		$token = new Token();
		$token->token = $row["token"];
		$token->userId = (int)$row["user_id"];

		return $token;
	}

	protected function prepareInsert(): mysqli_stmt {
		$db = Db::get();

		$p = $db->prepare("insert into tokens (token, user_id) values (?, ?)");
		$p->bind_param("ii", $this->token, $this->userId);

		return $p;
	}

	protected function afterInsert(): void {
		$db = Db::get();

		$this->userId = $db->insert_id;
	}

	/**
	 * @throws ModelException
	 */
	protected function prepareUpdate(): mysqli_stmt {
		throw new ModelException("Cant update token");
	}

}