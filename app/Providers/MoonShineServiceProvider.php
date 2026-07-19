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
use App\MoonShine\Resources\Workflow\App\Models\WorkflowResource;
use App\MoonShine\Resources\WorkflowStep\App\Models\WorkflowStepResource;
use App\MoonShine\Resources\User\App\Models\UserResource;
use App\MoonShine\Resources\WorkflowHistory\WorkflowHistoryResource;

class MoonShineServiceProvider extends ServiceProvider
{
    /**
     * @param  CoreContract<MoonShineConfigurator>  $core
     */
    public function boot(CoreContract $core): void
    {
        $core
            ->resources([
                MoonShineUserResource::class,
                MoonShineUserRoleResource::class,
                DocumentResource::class,
                // WorkflowResource::class,
                WorkflowStepResource::class,
                UserResource::class,
                WorkflowHistoryResource::class,
            ])
            ->pages([
                ...$core->getConfig()->getPages(),
            ])
        ;
    }
}
