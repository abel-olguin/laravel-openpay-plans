<?php

namespace Models;

use AbelOlguin\OpenPayPlans\Models\Subscription;
use AbelOlguin\OpenPayPlans\Models\UserPlan;
use AbelOlguin\OpenPayPlans\PlansProvider;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Foundation\Testing\WithFaker;
use Orchestra\Testbench\TestCase;

class UserPlanTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [PlansProvider::class];
    }

    public function testUserPlanBelongsToPlan()
    {
        $userPlan =new UserPlan();
        $this->assertTrue(method_exists(UserPlan::class, 'plan'));
        $this->assertTrue($userPlan->plan() instanceof BelongsTo);
    }

    public function testUserPlanBelongsToSubscription()
    {
        $userPlan = new UserPlan();
        $this->assertTrue(method_exists(UserPlan::class, 'subscription'));
        $this->assertTrue($userPlan->plan() instanceof BelongsTo);
    }
}
