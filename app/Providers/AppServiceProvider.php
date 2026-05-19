<?php

namespace App\Providers;

use App\Models\Document;
use App\Policies\DocumentPolicy;
use App\Observers\DocumentObserver;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;

class AppServiceProvider extends AuthServiceProvider
{
    protected $policies = [
        Document::class => DocumentPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // تسجيل Observer
        Document::observe(DocumentObserver::class);
    }
}