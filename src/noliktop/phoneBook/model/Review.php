<?php

namespace noliktop\phoneBook\model;

use mysqli_stmt;
use noliktop\phoneBook\db\Db;

class Review extends Model
{

	public int $id;
	public int $phoneId;
	public ?int $userId;
	public string $review;

	public static function fromRow(array $row): self
	{
		$review = new Review();
		$review->id = (int)$row["id"];
		$review->phoneId = (int)$row["phone_id"];
		$review->userId = $row["user_id"];
		$review->review = $row["review"];

		return $review;
	}

	protected function prepareInsert(): mysqli_stmt
	{
		$db = Db::get();

		$p = $db->prepare("insert into reviews (phone_id, user_id, review) values (?, ?, ?)");
		$p->bind_param("iis", $this->phoneId, $this->userId, $this->review);

		return $p;
	}

	protected function afterInsert(): void
	{
		$db = Db::get();

		$this->id = $db->insert_id;
	}

	protected function prepareUpdate(): mysqli_stmt
	{
		$db = Db::get();

		$p = $db->prepare("update reviews set phone_id = ?, user_id = ?, review = ? where id = ?");
		$p->bind_param("iisi", $this->phoneId, $this->userId, $this->review, $this->id);

		return $p;
	}

}