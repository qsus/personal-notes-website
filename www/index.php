<?php

declare(strict_types=1);

require_once __DIR__."/../scripts/Container.php";

$container = new Container();
$app = $container->get("App");
$app->run();
