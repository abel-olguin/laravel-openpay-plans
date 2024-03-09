<?php

namespace Models;

use AbelOlguin\OpenPayPlans\Models\Subscription;
use AbelOlguin\OpenPayPlans\PlansProvider;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Foundation\Testing\WithFaker;
use Orchestra\Testbench\TestCase;

class SubscriptionTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [PlansProvider::class];
    }

    public function testSubscriptionModelHasUserPlans()
    {
        $subscription =new Subscription();
        $this->assertTrue(method_exists(Subscription::class, 'userPlan'));
        $this->assertTrue($subscription->userPlan() instanceof HasOne);
    }

    public function testSubscriptionBelongsToPlan()
    {
        $subscription = new Subscription();
        $this->assertTrue(method_exists(Subscription::class, 'plan'));
        $this->assertTrue($subscription->plan() instanceof HasOneThrough);
    }
}
