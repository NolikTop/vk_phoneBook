<?php

namespace noliktop\phoneBook\model;

use mysqli;
use mysqli_stmt;
use noliktop\phoneBook\db\Db;

abstract class Model {

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

	/**
	 * @throws ModelException
	 */
	public function update(): void {
		$q = $this->prepareUpdate();

		if (!$q->execute()) {
			throw new ModelException(Db::get()->error);
		}
	}

	abstract protected function prepareUpdate(): mysqli_stmt;

}