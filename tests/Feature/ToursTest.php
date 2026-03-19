<?php

namespace Tests\Feature;

use Tests\TestCase;

class ToursTest extends TestCase
{
    public function test_tours_page_returns_success()
    {
        $response = $this->get('/tours');
        $response->assertStatus(200);
    }
}
