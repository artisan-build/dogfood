<?php

use ArtisanBuild\Till\Enums\PaymentProcessors;

return [
    'payment_processor' => PaymentProcessors::Demo,
    'team_mode' => true,
    'team_model' => \App\Models\Team::class,
    'user_model' => \App\Models\User::class,
    'live_or_test' => env('TILL_LIVE_OR_TEST'), // null means that we use the live price in production and test price everywhere else
    'subscribe_uri' => env('TILL_SUBSCRIBE_URI', 'subscribe'),
    'pricing_uri' => env('TILL_PRICING_URI', 'pricing'),
    'plans_uri' => env('TILL_PLANS_URI', 'plans'),
    'default_display' => env('TILL_DEFAULT_DISPLAY', 'year'),
    'plan_path' => app_path('Plans'),
    'pricing_section_template' => 'till::livewire.pricing_section',
    'active_feature_icon' => null,
    'inactive_feature_icon' => null,

];
