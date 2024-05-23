<?php

declare(strict_types=1);

namespace App;

use App\Container;

require '../vendor/autoload.php';

$container = new Container();
$app = $container->get("App");
$app->run();
