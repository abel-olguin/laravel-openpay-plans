<?php

namespace AbelOlguin\OpenPayPlans\Models\Traits;

use AbelOlguin\OpenPayPlans\Models\Plan;
use AbelOlguin\OpenPayPlans\Models\Subscription;
use AbelOlguin\OpenPayPlans\Models\UserPlan;

trait HasPlans
{

    public function plans()
    {
        return $this->belongsToMany(Plan::class, UserPlan::class);
    }

    public function subscriptions()
    {
        return $this->belongsToMany(Subscription::class, UserPlan::class);
    }

    public function hasAnyPlan($plans)
    {
        return $this->plans()->active()->whereIn('name', $plans)->exists();
    }

    public function hasActivePlan()
    {
        return $this->plans()->active()->exists();
    }
}
