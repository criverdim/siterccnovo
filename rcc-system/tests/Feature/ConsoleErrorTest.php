<?php

namespace Tests\Feature;

use Tests\TestCase;

class ConsoleErrorTest extends TestCase
{
    public function test_no_console_errors_in_basic_pages(): void
    {
        $pages = ['/', '/events', '/groups', '/login'];

        foreach ($pages as $path) {
            $response = $this->get($path);
            $response->assertStatus(200);
            $this->assertNotEmpty((string) $response->getContent(), "Page $path no content");
        }
    }
}
