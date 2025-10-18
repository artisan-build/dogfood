<?php

declare(strict_types=1);

namespace ArtisanBuild\CodeChatClient\Facades;

use ArtisanBuild\CodeChatClient\ChatManager;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \ArtisanBuild\CodeChatClient\Contracts\ChatDriverContract driver(?string $driver = null)
 * @method static array getAvailableDrivers()
 * @method static \ArtisanBuild\CodeChatClient\ChatManager extend(string $driver, \Closure $callback)
 *
 * @see ChatManager
 */
class CodeChat extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ChatManager::class;
    }
}
