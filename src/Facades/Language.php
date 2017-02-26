<?php

namespace BirdSolutions\Language\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \BirdSolutions\Language\Language
 */
class Language extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'language';
    }
}