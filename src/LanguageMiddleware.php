<?php

namespace BirdSolutions\Language;

use Closure;

/**
 * Class LocaleMiddleware
 *
 * Will handle a request with a route local prefix and set the app local to it
 * if it does not exists in supported locals it will assume it is just a route
 * and will attempt to load a locale saved in the session and as a last resort
 * it will fetch from to the configuration setup and search for the default
 * locale and attempt to redirect to that route with the selected locale.
 *
 * It will also preserve uri input and send it back to the reroute.
 */
class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $request->isMethod('get')) {
            return $next($request);
        }

        if (!array_key_exists($request->segment(1), config('language.available_locales'))) {
            return $this->hasNoRouteLocale($request);
        }

        return $this->hasRouteLocale($request, $next);
    }

    /**
     * Handle if the request has route locale.
     *
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    private function hasRouteLocale($request, Closure $next)
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
    private function hasNoRouteLocale($request)
    {
        if (session()->has('locale')) {
            return $this->hasSessionLocale($request);
        }

        return $this->hasNoSessionLocale($request);
    }

    /**
     * Handle if there is a locale variable in session.
     *
     * @param $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    private function hasSessionLocale($request)
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
    private function hasNoSessionLocale($request)
    {
        $this->saveLocale(config('language.default_locale'));

        return redirect($this->getUri($request->segments(), config('language.default_locale'), $request->all()));;
    }

    /**
     * Save the locale to the session and set the app locale.
     *
     * @param $locale
     */
    private function saveLocale($locale)
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
    private function getUri($segments, $locale, $inputs = [])
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
    private function addInputsToUri($uri, $inputs)
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
    private function addLocaleToSegments($segments, $locale)
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
    private function removeLocaleFromSegments($segments)
    {
        if (count($segments) > 0) {
            foreach (config('language.available_locales') as $key => $value) {

                if ($key == $segments[0]) {
                    array_shift($segments);
                    break;
                }
            }
        }

        return $segments;
    }
}
