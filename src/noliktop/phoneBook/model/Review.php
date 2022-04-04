<?php

namespace noliktop\phoneBook\model;

use mysqli_stmt;
use noliktop\phoneBook\db\Db;
use noliktop\phoneBook\exception\DbException;
use noliktop\phoneBook\exception\HTTPCodes;

class Review extends Model {

	public int $id;
	public int $phoneId;
	public ?int $userId;
	public string $review;
	public int $createdAt;

	/**
	 * @throws ModelException
	 */
	public static function getAllForPhone(Phone $phone, int $count, int $offset): array {
		$db = Db::get();

		$queryText = <<<QUERY
select * from reviews where phone_id = $phone->id 
QUERY;
		$q = $db->query($queryText);
		if (!$q) {
			throw new ModelException("Db error: $db->error", HTTPCodes::INTERNAL_SERVER_ERROR);
		}

		$allCount = $q->num_rows;

		$queryText = <<<QUERY
select r.id,
       if(isnull(sum(rm.mark)), 0, sum(rm.mark)) as rating,
       if(isnull(u.name), 'анонимно', u.name) as author,
       r.review,
       unix_timestamp(created_at) as created_at
from reviews r
left join reviews_marks rm on r.id = rm.review_id
left join users u on r.user_id = u.id
where phone_id = $phone->id
group by r.id, u.name
order by rating desc
limit $offset,$count
QUERY;
		$q = $db->query($queryText);

		$t = $q->fetch_all(MYSQLI_ASSOC);
		foreach ($t as &$review) { // mysql moment =(
			$review["id"] = (int)$review["id"];
			$review["rating"] = (int)$review["rating"];
			$review["created_at"] = (int)$review["created_at"];
		}

		return [
			"all_count" => $allCount,
			"reviews" => $t
		];
	}

	public static function fromRow(array $row): self {
		$review = new Review();
		$review->id = (int)$row["id"];
		$review->phoneId = (int)$row["phone_id"];
		$review->userId = $row["user_id"];
		$review->review = $row["review"];
		$review->createdAt = (int)$row["created_at"];

		return $review;
	}

	public static function fromId(int $id): self {
		$review = new Review();
		$review->id = $id;

		return $review;
	}

	protected function prepareInsert(): mysqli_stmt {
		$db = Db::get();

		$p = $db->prepare("insert into reviews (phone_id, user_id, review) values (?, ?, ?)");
		$p->bind_param("iis", $this->phoneId, $this->userId, $this->review);

		return $p;
	}

	protected function afterInsert(): void {
		$db = Db::get();

		$this->id = $db->insert_id;
		$this->createdAt = time(); // ибо зачем в бд лишний раз идти
	}

	protected function prepareUpdate(): mysqli_stmt {
		$db = Db::get();

		$p = $db->prepare("update reviews set phone_id = ?, user_id = ?, review = ? where id = ?");
		$p->bind_param("iisi", $this->phoneId, $this->userId, $this->review, $this->id);

		return $p;
	}

	/**
	 * @throws DbException
	 */
	public function getRating(): int {
		$db = Db::get();

		$queryText = <<<QUERY
select sum(rm.mark) as rating from reviews r
left join reviews_marks rm on r.id = rm.review_id
where r.id = ?
QUERY;
		$p = $db->prepare($queryText);
		if (!$p) {
			throw new DbException($db->error);
		}

		$p->bind_param("i", $this->id);

		if (!$p->execute()) {
			throw new DbException($db->error);
		}

		$q = $p->get_result();
		$row = $q->fetch_assoc();

		return (int)$row["rating"];
	}

}