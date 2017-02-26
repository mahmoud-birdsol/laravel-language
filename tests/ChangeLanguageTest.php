<?php

use BirdSolutions\Language\Test\TestCase;

class ChangeLanguageTest extends TestCase
{
    protected $baseUrl = 'http://localhost';

    /** @test */
    public function it_sets_the_selected_language_with_a_get_request()
    {
        app()->setLocale('en');

        $this->call('GET', $this->baseUrl . '/en/language?language=ar');

        $this->assertRedirectedTo($this->baseUrl);

        $this->assertTrue('ar' == app()->getLocale());
    }

    /** @test */
    public function it_sets_the_selected_language_with_a_post_request()
    {
        app()->setLocale('en');

        $this->call('POST', $this->baseUrl . '/en/language', [
            'language' => 'ar',
        ]);

        $this->assertRedirectedTo($this->baseUrl);

        $this->assertTrue('ar' == app()->getLocale());
    }
}