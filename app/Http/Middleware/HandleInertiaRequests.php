<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'rondas.app';

    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'appName' => config('app.name'),
            'flash' => fn () => ['success' => $request->session()->get('success')],
        ];
    }

    public function handle(Request $request, Closure $next)
    {
        $response = parent::handle($request, $next);

        // Blade screens (including login after session expiry) need a full visit.
        if ($request->header('X-Inertia') && $response->isOk()
            && !$response->headers->has('X-Inertia')
            && str_contains($response->headers->get('Content-Type', ''), 'text/html')) {
            return Inertia::location($request->fullUrl());
        }

        return $response;
    }
}
