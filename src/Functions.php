<?php

declare(strict_types=1);

if ( ! function_exists('hallway')) {
    function hallway(): void {}
}

if ( ! function_exists('hallway_can')) {
    function hallway_can(string $event)
    {
        return Illuminate\Support\Facades\Auth::user()->hallway_members->first()->role->can($event);
    }
}
