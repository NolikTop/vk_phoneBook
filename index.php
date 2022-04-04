<?php

declare(strict_types=1);

use noliktop\phoneBook\App;

include "autoload.php";

$app = new App();
$app->handleRequest();