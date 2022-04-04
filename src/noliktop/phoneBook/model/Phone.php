<?php

namespace noliktop\phoneBook\model;

use mysqli;
use mysqli_stmt;
use noliktop\phoneBook\db\Db;
use noliktop\phoneBook\exception\AppException;
use noliktop\phoneBook\exception\HTTPCodes;
use noliktop\phoneBook\exception\ValidationException;

class Phone extends Model
{

	public int $id;
	public string $phone;

	public static function prepare(string $phone): string{
		$hasPlus = $phone[0] === "+";
		$onlyDigits = preg_replace('/[^0-9]/', '', $phone);

		if(!$hasPlus){ // Если телефон не международный, то считаем, что номер Российский
			$onlyDigits = '7' . $onlyDigits;
		}

		return $onlyDigits;
	}

	/**
	 * @param string $prefix
	 * @return array
	 * @throws ModelException
	 */
	public static function findByPrefix(string $prefix): array{
		$db = Db::get();
		$prepared = self::prepare($prefix);

		$queryText = <<<QUERY
select concat('+', p.phone) as phone, count(r.review) as reviews_count from phones p
left join reviews r on p.id = r.phone_id
where phone like '$prepared%'
group by p.phone
QUERY;
		$q = $db->query($queryText);
		if(!$q){
			throw new ModelException("Db error", HTTPCodes::INTERNAL_SERVER_ERROR);
		}

		return $q->fetch_all(MYSQLI_ASSOC);
	}

	public static function fromRow(array $row): self
	{
		$phone = new Phone();
		$phone->id = (int)$row["id"];
		$phone->phone = $row["phone"];

		return $phone;
	}

	protected function prepareInsert(): mysqli_stmt
	{
		$db = Db::get();

		$p = $db->prepare("insert into phones (phone) values (?)");
		$p->bind_param("s", $this->phone);

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

		$p = $db->prepare("update phones set phone = ? where id = ?");
		$p->bind_param("si", $this->phone, $this->id);

		return $p;
	}

}