<?php

declare(strict_types=1);


namespace noliktop\phoneBook\exception;


interface ErrorCodes {

	const WRONG_TYPE = 1;
	const WRONG_TOKEN = 2;
	const NO_VALUE_FOR_PARAMETER = 3;
	const VALUE_IS_TOO_LONG = 4;
	const VALUE_MUST_BE_NOT_NEGATIVE_INTEGER = 5;
	const CONTROLLER_NOT_FOUND = 6;
	const METHOD_NOT_FOUND = 7;
	const PHONE_NOT_FOUND = 8;

	const REVIEW_HAS_BEEN_DELETED_OR_DOESNT_EXIST = 20;

}