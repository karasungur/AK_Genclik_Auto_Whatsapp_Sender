<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = config('app.locale', 'tr');

        App::setLocale($locale);
        Carbon::setLocale($locale);

        $this->setPhpLocale($locale);

        return $next($request);
    }

    private function setPhpLocale(string $locale): void
    {
        $variants = [$locale];

        if ($locale === 'tr') {
            $variants = array_merge([
                'tr_TR.UTF-8',
                'tr_TR',
                'turkish',
            ], $variants);
        } else {
            $variants = array_merge([
                sprintf('%s_%s.UTF-8', $locale, strtoupper($locale)),
                sprintf('%s_%s', $locale, strtoupper($locale)),
            ], $variants);
        }

        @setlocale(LC_ALL, ...$variants);
        @setlocale(LC_TIME, ...$variants);
    }
}
