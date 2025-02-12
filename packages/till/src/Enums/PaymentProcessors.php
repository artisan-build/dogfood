<?php

namespace ArtisanBuild\Till\Enums;

use ArtisanBuild\Till\Contracts\PlanInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

enum PaymentProcessors: string
{
    case Demo = 'demo'; // A demo mode payment processor that cannot be used in production.
    case Stripe = 'stripe';

    // URL that the user should use to subscribe to the plan
    public function subscribe(PlanInterface $plan): string
    {
        return Route::has("till_{$this->value}.subscribe")
            ? route("till_{$this->value}.subscribe", ['plan' => $plan])
            : '#';
    }

    // URL that the user should use to manage / change / cancel the plan
    public function manage(PlanInterface $plan): string
    {
        return Route::has("till_{$this->value}.manage")
            ? route("till_{$this->value}.manage", ['plan' => $plan])
            : '#';
    }

    public function subscribed(PlanInterface $plan): bool
    {
        return class_exists("ArtisanBuild\Till{$this->name}\Actions\IsSubscribed")
            ? app("ArtisanBuild\Till{$this->name}\Actions\IsSubscribed")
            : false;
    }

    // Returns true if the state was found to be out of sync and an event was fired to bring it back
    // in sync. In theory, this should never return true but if it does it might indicate that we are
    // missing something in our webhook handlers or there's a hole in our logic somewhere.
    public function sync(PlanInterface $plan): bool
    {
        $changed = class_exists("ArtisanBuild\Till{$this->name}\Actions\SyncSubscriptionStatus")
        ? app("ArtisanBuild\Till{$this->name}\Actions\SyncSubscriptionStatus")
        : false;

        if ($changed) {
            Log::alert('We head to fire an event to correct the subscription status of plan '.$plan->getId().' for user '.Auth::id());
        }

        return $changed;
    }
}
