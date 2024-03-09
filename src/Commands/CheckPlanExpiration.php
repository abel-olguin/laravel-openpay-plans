<?php

namespace AbelOlguin\OpenPayPlans\Commands;

use AbelOlguin\OpenPayPlans\Helpers\OpenPayHelper;
use AbelOlguin\OpenPayPlans\Models\Subscription;
use AbelOlguin\OpenPayPlans\Models\UserPlan;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckPlanExpiration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plans:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if any subscription was cancelled or unpaid';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $helper = new OpenPayHelper();
        $today = Carbon::now()->format('Y-m-d');
        $subscriptions  = Subscription::with('userPlan')
                                      ->whereHas('userPlan', fn($query) =>  $query->where('active', true))
                                      ->where('period_end_date', '<=', $today)
                                      ->whereIn('status', OpenPayHelper::$activeStatuses)
                                      ->get();

        foreach ($subscriptions as $subscription) {
            $userPlan = $subscription->userPlan;
            $openPaySubscription = $helper->getSubscription($userPlan, $subscription->subscription_id);

            if(!in_array($openPaySubscription->status, OpenPayHelper::$activeStatuses) || $openPaySubscription->cancel_at_perriod_end){
                $userPlan->update(['active' => false]);
            }

            $subscription->update([
                'period_end_date'   => $openSubscription->period_end_date,
                'status'            => $openSubscription->status,
            ]);
        }
    }
}
