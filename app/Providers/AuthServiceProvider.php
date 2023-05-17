<?php

namespace App\Providers;

use App\Models\Checklist;
use App\Models\ChecklistItem;
use App\Policies\ChecklistItemPolicy;
use App\Policies\ChecklistPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Checklist::class => ChecklistPolicy::class,
        ChecklistItem::class => ChecklistItemPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
