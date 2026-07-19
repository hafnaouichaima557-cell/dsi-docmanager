<?php

namespace App\Providers;
use App\Models\Document;
use App\Observers\DocumentObserver;

use App\Policies\DocumentPolicy;

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