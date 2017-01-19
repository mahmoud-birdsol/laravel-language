<?php

namespace BirdSolutions\Language;

class Language
{
    public static function getLocale()
    {
        if(request()->segment(1) == 'ar'){
            dd('here');
        }
        return array_key_exists(request()->segment(1), config('language.available_locales')) ? request()->segment(1) : null;
    }
}