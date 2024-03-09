<?php

namespace AbelOlguin\OpenPayPlans\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use AbelOlguin\OpenPayPlans\Helpers\OpenPayHelper;
use AbelOlguin\OpenPayPlans\Models\Plan;
use AbelOlguin\OpenPayPlans\Models\Subscription;
use AbelOlguin\OpenPayPlans\Models\UserPlan;
use \AbelOlguin\OpenPayPlans\Controllers\Traits\Subscriptions;

class SubscriptionController
{
    use Subscriptions;

    public function __construct(private OpenPayHelper $openHelper)
    {
    }

    /**
     * Show current user subscription list
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function index(Request $request)
    {
        $userPlans  = UserPlan::where('user_id', auth()->id())->with('plan', 'subscription')->get();

        return view('plans::index', compact( 'userPlans'));
    }

    /**
     * Show new subscription form
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function create()
    {
        $plans      = Plan::select('id', 'name')->get();

        return view('plans::create', compact( 'plans'));
    }

    /**
     * Cancel subscription
     * @param UserPlan $userPlan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(UserPlan $userPlan)
    {
        $this->openHelper->cancelSubscription($userPlan);

        return redirect()->route(config('plans.subscription_success_url'))
                         ->with('success', __('Subscription cancelled successfully.'));
    }

    /**
     * Create new subscription
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validated          = $this->validateSubscription($request);
            $plan               = Plan::find($validated['plan_id']);
            $openSubscription   = $this->openHelper->subscribePlan($plan, $validated);
            $subscription       = $this->saveSubscription($openSubscription, $validated);
            $userPlan           = $this->saveUserPlan($openSubscription, $subscription, $validated);

            DB::commit();

            return redirect()->to(config('plans.subscription_success_url'))
                             ->with('success', __("Subscribed successfully."));
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                             ->with('error', __('An error has occurred, try again later: ') . $e->getMessage());
        }

    }
}
