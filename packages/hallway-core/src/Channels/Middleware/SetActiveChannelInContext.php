<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Channels\Middleware;

use Closure;
use Context;
use Illuminate\Http\Request;

class SetActiveChannelInContext
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->routeIs('livewire.update')) {
            return $next($request);
        }
        if ($channel = $request->route('channel')) {
            Context::add('channel', $channel);
        } else {
            Context::forget('channel');
        }

        return $next($request);
    }
}
