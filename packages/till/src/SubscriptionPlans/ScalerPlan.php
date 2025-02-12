<?php

namespace ArtisanBuild\Till\SubscriptionPlans;

use ArtisanBuild\Till\Attributes\TeamPlan;
use ArtisanBuild\Till\Contracts\PlanInterface;
use ArtisanBuild\Till\Enums\PlanTerms;
use ArtisanBuild\Till\SubscriptionPlans\BasePlan;

#[TeamPlan]
class ScalerPlan extends BasePlan implements PlanInterface
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
        PlanTerms::Week->value => null,
        PlanTerms::Month->value => 50,
        PlanTerms::Year->value => 500,
        PlanTerms::Life->value => null,
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
        'size' => '', // https://fluxui.dev/components/badge#sizes
        'variant' => '', // https://fluxui.dev/components/badge ('', 'pill', or 'solid')
        'color' => '', // example: ArtisanBuild\FluxThemes\Enums\Colors::Blue->value
        'text' => '', // Text of the badge. Keep it short.
        'icon' => '', // Anything from heroicons (See Flux badge docs for icon use)
        'inset' => '', // https://fluxui.dev/components/badge#inset
    ];

    public string $heading = 'Scaler';

    public string $subheading = 'Amazing efficiency across multiple growing teams';

    /**
     * Define Your Features
     * ---------------------
     * This is an array of arrays. Each inner array is a feature that will be displayed on your pricing and plans pages.
     * This is used for display only and does not actually enable the features. That part is taken care of in the next
     * section, where you define your abilities. For the moment, there are only two valid keys: text and icon. The icon
     * is nullable and may be omitted from the feature.
     *
     * You can also use a stirng instead of an array for a feature that doesn't have an icon, but if you're using
     * icons on any of your features, we recommend that you use an array for all of your features because consistency
     * leads to more maintainable apps.
     */
    public array $features = [
        ['text' => 'Ten Users', 'icon' => null],
        ['text' => '2,500 Queries / Day', 'icon' => null],
        ['text' => 'Email Support', 'icon' => null],
    ];

    /**
     * Abilities
     * ---------
     * What subscribers to this plan can do. All abilities should be defined as invokable classes. We keep them in a
     * folder called Abilities inside the folder where our subscription plans are kept and by default, we will create
     * that folder for you when you install this package and your abilities must be kept in that file.
     *
     * This tuple takes the class name of the ability as the first element and an array of arguments passed to the
     * __invoke() method of the class as the second argument. You can use FQDNs in the first position, but the namespace
     * will be ignored. All abilities must be in the Abilities folder inside of your plans folder. This was an
     * intentional decision. It takes away a little bit of flexibility from the end user of this package, but in doing
     * so it significantly simplifies the way we process and handle the data.
     */
    public array $can = [
        // ['AddSeats', ['limit' => 1]],
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
