<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureScreenAccess
{
    /**
     * Ensure the authenticated user's role grants access to the given screen.
     *
     * Usage in routes: ->middleware('screen:clients.view')
     */
    public function handle(Request $request, Closure $next, string $key): Response
    {
        $user = $request->user();

        if (! $user || ! $user->hasPermission($key)) {
            abort(403);
        }

        return $next($request);
    }
}
