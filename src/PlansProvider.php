<?php

namespace AbelOlguin\OpenPayPlans;

use AbelOlguin\OpenPayPlans\Commands\DeletePlans;
use AbelOlguin\OpenPayPlans\Models\Plan;
use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Console\AboutCommand;
use App\Models\User;
use AbelOlguin\OpenPayPlans\Commands\CreatePlans;
use AbelOlguin\OpenPayPlans\Commands\CheckPlanExpiration;
use AbelOlguin\OpenPayPlans\Middlewares\HasActivePlan;
use AbelOlguin\OpenPayPlans\Middlewares\HasAnyPlan;

class PlansProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerConfig();
    }

    public function boot()
    {
        AboutCommand::add('Laravel Openpay plans', fn () => ['Version' => '0.1.0', 'Author' => 'Abel Olguin', 'Message' => ':)']);

        $this->defineMiddlewares();
        $this->defineGates();
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'plans');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadJsonTranslationsFrom(__DIR__.'/../lang');

        if ($this->app->runningInConsole()) {
            $this->commands([
                CreatePlans::class,
                CheckPlanExpiration::class,
                DeletePlans::class,
            ]);
            $this->publishConfigs();
            $this->publishDatabase();
            $this->publishViews();
            $this->publishLang();
        }
    }

    protected function defineMiddlewares()
    {
        app('router')->aliasMiddleware('plans', HasAnyPlan::class);
        app('router')->aliasMiddleware('plans.active', HasActivePlan::class);
    }

    protected function defineGates()
    {
        Gate::define('has-active-plan', function (User $user) {
            return $user->hasActivePlan();
        });

        Gate::define('has-plan', function (User $user, string|array $plan) {
            return $user->hasAnyPlan(is_array($plan) ? $plan : [$plan]);
        });

        Gate::define('create-plan', function (User $user, Plan $plan = null) {
            if(config('plans.allow_multiple_plans') && $plan && !$user->hasAnyPlan([$plan->name])){
                return true;
            }
            $plan = $user->hasActivePlan();
            return !$plan;
        });
    }

    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/plans.php', 'plans');
    }

    protected function publishConfigs(): void
    {
        $this->publishes([
            __DIR__ . '/../config/plans.php' => config_path('plans.php'),
        ], 'plans-config');

    }

    protected function publishDatabase(){
        $this->publishes([
            __DIR__.'/../database/migrations/create_plan_tables.php.stub' => $this->getMigrationFileName('create_plan_tables.php'),
        ], 'plans-migrations');
    }

    protected function publishViews(){

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/plans'),
        ]);
    }

    protected function publishLang(){

        $this->publishes([
            __DIR__.'/../lang' => $this->app->langPath('vendor/plans'),
        ]);
    }

    private function getMigrationFileName(string $migrationFileName): string
    {
        $timestamp = date('Y_m_d_His');

        $filesystem = $this->app->make(Filesystem::class);

        return Collection::make([$this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR])
            ->flatMap(fn ($path) => $filesystem->glob($path.'*_'.$migrationFileName))
            ->push($this->app->databasePath()."/migrations/{$timestamp}_{$migrationFileName}")
            ->first();
    }
}
