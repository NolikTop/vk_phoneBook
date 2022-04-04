<?php

namespace noliktop\phoneBook\country;

use noliktop\phoneBook\model\Phone;

class CountryRecogniser {

	protected static array $countries = [];

	protected static function init() {
		self::reg("7", "RU");
		self::reg("1", "US");
		self::reg("86", "CH");
		self::reg("52", "MX");
		self::reg("1905", "MX");

		self::sort();
	}

	protected static function reg(string $prefix, string $code): void {
		self::$countries[] = [$prefix, $code];
	}

	protected static function sort(): void {
		usort(self::$countries, fn(array $code1, array $code2) => strlen($code2[0]) - strlen($code1[0]));
	}

	/**
	 * @throws CountryException
	 */
	public static function getCountryCodeFor(Phone $phone): string {
		if (empty(self::$countries)) {
			self::init();
		}

		foreach (self::$countries as [$prefix, $code]) {
			if (strpos($phone->phone, $prefix) === 0) {
				return $code;
			}
		}

		throw new CountryException("No country for phone number +$phone->phone");
	}

}