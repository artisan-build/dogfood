<?php

declare(strict_types=1);

namespace ArtisanBuild\Adverbs\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Thunk\Verbs\Event;
use Thunk\Verbs\Facades\Verbs;
use Thunk\Verbs\Metadata;

class GreedyMetadata
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Verbs::createMetadataUsing(fn (Metadata $metadata, Event $event) => [
            'request_headers' => $request->headers->all(),
            'user' => $request->user()?->toArray(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return $next($request);
    }
}
