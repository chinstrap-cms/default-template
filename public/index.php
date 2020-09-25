<?php

declare(strict_types=1);

use Chinstrap\Core\Exceptions\ErrorHandler;
use Chinstrap\Core\Http\Middleware\ClockworkMiddleware;
use Chinstrap\Core\Http\Middleware\HttpCachingProxyMiddleware;
use Chinstrap\Core\Http\Middleware\NotFoundMiddleware;
use Chinstrap\Core\Http\Middleware\RoutesMiddleware;
use Chinstrap\Core\Http\Middleware\WhoopsMiddleware;
use Chinstrap\Core\Kernel\Kernel;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Laminas\HttpHandlerRunner\RequestHandlerRunner;
use Laminas\Stratigility\MiddlewarePipe;
use League\Container\Container;
use League\Container\ReflectionContainer;

require_once __DIR__ . '/../bootstrap.php';

if (!defined('PUBLIC_DIR')) {
    define('PUBLIC_DIR', __DIR__);
}

$app = new MiddlewarePipe();

$kernel = new Kernel();
$kernel->bootstrap();
$container = $kernel->getContainer();

$app->pipe($container->get(WhoopsMiddleware::class));
$app->pipe($container->get(ClockworkMiddleware::class));
$app->pipe($container->get(HttpCachingProxyMiddleware::class));
$app->pipe($container->get(RoutesMiddleware::class));
$app->pipe($container->get(NotFoundMiddleware::class));

$server = new RequestHandlerRunner(
    $app,
    new SapiEmitter(),
    static function () {
        return ServerRequestFactory::fromGlobals();
    },
    $container->get(ErrorHandler::class)
);

$server->run();
