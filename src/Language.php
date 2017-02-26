<?php

namespace BirdSolutions\Language;

use Closure;

class Language
{
    /**
     * Get the route local.
     *
     * @return null|string
     */
    public static function getLocale()
    {
        return array_key_exists(request()->segment(1), config('language.available_languages')) ? request()->segment(1) : null;
    }

    /**
     * Handle if the request has route locale.
     *
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function hasRouteLocale($request, Closure $next)
    {
        $this->saveLocale($request->segment(1));

        return $next($request);
    }

    /**
     * Handle if the request does not have a route locale.
     *
     * @param $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function hasNoRouteLocale($request)
    {
        if (session()->has('locale')) {
            return $this->hasSessionLocale($request);
        }

        return $this->hasNoSessionLocale($request);
    }

    /**
     * Handle if the session has set (choose) locale.
     *
     * @param $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function hasSessionSetLocale($request)
    {
        $this->saveLocale(session()->get('setLocale'));

        $uri = $this->getUri($request->segments(), session()->get('setLocale'), $request->all());

        session()->forget('setLocale');

        return redirect($uri);
    }

    /**
     * Handle if there is a locale variable in session.
     *
     * @param $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function hasSessionLocale($request)
    {
        $this->saveLocale(session()->get('locale'));

        return redirect($this->getUri($request->segments(), app()->getLocale(), $request->all()));
    }

    /**
     * Handle if there is no locale variable in session.
     *
     * @param $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function hasNoSessionLocale($request)
    {
        $this->saveLocale(config('language.default_language'));

        return redirect($this->getUri($request->segments(), config('language.default_language'), $request->all()));;
    }

    /**
     * Save the locale to the session and set the app locale.
     *
     * @param $locale
     */
    protected function saveLocale($locale)
    {
        app()->setLocale($locale);
        session()->put('locale', $locale);
    }

    /**
     * Get the redirection uri.
     *
     * @param $segments
     * @param $locale
     * @param array $inputs
     * @return mixed
     */
    protected function getUri($segments, $locale, $inputs = [])
    {
        $uri = implode('/', $this->addLocaleToSegments($segments, $locale));

        if (!empty($inputs)) {
            $uri = $this->addInputsToUri($uri, $inputs);
        }

        return $uri;
    }

    /**
     * Preserve any inputs to uri.
     *
     * @param $uri
     * @param $inputs
     * @return string
     */
    protected function addInputsToUri($uri, $inputs)
    {
        $uri = $uri . '?';

        foreach ($inputs as $key => $value) {
            $uri = $uri . $key . '=' . $value;
        }

        return $uri;
    }

    /**
     * Add the uri to teh segments array.
     *
     * @param $segments
     * @param $locale
     * @return mixed
     */
    protected function addLocaleToSegments($segments, $locale)
    {
        $segments = $this->removeLocaleFromSegments($segments);

        array_unshift($segments, $locale);

        return $segments;
    }

    /**
     * Remove the locale from uri segments.
     *
     * @param $segments
     * @return mixed
     */
    protected function removeLocaleFromSegments($segments)
    {
        if (count($segments) > 0) {
            foreach (config('language.available_languages') as $key => $value) {

                if ($key == $segments[0]) {
                    array_shift($segments);
                    break;
                }
            }
        }

        return $segments;
    }
}