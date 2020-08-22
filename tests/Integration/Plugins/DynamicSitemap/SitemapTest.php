<?php

declare(strict_types=1);

namespace Chinstrap\Tests\Integration\Plugins\DynamicSitemap;

use Chinstrap\Tests\IntegrationTestCase;

final class SitemapTest extends IntegrationTestCase
{
    public function testIndex(): void
    {
        $this->makeRequest('/sitemap')
            ->assertStatusCode(200);
    }
}
