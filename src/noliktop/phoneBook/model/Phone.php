<?php

namespace noliktop\phoneBook\model;

use mysqli_stmt;
use noliktop\phoneBook\db\Db;
use noliktop\phoneBook\exception\AppException;
use noliktop\phoneBook\exception\DbException;
use noliktop\phoneBook\exception\ErrorCodes;

class Phone extends InsertableModel {

	public int $id;
	public string $phone;

	/**
	 * @throws AppException
	 */
	public static function prepare(string $phone): string {
		$hasPlus = $phone[0] === "+";
		$onlyDigits = preg_replace('/[^0-9]/', '', $phone);

		if (empty($onlyDigits)) {
			throw new AppException('Phone is invalid');
		}

		if (!$hasPlus) { // Если телефон не международный, то считаем, что номер Российский
			$onlyDigits = '7' . $onlyDigits;
		}

		return $onlyDigits;
	}

	public static function fromNumber(string $number): Phone {
		$phone = new Phone();
		$phone->phone = self::prepare($number);

		return $phone;
	}

	/**
	 * @param string $prefix
	 * @return array
	 * @throws ModelException
	 * @throws DbException
	 * @throws AppException
	 */
	public static function findByPrefix(string $prefix): array {
		$db = Db::get();
		$prepared = self::prepare($prefix);

		$queryText = <<<QUERY
select concat('+', p.phone) as phone, count(r.review) as reviews_count from phones p
left join reviews r on p.id = r.phone_id
where phone like ?
group by p.phone
order by reviews_count desc
QUERY;
		$p = $db->prepare($queryText);
		if (!$p) {
			throw new DbException($db->error);
		}

		$prepared .= "%"; // для поиска
		$p->bind_param("s", $prepared);
		if (!$p->execute()) {
			throw new DbException($db->error);
		}

		$q = $p->get_result();

		$t = $q->fetch_all(MYSQLI_ASSOC);
		foreach ($t as &$phone) { // mysql moment
			$phone["reviews_count"] = (int)$phone["reviews_count"];
		}

		return $t;
	}

	public function fillFromRow(array $row): void {
		$this->id = (int)$row["id"];
		$this->phone = $row["phone"];
	}

	protected function prepareInsert(): mysqli_stmt {
		$db = Db::get();

		$p = $db->prepare("insert into phones (phone) values (?)");
		$p->bind_param("s", $this->phone);

		return $p;
	}

	protected function afterInsert(): void {
		$db = Db::get();

		$this->id = $db->insert_id;
	}

	/**
	 * @throws ModelException
	 */
	public function loadByPhoneOrCreate(): void {
		try {
			$this->loadByPhone();
			return; // телефон нашелся, id подтянули
		} catch (ModelNotFoundException $e) {
		}

		$this->insert(); // добавляем номер раз он не существует
	}

	/**
	 * @throws ModelNotFoundException
	 * @throws DbException
	 */
	public function loadByPhone(): void {
		$db = Db::get();

		$p = $db->prepare("select * from phones where phone = ?");
		if (!$p) {
			throw new ModelNotFoundException();
		}

		$p->bind_param("s", $this->phone);

		if (!$p->execute()) {
			throw new DbException($db->error);
		}

		$q = $p->get_result();
		if ($q->num_rows === 0) {
			throw new ModelNotFoundException("Phone not found", ErrorCodes::PHONE_NOT_FOUND);
		}

		$row = $q->fetch_assoc();
		$this->fillFromRow($row);
	}

}