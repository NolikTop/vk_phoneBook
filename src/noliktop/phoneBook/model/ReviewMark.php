<?php

namespace noliktop\phoneBook\model;

use mysqli_stmt;
use noliktop\phoneBook\db\Db;

class ReviewMark extends Model
{

	public int $reviewId;
	public int $userId;
	public int $mark;

	public static function fromRow(array $row): self
	{
		$review = new ReviewMark();
		$review->reviewId = (int)$row["review_id"];
		$review->userId = (int)$row["user_id"];
		$review->mark = (int)$row["mark"];

		return $review;
	}

	protected function prepareInsert(): mysqli_stmt
	{
		$db = Db::get();

		$p = $db->prepare("insert into reviews_marks (review_id, user_id, mark) values (?, ?, ?)");
		$p->bind_param("iii", $this->reviewId, $this->userId, $this->mark);

		return $p;
	}

	protected function afterInsert(): void
	{
	}

	protected function prepareUpdate(): mysqli_stmt
	{
		$db = Db::get();

		$p = $db->prepare("update reviews_marks set mark = ? where review_id = ? and user_id = ?");
		$p->bind_param("iii", $this->mark, $this->reviewId, $this->userId);

		return $p;
	}

}