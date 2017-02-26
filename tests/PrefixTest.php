<?php

use BirdSolutions\Language\Facades\Language;
use BirdSolutions\Language\Test\TestCase;

class PrefixTest extends TestCase
{
    protected $baseUrl = 'http://localhost';

    /** @test */
    public function it_will_prefix_the_routes_with_local_if_exists()
    {
        $this->call('GET', $this->baseUrl . '/en/about');
        $this->assertTrue(Language::getLocale() == 'en');
    }

    /** @test */
    public function it_will_return_null_if_the_route_is_not_prefixed()
    {
        $this->call('GET', $this->baseUrl . '/about');
        $this->assertTrue(Language::getLocale() == null);
    }
}