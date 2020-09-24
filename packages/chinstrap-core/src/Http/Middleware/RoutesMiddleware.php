<?php

declare(strict_types=1);

namespace Chinstrap\Core\Http\Middleware;

use League\Route\Router;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class RoutesMiddleware implements MiddlewareInterface
{
    /**
     * @var Router
     */
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (getenv('APP_ENV') == 'development') {
            $this->router->get('/__clockwork/{request:.+}', 'Chinstrap\Core\Http\Controllers\ClockworkController::process');
        }
        $this->router->get('/images/[{name}]', 'Chinstrap\Core\Http\Controllers\ImageController::get');
        $this->router->get('/[{name:[a-zA-Z0-9\-\/]+}]', 'Chinstrap\Core\Http\Controllers\MainController::index')
               ->middleware(new \Chinstrap\Core\Http\Middleware\HttpCache())
               ->middleware(new \Chinstrap\Core\Http\Middleware\ETag());
        $this->router->post('/[{name:[a-zA-Z0-9\-\/]+}]', 'Chinstrap\Core\Http\Controllers\MainController::submit');
        try {
            $response = $this->router->dispatch($request);
        } catch (\League\Route\Http\Exception\NotFoundException $e) {
            $view = $this->container->get('Chinstrap\Core\Contracts\Views\Renderer');
            $response = $view->render(
                $this->container->get('response')->withStatus(404),
                '404.html'
            );
        }
        return $response;
    }
}
