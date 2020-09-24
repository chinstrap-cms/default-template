<?php

declare(strict_types=1);

use Chinstrap\Core\Http\Middleware\RoutesMiddleware;
use Laminas\Diactoros\ResponseFactory;
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

$container = new Container();
$container->delegate(
    new ReflectionContainer()
);
$providers = [
              'Chinstrap\Core\Providers\ContainerProvider',
              'Chinstrap\Core\Providers\CacheProvider',
              'Chinstrap\Core\Providers\ClockworkProvider',
              'Chinstrap\Core\Providers\ConfigProvider',
              'Chinstrap\Core\Providers\ConsoleProvider',
              'Chinstrap\Core\Providers\EventProvider',
              'Chinstrap\Core\Providers\FlysystemProvider',
              'Chinstrap\Core\Providers\FormsProvider',
              'Chinstrap\Core\Providers\HandlerProvider',
              'Chinstrap\Core\Providers\LoggerProvider',
              'Chinstrap\Core\Providers\RouterProvider',
              'Chinstrap\Core\Providers\SessionProvider',
              'Chinstrap\Core\Providers\SitemapGeneratorProvider',
              'Chinstrap\Core\Providers\SourceProvider',
              'Chinstrap\Core\Providers\TwigProvider',
              'Chinstrap\Core\Providers\TwigLoaderProvider',
              'Chinstrap\Core\Providers\ViewProvider',
              'Chinstrap\Core\Providers\YamlProvider',
              'Chinstrap\Core\Providers\MailerProvider',
              'Chinstrap\Core\Providers\GlideProvider',
             ];

foreach ($providers as $provider) {
    $container->addServiceProvider($provider);
}
$container->share('response', \Laminas\Diactoros\Response::class);
$container->share('Psr\Http\Message\ResponseInterface', \Laminas\Diactoros\Response::class);

$app->pipe($container->get(RoutesMiddleware::class));

$server = new RequestHandlerRunner(
	$app,
	new SapiEmitter(),
	static function () {
		return ServerRequestFactory::fromGlobals();
	},
	static function (\Throwable $e) {
		$response = (new ResponseFactory())->createResponse(500);
		$response->getBody()->write(sprintf(
			'An error occurred: %s',
			$e->getMessage
		));
		return $response;
	}
);

$server->run();
