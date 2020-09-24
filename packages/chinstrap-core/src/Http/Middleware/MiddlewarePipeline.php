<?php

declare(strict_types=1);

namespace Chinstrap\Core\Http\Middleware;

use Iterator;

final class MiddlewarePipeline implements Iterator
{
    /**
     * @var string[]
     */
    private $items;

    /**
     * @var int
     */
    private $position;

    public function __construct()
    {
        $this->items = [
            ClockworkMiddleware::class,
            WhoopsMiddleware::class,
            RoutesMiddleware::class,
        ];
        $this->position = 0;
    }

    /**
     * {@inheritDoc}
     */
    public function current()
    {
        return $this->items[$this->position];
    }

    /**
     * {@inheritDoc}
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * {@inheritDoc}
     */
    public function valid()
    {
        return isset($this->items[$this->position]);
    }

    /**
     * {@inheritDoc}
     */
    public function rewind()
    {
        $this->position = 0;
    }
}
