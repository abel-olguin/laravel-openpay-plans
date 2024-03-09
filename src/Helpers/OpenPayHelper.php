<?php

namespace AbelOlguin\OpenPayPlans\Helpers;

use AbelOlguin\OpenPayPlans\Models\Plan;
use AbelOlguin\OpenPayPlans\Models\UserPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Openpay\Data\Openpay;
use Openpay\Data\OpenpayApi;
use Openpay\Resources\OpenpaySubscription;

class OpenPayHelper
{
    private OpenpayApi $helper;
    public static $activeStatuses = ['active', 'trial'];

    public function __construct()
    {
        Openpay::setId(config('plans.openId'));
        Openpay::setApiKey(config('plans.openApiKey'));
        Openpay::setCountry(config('plans.openCountry'));
        Openpay::setProductionMode(config('plans.openProduction'));

        $this->helper = Openpay::getInstance();
    }

    /**
     * Delete openpay plan
     * @param Plan $plan
     * @return void
     */
    public function deletePlan(Plan $plan)
    {
        $plan = $this->helper->plans->get($plan->plan_id);
        $plan->delete();
    }

    /**
     * Create open pay plan
     *
     * @param Plan $plan
     * @return mixed
     */
    public function createPlan(Plan $plan)
    {
        return $this->helper->plans->add([
            "amount"             => $plan->price,
            "status_after_retry" => "cancelled",
            "retry_times"        => 2,
            "name"               => $plan->name,
            "repeat_unit"        => $plan->plan_type,
            "trial_days"         => $plan->trial_days,
            "repeat_every"       => $plan->plan_type_quantity
        ]);

    }

    /**
     * Subscribe to one openpay plan
     *
     * @param Plan $plan
     * @param array $data
     * @return OpenpaySubscription
     */
    public function subscribePlan(Plan $plan, array $data):OpenpaySubscription
    {
        $data['plan_id'] = $plan->plan_id;
        if ($data['trial']) {
            $data['trial_end_date'] = Carbon::now()->addDays($plan->trial_days);
        }

        $customer = $this->helper->customers->get($this->getCurrentCustomerId());

        return $customer->subscriptions->add($data);
    }

    /**
     * Get current user customer or make a new one
     *
     * @return mixed
     */
    public function getCurrentCustomerId()
    {
        $user = auth()->user();
        if (!$user->customer_id) {
            $customer           = $this->createCustomer($user);
            $user->customer_id  = $customer->id;
            $user->save();
        }
        return $user->customer_id;
    }

    /**
     * Create a new user openpay customer
     *
     * @param User $user
     * @return mixed
     */
    public function createCustomer(User $user)
    {
        return $this->helper->customers->add([
            'name'      => $user->name,
            'last_name' => $user->last_name,
            'email'     => $user->email,
        ]);
    }

    /**
     * Get a user plan openpay subscription
     *
     * @param UserPlan $userPlan
     * @param string $subscriptionId
     * @return mixed
     */
    public function getSubscription(UserPlan $userPlan, string $subscriptionId)
    {
        $customer = $this->helper->customers->get($userPlan->user->customer_id);
        return $customer->subscriptions->get($subscriptionId);
    }

    /**
     * Cancel an openpay subscription
     *
     * @param UserPlan $userPlan
     * @return void
     */
    public function cancelSubscription(UserPlan $userPlan)
    {
        $customer       = $this->helper->customers->get($userPlan->user->customer_id);
        $subscription   = $customer->subscriptions->get($userPlan->subscription->subscription_id);
        $subscription->delete();
    }
}
