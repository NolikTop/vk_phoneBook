<?php

declare(strict_types=1);


namespace noliktop\phoneBook\controller;


class PhoneBookControllerFactory extends ControllerFactory {

	public function registerAllControllers(): void {
		$this->register(new PhoneController());
	}

}