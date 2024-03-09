<?php

namespace AbelOlguin\OpenPayPlans\Commands;

use AbelOlguin\OpenPayPlans\Helpers\OpenPayHelper;
use AbelOlguin\OpenPayPlans\Models\Plan;
use Illuminate\Console\Command;

class CreatePlans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plans:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create all plans on openpay';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $helper = new OpenPayHelper();
        $plans  = Plan::all();
        foreach ($plans as $key => $plan) {
            if($plan->plan_id){
                $helper->deletePlan($plan);
            }

            if($plan->price){
                $openPayPlan = $helper->createPlan($plan);
                $plan->update(['plan_id' => $openPayPlan->id]);
            }
        }
    }
}
