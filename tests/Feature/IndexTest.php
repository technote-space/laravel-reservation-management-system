<?php
declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Class IndexTest
 * @package Tests\Feature
 * @group Feature
 */
class IndexTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $this->assertStringContainsString('/js/app.js', $response->content());
        $this->assertStringContainsString('/css/app.css', $response->content());
        $this->assertStringContainsString('<div id="app"></div>', $response->content());
    }

    public function testIndex2()
    {
        $response = $this->get('__test__');
        $response->assertStatus(200);
        $this->assertStringContainsString('/js/app.js', $response->content());
        $this->assertStringContainsString('/css/app.css', $response->content());
        $this->assertStringContainsString('<div id="app"></div>', $response->content());
    }
}
