<?php

declare(strict_types=1);


namespace noliktop\phoneBook\model;


use mysqli_stmt;
use noliktop\phoneBook\db\Db;

abstract class InsertableModel extends Model {

	/**
	 * @throws ModelException
	 */
	public function insert(): void {
		$q = $this->prepareInsert();

		if (!$q->execute()) {
			throw new ModelException(Db::get()->error);
		}

		$this->afterInsert();
	}

	abstract protected function prepareInsert(): mysqli_stmt;

	abstract protected function afterInsert(): void;

}