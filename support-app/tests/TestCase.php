<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Symfony's fake requests default to "en-us"; browse like the Dutch-speaking majority of clients.
        $this->withHeader('Accept-Language', 'nl-BE,nl;q=0.9');
    }
}
