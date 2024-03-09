<?php

namespace Models;

use AbelOlguin\OpenPayPlans\Models\Plan;
use AbelOlguin\OpenPayPlans\Models\Subscription;
use AbelOlguin\OpenPayPlans\PlansProvider;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Foundation\Testing\WithFaker;
use Orchestra\Testbench\TestCase;

class PlanTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [PlansProvider::class];
    }

    public function testPlanHasActiveScope()
    {
        $this->assertTrue(method_exists(Plan::class, 'scopeActive'));
    }

}
