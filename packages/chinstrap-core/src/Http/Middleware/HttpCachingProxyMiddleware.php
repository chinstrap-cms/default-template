<?php

declare(strict_types=1);

namespace Chinstrap\Core\Http\Middleware;

use Http\Message\StreamFactory\DiactorosStreamFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use PublishingKit\HttpProxy\Client;

final class HttpCachingProxyMiddleware implements MiddlewareInterface
{
    /**
     * @var CacheItemPoolInterface
     */
    private $cache;

    public function __construct(CacheItemPoolInterface $cache)
    {
        $this->cache = $cache;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $client = new Client(function ($request) {
            return $handler->handle($request);
        });
        $proxy = new Proxy($client, $this->cache, new DiactorosStreamFactory());
        return $proxy->handle($request);
    }
}
