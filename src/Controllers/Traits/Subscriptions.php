<?php

namespace AbelOlguin\OpenPayPlans\Controllers\Traits;

use AbelOlguin\OpenPayPlans\Models\UserPlan;
use Openpay\Resources\OpenpaySubscription;
use AbelOlguin\OpenPayPlans\Helpers\OpenPayHelper;
use AbelOlguin\OpenPayPlans\Models\Plan;
use AbelOlguin\OpenPayPlans\Models\Subscription;
use AbelOlguin\OpenPayPlans\Rules\TrialRule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

trait Subscriptions
{
    /**
     * Validation rules to create a new subscription
     *
     * @param Request $request
     * @return array
     */
    protected function validateSubscription(Request $request): array
    {
        $year = date('y');
        $max  = (int)$year + 5;

        return $request->validate([
            'trial'                 => [new TrialRule],
            'plan_id'               => 'required|exists:plans,id',
            'card'                  => 'required|array',
            'card.card_number'      => 'required|digits:16',
            'card.holder_name'      => 'required',
            'card.expiration_year'  => 'required|integer|between:' . $year . ',' . $max,
            'card.expiration_month' => 'required|integer|between:1,12',
            'card.cvv2'             => 'required|digits:3',
        ]);
    }

    /**
     * Save a subscription
     * @param OpenpaySubscription $openSubscription
     * @param array $validated
     * @return Subscription
     */
    protected function saveSubscription(OpenpaySubscription $openSubscription, array $validated): Subscription
    {
        return Subscription::create([
            'subscription_id'       => $openSubscription->id,
            'card_type'             => $openSubscription->card->type,
            'brand'                 => $openSubscription->card->brand,
            'card_number'           => $openSubscription->card->card_number,
            'holder_name'           => $openSubscription->card->holder_name,
            'bank_name'             => $openSubscription->card->bank_name,
            'current_period_number' => $openSubscription->current_period_number,
            'period_end_date'       => $openSubscription->period_end_date,
            'trial_end_date'        => $openSubscription->trial_end_date,
            'status'                => $openSubscription->status
        ]);
    }

    /**
     * Save user plan
     * @param OpenpaySubscription $openSubscription
     * @param Subscription $subscription
     * @param array $validated
     * @return UserPlan
     */
    public function saveUserPlan(OpenpaySubscription $openSubscription, Subscription $subscription, int $planId): UserPlan
    {
        return UserPlan::create([
            'user_id'           => auth()->id(),
            'plan_id'           => $planId,
            'subscription_id'   => $subscription->id,
            'active'            => in_array($openSubscription->status, OpenPayHelper::$activeStatuses)
        ]);
    }

}
