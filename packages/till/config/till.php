<?php

use ArtisanBuild\Till\Enums\PaymentProcessors;
use ArtisanBuild\Till\Models\TillTeam;
use ArtisanBuild\Till\Models\TillUser;

return [
    'payment_processor' => PaymentProcessors::Demo,
    'team_mode' => true,
    'team_model' => TillTeam::class,
    'user_model' => TillUser::class,
    'live_or_test' => env('TILL_LIVE_OR_TEST'), // null means that we use the live price in production and test price everywhere else
    'subscribe_uri' => env('TILL_SUBSCRIBE_URI', 'subscribe'),
    'pricing_uri' => env('TILL_PRICING_URI', 'pricing'),
    'plans_uri' => env('TILL_PLANS_URI', 'plans'),
    'default_display' => env('TILL_DEFAULT_DISPLAY', 'year'),
    'always_show_lifetime' => true,
    'plan_path' => base_path('packages/till/src/SubscriptionPlans'),
    'pricing_section_template' => 'till::livewire.pricing_section',
    'active_feature_icon' => null,
    'inactive_feature_icon' => null,

];
