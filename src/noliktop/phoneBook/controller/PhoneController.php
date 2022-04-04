<?php

declare(strict_types=1);


namespace noliktop\phoneBook\controller;


use noliktop\phoneBook\country\CountryRecogniser;
use noliktop\phoneBook\exception\ValidationException;
use noliktop\phoneBook\model\Phone;
use noliktop\phoneBook\response\IResponse;

class PhoneController extends Controller {

	public function getName(): string {
		return 'phone';
	}

	public function search(array $args): IResponse {
		$phone = $args['phone'] ?? '';
		if(empty($phone)){
			throw new ValidationException('phone');
		}

		return $this->response(Phone::findByPrefix($phone));
	}

	public function getCountry(array $args): IResponse{
		$phone = $args['phone'] ?? '';
		if(empty($phone)){
			throw new ValidationException('phone');
		}

		return $this->response(CountryRecogniser::getCountryCodeFor());
	}

}