<?php

namespace App\Providers;

use App\Models\Client;
use App\Models\Contract;
use App\Models\CourtCase;
use App\Policies\CasePolicy;
use App\Policies\ClientPolicy;
use App\Policies\ContractPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // تسجيل السياسات
        Gate::policy(CourtCase::class, CasePolicy::class);
        Gate::policy(Contract::class, ContractPolicy::class);
        Gate::policy(Client::class, ClientPolicy::class);
    }
}