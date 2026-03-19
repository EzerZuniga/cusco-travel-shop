<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomeTest extends TestCase
{
    public function test_home_page_returns_success()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
