<?php

namespace ArtisanBuild\Turbulence\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetDefaultsForAccountSessionUrls
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! config('turbulence.account_session_urls.enabled')) {
            return $next($request);
        }

        URL::defaults([
            'account_identifier' => $request->route('account_identifier', config('turbulence.account_session_urls.index_key')),
            'account_id' => $request->route('account_id', 0),
        ]);

        return $next($request);
    }
}
