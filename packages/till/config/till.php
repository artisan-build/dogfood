<?php

use ArtisanBuild\Till\Models\TillTeam;
use ArtisanBuild\Till\Models\TillUser;

return [
    'team_mode' => env('TILL_TEAM_MODE', true),
    'team_model' => env('TILL_TEAM_MODEL', TillTeam::class),
    'user_model' => env('TILL_USER_MODEL', TillUser::class),
    'live_or_test' => env('TILL_LIVE_OR_TEST'), // null means that we use the live price in production and test price everywhere else
    'subscribe_uri' => env('TILL_SUBSCRIBE_URI', 'subscribe'),
    'pricing_uri' => env('TILL_PRICING_URI', 'pricing'),
    'default_display' => env('TILL_DEFAULT_DISPLAY', 'year'),
    'show_free_plan' => env('TILL_SHOW_FREE_PLAN', false),
    'plan_path' => __DIR__.'/../src/Plans',
    'pricing_section_template' => 'till::livewire.pricing_section',
];
