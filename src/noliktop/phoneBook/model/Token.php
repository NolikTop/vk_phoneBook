<?php

namespace noliktop\phoneBook\model;

use mysqli_stmt;
use noliktop\phoneBook\db\Db;

class Token
{

	public string $token;
	public int $user_id;

	public static function fromRow(array $row): self
	{
		$token = new Token();
		$token->token = $row["token"];
		$token->user_id = (int)$row["user_id"];

		return $token;
	}

	protected function prepareInsert(): mysqli_stmt
	{
		$db = Db::get();

		$p = $db->prepare("insert into tokens (token, user_id) values (?, ?)");
		$p->bind_param("ii", $this->token, $this->user_id);

		return $p;
	}

	protected function afterInsert(): void
	{
		$db = Db::get();

		$this->user_id = $db->insert_id;
	}

	/**
	 * @throws ModelException
	 */
	protected function prepareUpdate(): mysqli_stmt
	{
		throw new ModelException("Cant update token");
	}

}