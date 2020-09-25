<?php

declare(strict_types=1);

use Chinstrap\Core\Exceptions\ErrorHandler;
use Chinstrap\Core\Kernel\AppFactory;
use Laminas\Diactoros\ResponseFactory;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Laminas\HttpHandlerRunner\RequestHandlerRunner;

require_once __DIR__ . '/../bootstrap.php';

if (!defined('PUBLIC_DIR')) {
    define('PUBLIC_DIR', __DIR__);
}

$factory = new AppFactory();
$app = $factory();

$server = new RequestHandlerRunner(
    $app,
    new SapiEmitter(),
    static function () {
        return ServerRequestFactory::fromGlobals();
    },
    new ErrorHandler(new ResponseFactory())
);

$server->run();
