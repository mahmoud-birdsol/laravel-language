<?php

namespace BirdSolutions\Language\Controllers;

use Illuminate\Http\Request;

class LanguageController
{
    /**
     * Change the language.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeLanguage(Request $request)
    {
        if($request->has('language')){

            app()->setLocale($request->input('language'));
            session()->put('setLocale', trim($request->input('language')));

            return redirect()->back();
        }

        return redirect()->back();
    }
}