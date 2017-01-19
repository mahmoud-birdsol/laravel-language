<?php

use BirdSolutions\Language\Test\TestCase;

class LanguageMiddlewareTest extends TestCase
{
    protected $baseUrl = 'http://localhost';

    /** @test */
    public function it_changes_the_locale_from_route()
    {
        $this->call('GET', $this->baseUrl . '/ar/about');

        $this->assertTrue($this->currentUri == $this->baseUrl . '/ar/about');

        $this->assertTrue('ar' == app()->getLocale());
    }

    /** @test */
    public function it_redirects_to_a_localized_route_if_locale_is_not_specified()
    {
        $this->call('GET', $this->baseUrl . '/about');

        $this->assertRedirectedTo($this->baseUrl . '/en/about');

        $this->assertTrue('en' == app()->getLocale());
    }

    /** @test */
    public function it_sets_the_locale_from_the_uri()
    {
        app()->setLocale('en');

        $this->call('GET', $this->baseUrl . '/ar/about');

        $this->assertTrue($this->currentUri == $this->baseUrl . '/ar/about');

        $this->assertTrue('ar' == app()->getLocale());
    }

    /** @test */
    public function it_defaults_to_session_local_if_set()
    {
        session()->put('locale', 'ar');

        $this->call('GET', $this->baseUrl . '/about');

        $this->assertRedirectedTo($this->baseUrl . '/ar/about');

        $this->assertTrue('ar' == app()->getLocale());
    }

    /** @test */
    public function it_defaults_to_default_locale_if_not_in_uri_or_session()
    {
        $this->call('GET', $this->baseUrl . '/about');

        $this->assertRedirectedTo($this->baseUrl . '/en/about');

        $this->assertTrue('en' == app()->getLocale());
    }

    /** @test */
    public function it_routes_home_page_to_default_locale()
    {
        $this->call('GET', $this->baseUrl . '/');

        $this->assertRedirectedTo($this->baseUrl . '/en');

        $this->assertTrue('en' == app()->getLocale());
    }

    public function it_keeps_the_route_input()
    {
        // TODO
    }

    public function it_sends_posts_request_correctly_without_redirects()
    {
        // TODO
    }

    public function it_sends_put_request_correctly_without_redirects()
    {
        // TODO
    }

    public function it_sends_patch_request_correctly_without_redirects()
    {
        // TODO
    }

    public function it_sends_delete_request_correctly_without_redirects()
    {
        // TODO
    }
}