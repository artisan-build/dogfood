<?php

declare(strict_types=1);

namespace ArtisanBuild\AgentOsInstaller\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to control access to Agent OS documentation viewer
 */
class AgentOsViewerMiddleware
{
    /**
     * Handle an incoming request
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if a custom gate is configured
        $gate = config('agent-os-installer.viewer.gate');

        if ($gate && Gate::has($gate)) {
            // Gates may require a user, so check authorization properly
            if (! Gate::check($gate)) {
                abort(403, 'Unauthorized to access Agent OS documentation.');
            }

            return $next($request);
        }

        // Default behavior: allow localhost, require auth in production
        if (app()->environment('local') || $request->ip() === '127.0.0.1') {
            return $next($request);
        }

        if (! $request->user()) {
            abort(403, 'Authentication required to access Agent OS documentation.');
        }

        return $next($request);
    }
}
