<?php

declare(strict_types=1);


namespace noliktop\phoneBook\cache;


trait UseCache {

	/**
	 * @param string $key
	 * @param int $time
	 * @param callable $generate
	 * @return mixed
	 */
	public function remember(string $key, int $time, callable $generate){
		// todo если успею завезти редиc, а пока так))
		return $generate();
	}

	/**
	 * @param bool $cond
	 * @param string $key
	 * @param int $time
	 * @param callable $generate
	 * @return mixed
	 */
	public function rememberIf(bool $cond, string $key, int $time, callable $generate){
		// todo если успею завезти редиc, а пока так))
		return $generate();
	}

}