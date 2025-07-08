<?php

namespace ArtisanBuild\CodeChatClient;

use ArtisanBuild\CodeChatClient\Contracts\ChatDriverContract;
use ArtisanBuild\CodeChatClient\Drivers\ClaudeCodeDriver;
use Illuminate\Support\Manager;

class ChatManager extends Manager
{
    /**
     * Get the default driver name.
     */
    public function getDefaultDriver(): string
    {
        return $this->config->get('code-chat-client.default', 'claude-code');
    }

    /**
     * Create the Claude Code driver instance.
     */
    protected function createClaudeCodeDriver(): ChatDriverContract
    {
        return new ClaudeCodeDriver;
    }

    /**
     * Register a custom driver creator.
     *
     * @param  mixed  $driver
     * @return $this
     */
    #[\Override]
    public function extend($driver, \Closure $callback)
    {
        $this->customCreators[$driver] = $callback;

        return $this;
    }

    /**
     * Get all available drivers.
     */
    public function getAvailableDrivers(): array
    {
        $drivers = [];

        // Check built-in drivers
        $builtInDrivers = ['claude-code'];

        foreach ($builtInDrivers as $driver) {
            try {
                $instance = $this->driver($driver);
                if ($instance->isAvailable()) {
                    $drivers[$driver] = $instance->getName();
                }
            } catch (\Exception) {
                // Driver not available
            }
        }

        // Check custom drivers
        foreach ($this->customCreators as $driver => $creator) {
            try {
                $instance = $this->driver($driver);
                if ($instance->isAvailable()) {
                    $drivers[$driver] = $instance->getName();
                }
            } catch (\Exception) {
                // Driver not available
            }
        }

        return $drivers;
    }
}
