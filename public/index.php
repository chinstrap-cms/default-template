<?php

declare(strict_types=1);

use Chinstrap\Core\Exceptions\ErrorHandler;
use Chinstrap\Core\Kernel\AppFactory;
use Chinstrap\Core\Kernel\CachingRequestHandlerDecorator;
use Chinstrap\Core\Kernel\ContainerFactory;
use Laminas\Diactoros\ResponseFactory;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Laminas\HttpHandlerRunner\RequestHandlerRunner;
use PublishingKit\Cache\Factories\StashCacheFactory;
use PublishingKit\Config\Config;

require_once __DIR__ . '/../bootstrap.php';

if (!defined('PUBLIC_DIR')) {
    define('PUBLIC_DIR', __DIR__);
}

$factory = new AppFactory((new ContainerFactory())());
if ($_ENV['CACHE_PROXY'] === true) {
    $config = new Config([
        'driver' => 'filesystem',
        'path' => 'cache/proxy',
    ]);
    $app = new CachingRequestHandlerDecorator(
        $factory(),
        (new StashCacheFactory())->make($config->toArray())
    );
} else {
    $app = $factory();
}

$server = new RequestHandlerRunner(
    $app,
    new SapiEmitter(),
    static function () {
        return ServerRequestFactory::fromGlobals();
    },
    new ErrorHandler(new ResponseFactory())
);

$server->run();
