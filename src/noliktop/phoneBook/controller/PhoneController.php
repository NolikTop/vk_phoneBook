<?php

declare(strict_types=1);


namespace noliktop\phoneBook\controller;


use noliktop\phoneBook\country\CountryRecogniser;
use noliktop\phoneBook\exception\AppException;
use noliktop\phoneBook\exception\AuthException;
use noliktop\phoneBook\exception\DbException;
use noliktop\phoneBook\exception\ValidationException;
use noliktop\phoneBook\model\ModelException;
use noliktop\phoneBook\model\ModelNotFoundException;
use noliktop\phoneBook\model\Phone;
use noliktop\phoneBook\model\Review;
use noliktop\phoneBook\model\ReviewMark;
use noliktop\phoneBook\model\Token;
use noliktop\phoneBook\response\IResponse;

class PhoneController extends Controller {

	public function getName(): string {
		return 'phone';
	}

	/**
	 * @throws ValidationException
	 */
	public function getCountry(array $args): IResponse {
		$phone = $args['phone'] ?? '';
		if (empty($phone)) {
			throw new ValidationException('phone');
		}

		$phone = Phone::fromNumber($phone);

		$countryCode = CountryRecogniser::getCountryCodeFor($phone);
		return $this->response([
			'country' => $countryCode
		]);
	}

	/**
	 * @throws ValidationException
	 * @throws ModelException
	 * @throws AppException
	 */
	public function search(array $args): IResponse {
		$phone = $args['phone'] ?? '';
		if (empty($phone)) {
			throw new ValidationException('phone');
		}

		$allPhones = Phone::findByPrefix($phone);
		return $this->response($allPhones);
	}

	const MAX_COUNT = 100;

	/**
	 * @throws ValidationException
	 * @throws ModelException
	 * @throws ModelNotFoundException
	 */
	public function getReviews(array $args): IResponse {
		$phone = $args['phone'] ?? '';
		if (empty($phone)) {
			throw new ValidationException('phone');
		}

		$count = (int)($args['count'] ?? 0);
		if ($count <= 0) {
			throw new ValidationException('count');
		}

		if ($count > self::MAX_COUNT) {
			throw new ValidationException('count', 'Max count is ' . self::MAX_COUNT);
		}

		$offset = (int)($args['offset'] ?? -1);
		if ($offset < 0) {
			throw new ValidationException('offset');
		}

		$phone = Phone::fromNumber($phone);
		$phone->loadByPhone();

		$reviews = Review::getAllForPhone($phone, $count, $offset);
		return $this->response($reviews);
	}

	/**
	 * @param array $args
	 * @return IResponse
	 * @throws ModelException
	 * @throws ValidationException
	 * @throws AuthException
	 * @throws DbException
	 */
	public function addReview(array $args): IResponse {
		$phone = $args['phone'] ?? '';
		if (empty($phone)) {
			throw new ValidationException('phone');
		}

		$reviewText = $args['review'] ?? '';
		if (empty($reviewText)) {
			throw new ValidationException('review');
		}

		$access_token = $args['access_token'] ?? '';
		$userId = null;
		if (!empty($access_token)) {
			$token = Token::get($access_token);
			$userId = $token->userId;
		}

		$phone = Phone::fromNumber($phone);
		$phone->loadByPhoneOrCreate();

		$review = new Review();
		$review->userId = $userId;
		$review->review = $reviewText;
		$review->phoneId = $phone->id;
		$review->insert();

		return $this->response([
			'review_id' => $review->id
		]);
	}

	/**
	 * @throws DbException
	 * @throws ValidationException
	 * @throws AuthException
	 */
	public function rateReview(array $args): IResponse {
		$reviewId = (int)($args['review_id'] ?? 0);
		if ($reviewId <= 0) {
			throw new ValidationException('review_id');
		}

		$mark = (int)($args['mark'] ?? 0);
		if ($mark === 0) {
			throw new ValidationException('mark');
		}

		if ($mark !== 1 && $mark !== -1) {
			throw new ValidationException('mark should be 1 or -1');
		}

		$accessToken = $args['access_token'] ?? '';
		if (empty($accessToken)) {
			throw new ValidationException('access_token');
		}
		$token = Token::get($accessToken);
		$userId = $token->userId;

		$reviewMark = new ReviewMark();
		$reviewMark->userId = $userId;
		$reviewMark->reviewId = $reviewId;
		$reviewMark->mark = $mark;
		$reviewMark->insertOrUpdate();

		$newRating = Review::fromId($reviewId)->getRating();
		return $this->response([
			'new_rating' => $newRating
		]);
	}

}