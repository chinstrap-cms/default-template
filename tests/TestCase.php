<?php

declare(strict_types=1);

namespace Chinstrap\Tests;

use Chinstrap\Core\Kernel\AppFactory;
use Chinstrap\Core\Kernel\ContainerFactory;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * Container instance
     *
     * @var ContainerInterface|null
     */
    protected $container;

    /**
     * App instance
     *
     * @var MiddlewarePipe|null
     */
    protected $app;

    public function setUp(): void
    {
        if (!defined('ROOT_DIR')) {
            define('ROOT_DIR', __DIR__ . '/../');
        }
        if (!defined('CONTENT_PATH')) {
            define('CONTENT_PATH', 'content/');
        }
        if (!defined('PUBLIC_DIR')) {
            define('PUBLIC_DIR', __DIR__ . '/../public/');
        }
        $this->container = (new ContainerFactory())();
        $factory = new AppFactory($this->container);
        $this->app = $factory();
    }

    public function tearDown(): void
    {
        $this->app = null;
        $this->container = null;
    }
}
