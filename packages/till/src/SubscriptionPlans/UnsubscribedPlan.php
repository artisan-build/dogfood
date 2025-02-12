<?php

namespace ArtisanBuild\Till\SubscriptionPlans;

use ArtisanBuild\Till\Attributes\DefaultPlan;
use ArtisanBuild\Till\Attributes\IndividualPlan;
use ArtisanBuild\Till\Attributes\TeamPlan;
use ArtisanBuild\Till\Contracts\PlanInterface;
use ArtisanBuild\Till\Enums\PlanTerms;
use ArtisanBuild\Till\SubscriptionPlans\Abilities\AddSeats;
use ArtisanBuild\Till\SubscriptionPlans\BasePlan;

#[DefaultPlan]
#[TeamPlan]
#[IndividualPlan]
class UnsubscribedPlan extends BasePlan implements PlanInterface
{

    /**
     * Prices
     * ------
     * This is where you would define the prices for each subscription term that you offer (week, month, year, life).
     * It is a much more interesting array in other feature classes. In this one, it only holds one value and that is
     * the only valid value for the default plan.
     *
     * Do not edit this value for the default plan. At least at this point. We may eventually come up with a way to
     * force payment before a user can even create an account, in which case it might make sense to define pricing here.
     * But in all of our apps, a user registers for an account either to start using the free tier of the tool or as
     * part of the purchase process. So an account is created even if the user does not complete the payment. They
     * are simply left on this plan, their action on the site governed by the abilities defined below.
     */
    public array $prices = [
        PlanTerms::Default->value => 0,
    ];

    /**
     * The Badge
     * ----------
     * The badge for any plan only appears if a user is not subscribed to any plan. If the user is subscribed, a
     * "current plan" badge is displayed in a highlighted box on the "Plans" page while they're viewing it. In our
     * default configuration, the same template is used for both the "Pricing" page for guests and the "Plans" page for
     * registered users.
     */
    public array $badge = [
        'size' => '',
        'variant' => '',
        'color' => '',
        'text' => '',
        'icon' => '',
    ];

    public string $heading = 'Unsubscribed';

    public string $subheading = '';

    /**
     * For Fremium Apps Only
     * ---------------------
     * This "plan" provides the default abilities for anyone who is not subscribed to a paid plan. If your app has a
     * free tier with actual features, rather than being a paid-only application, you will want the free tier to appear
     * on your pricing page. All you have to do for that to happen is populate the features of the free tier in this
     * array according to the documentation. Valid sample data is commented out below.
     */
    public array $features = [
        //['text' => 'One User', 'icon' => null],
        //['text' => '50 Queries / Day', 'icon' => null],
        //['text' => 'Email Support', 'icon' => null],
    ];

    /**
     * What subscribers to this plan can do. All abilities should be defined as invokable classes. We keep them in a
     * folder called Abilities inside the folder where our subscription plans are kept and by default, we will create
     * that folder for you when you install this package. You are welcome to move it anywhere because the array
     * below expects a fully qualified classname as the first value of each tuple. However, if you use the command
     * `php artisan till:create-ability` that is where we will create the new class.
     *
     * The values of this array are tuples where the first value is the FQCN of the invokable class and the second value
     * is an array of arguments that are expected by the __invoke() method of the ability class.
     */
    public array $can = [
        [AddSeats::class, ['limit' => 1]],
    ];

    /**
     * Provider Keys
     * -------------
     * These are automatically created for you when you run php artisan till:sync-driver stripe
     *
     * You should never need to edit these values.
     */
    public array $provider_keys = [
    ];
}

