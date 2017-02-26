<?php

namespace BirdSolutions\Language\Middleware;

use BirdSolutions\Language\Language;
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
    protected $language;

    /**
     * LanguageMiddleware constructor.
     */
    public function __construct()
    {
        $this->language = new Language();
    }

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
            return $this->language->hasNoRouteLocale($request);
        }

        return $this->language->hasRouteLocale($request, $next);
    }
}
