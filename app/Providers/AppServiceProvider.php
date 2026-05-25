<?php

namespace App\Providers;

use App\Models\Assessment;
use App\Models\Flag;
use App\Models\Resource;
use App\Policies\AssessmentPolicy;
use App\Policies\FlagPolicy;
use App\Policies\ResourcePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Resource::class, ResourcePolicy::class);
        Gate::policy(Assessment::class, AssessmentPolicy::class);
        Gate::policy(Flag::class, FlagPolicy::class);
    }
}
