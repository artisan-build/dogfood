<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Channels\Middleware;

use ArtisanBuild\Hallway\Channels\States\ChannelState;
use ArtisanBuild\Hallway\Messages\States\MessageState;
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
        }
        elseif ($thread = $request->route('message')) {
            Context::add('thread', $thread);
            assert($thread instanceof MessageState);
            Context::add('channel', ChannelState::load($thread->channel_id));
        }
        else {
            Context::forget('channel');
        }

        return $next($request);
    }
}
