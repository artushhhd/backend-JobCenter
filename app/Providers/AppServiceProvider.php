<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        RateLimiter::for('login', fn (Request $request) => Limit::perMinute(5)
            ->by(mb_strtolower((string) $request->input('email')).'|'.$request->ip())
            ->response(fn () => response()->json(['message' => 'Too many login attempts. Please try again later.'], 429))
        );

        RateLimiter::for('register', fn (Request $request) => Limit::perMinute(10)
            ->by(mb_strtolower((string) $request->input('email')).'|'.$request->ip())
            ->response(fn () => response()->json(['message' => 'Too many registration attempts. Please try again later.'], 429))
        );

        RateLimiter::for('job-writes', fn (Request $request) => Limit::perMinute(30)
            ->by((string) ($request->user()?->id ?? 'guest').'|'.$request->ip())
            ->response(fn () => response()->json(['message' => 'You are acting too fast. Please wait a minute.'], 429))
        );
    }
}
