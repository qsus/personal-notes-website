<?php

declare(strict_types=1);

use App\Container;

require_once __DIR__."/../scripts/Container.php";

$container = new Container();
$app = $container->get("App");
$app->run();
