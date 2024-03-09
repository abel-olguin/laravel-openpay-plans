<?php

namespace AbelOlguin\OpenPayPlans\Routes;
use Illuminate\Support\Facades\Route;
use AbelOlguin\OpenPayPlans\Controllers\SubscriptionController;

class Plans
{
    public static function routes()
    {
        Route::middleware('auth')->prefix(__('plans'))->as('plans.')-> group(function(){
            $path = __('subscriptions');
            $verbs = Route::resourceVerbs();

            Route::get($path, [SubscriptionController::class, 'index'])->name('subscriptions.index');
            Route::get("{$path}/{$verbs['create']}", [SubscriptionController::class, 'create'])->name('subscriptions.create');
            Route::post($path, [SubscriptionController::class, 'store'])->name('subscriptions.store');
            Route::put("{$path}/{userPlan}", [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
        });
    }
}
