<?php

namespace noliktop\phoneBook\country;

use noliktop\phoneBook\model\Phone;

class CountryRecogniser
{

	protected static array $countries = [];

	protected static function init()
	{
		self::reg("7", "RU");
		self::reg("1", "US");
		self::reg("86", "CH");
		self::reg("52", "MX");
		self::reg("1905", "MX");
	}

	protected static function reg(string $prefix, string $code): void{
		self::$countries[$prefix] = $code;
	}

	public static function getCountryCodeFor(Phone $phone): string
	{
		if (empty(self::$countries)) {
			self::init();
		}

		return "RU"; // todo
	}

}