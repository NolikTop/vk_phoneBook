<?php

declare(strict_types=1);


namespace noliktop\phoneBook\request;


class IncomingRequestFactory {

	public static function getRequestFromGlobals(): IncomingRequest{
		return new IncomingRequest($_GET["path"] ?? "", $_REQUEST);
	}

}