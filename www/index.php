<?php

declare(strict_types=1);

namespace App;

use App\Container;

require '../vendor/autoload.php';

\Sentry\init([
    'dsn' => getenv('SENTRY_DSN'),
    'environment' => 'development',
    // Specify a fixed sample rate
    'traces_sample_rate' => 1.0,
    // Set a sampling rate for profiling - this is relative to traces_sample_rate
    'profiles_sample_rate' => 1.0,
]);

// all exceptions will be sent to Sentry and then rethrown
set_exception_handler(function ($exception) {
    \Sentry\captureException($exception);
    throw $exception;
});

$container = new Container();
$app = $container->get("App");
$app->run();
