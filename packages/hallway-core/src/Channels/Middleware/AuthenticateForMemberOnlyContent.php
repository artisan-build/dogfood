<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Channels\Middleware;

use ArtisanBuild\Hallway\Channels\States\ChannelState;
use ArtisanBuild\Hallway\Messages\States\MessageState;
use Closure;
use Illuminate\Auth\Middleware\Authenticate;

// TODO: This duplicates some work because it runs before SubstituteBindings (because it extends Authenticate?)
// Is there a better approach we can take here?
class AuthenticateForMemberOnlyContent extends Authenticate
{
    #[\Override]
    public function handle($request, Closure $next, ...$guards)
    {
        // Allow public channels to be viewed without authentication
        if ($request->route()?->hasParameter('channel')) {
            if (! ChannelState::load($request->route('channel'))->type->isPublicChannel()) {
                $this->authenticate($request, $guards);
            }
        }

        // Allow threads in public channels to be viewed without authentication
        if ($request->route()?->hasParameter('message')) {
            $message = MessageState::load($request->route('message'));
            if (! ChannelState::load($message->channel_id)->type->isPublicChannel()) {
                $this->authenticate($request, $guards);
            }
        }

        return $next($request);
    }
}
