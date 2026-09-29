<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_homepage_redirects_to_manufacturers(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/manufacturers');
    }
}
