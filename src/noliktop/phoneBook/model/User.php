<?php

namespace noliktop\phoneBook\model;

use mysqli_stmt;
use noliktop\phoneBook\db\Db;

class User extends Model {

	public int $id;
	public string $name;

	public static function fromRow(array $row): self {
		$user = new User();
		$user->id = (int)$row["id"];
		$user->name = $row["name"];

		return $user;
	}

	protected function prepareInsert(): mysqli_stmt {
		$db = Db::get();

		$p = $db->prepare("insert into users (name) values (?)");
		$p->bind_param("s", $this->name);

		return $p;
	}

	protected function afterInsert(): void {
		$db = Db::get();

		$this->id = $db->insert_id;
	}

	protected function prepareUpdate(): mysqli_stmt {
		$db = Db::get();

		$p = $db->prepare("update users set name = ? where id = ?");
		$p->bind_param("si", $this->name, $this->id);

		return $p;
	}

}