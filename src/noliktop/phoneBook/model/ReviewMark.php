<?php

namespace noliktop\phoneBook\model;

use noliktop\phoneBook\db\Db;
use noliktop\phoneBook\exception\DbException;

class ReviewMark extends Model {

	public int $reviewId;
	public int $userId;
	public int $mark;

	/**
	 * @throws DbException
	 */
	public function insertOrUpdate(): void {
		$db = Db::get();

		$queryText = <<<QUERY
insert into reviews_marks (review_id, user_id, mark) values (?, ?, ?)
on duplicate key update mark = values(mark);
QUERY;

		$p = $db->prepare($queryText);
		if (!$p) {
			throw new DbException($db->error);
		}

		$p->bind_param("iii", $this->reviewId, $this->userId, $this->mark);
		if (!$p->execute()) {
			throw new DbException($db->error);
		}
	}

}