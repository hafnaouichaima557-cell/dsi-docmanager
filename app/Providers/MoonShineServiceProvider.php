<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use MoonShine\Contracts\Core\DependencyInjection\CoreContract;
use MoonShine\Laravel\DependencyInjection\MoonShine;
use MoonShine\Laravel\DependencyInjection\MoonShineConfigurator;
use App\MoonShine\Resources\MoonShineUser\MoonShineUserResource;
use App\MoonShine\Resources\MoonShineUserRole\MoonShineUserRoleResource;
use App\MoonShine\Resources\Document\DocumentResource;
use App\MoonShine\Resources\WorkflowStep\WorkflowStepResource;
use App\MoonShine\Resources\User\UserResource;
use App\MoonShine\Resources\WorkflowHistory\WorkflowHistoryResource;
use App\MoonShine\Resources\Notification\NotificationResource;
use App\MoonShine\Pages\Workflow;

class MoonShineServiceProvider extends ServiceProvider
{
    /**
     * @param  CoreContract<MoonShineConfigurator>  $core
     */
    public function boot(CoreContract $core): void
    {
        $core
            ->resources([
                // MoonShineUserResource::class,
                // MoonShineUserRoleResource::class,
                DocumentResource::class,
                // WorkflowResource::class,
                WorkflowStepResource::class,
                UserResource::class,
                WorkflowHistoryResource::class,
                NotificationResource::class,
            ])
            ->pages([
                ...$core->getConfig()->getPages(),
                Workflow::class,
            ])
        ;
    }
}
