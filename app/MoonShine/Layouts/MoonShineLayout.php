<?php

declare(strict_types=1);

namespace App\MoonShine\Layouts;

use MoonShine\Laravel\Layouts\AppLayout;
use MoonShine\ColorManager\Palettes\PurplePalette;
use MoonShine\ColorManager\ColorManager;
use MoonShine\Contracts\ColorManager\ColorManagerContract;
use MoonShine\Contracts\ColorManager\PaletteContract;
use App\MoonShine\Resources\Document\DocumentResource;
use MoonShine\MenuManager\MenuItem;
use App\MoonShine\Resources\Workflow\App\Models\WorkflowResource;
use App\MoonShine\Resources\WorkflowStep\App\Models\WorkflowStepResource;
use App\MoonShine\Resources\User\App\Models\UserResource;
use App\MoonShine\Resources\WorkflowHistory\WorkflowHistoryResource;

final class MoonShineLayout extends AppLayout
{
    /**
     * @var null|class-string<PaletteContract>
     */
    protected ?string $palette = PurplePalette::class;

    protected function assets(): array
    {
        return [
            ...parent::assets(),
        ];
    }

    protected function menu(): array
    {
        return [
            ...parent::menu(),
            MenuItem::make(DocumentResource::class, 'Documents'),
            // MenuItem::make(WorkflowResource::class, 'App\Models\Workflows'),
            MenuItem::make(WorkflowStepResource::class, 'App\Models\WorkflowSteps'),
            MenuItem::make(UserResource::class, 'App\Models\Users'),
            MenuItem::make(WorkflowHistoryResource::class, 'WorkflowHistories'),
        ];
    }

    /**
     * @param ColorManager $colorManager
     */
    protected function colors(ColorManagerContract $colorManager): void
    {
        parent::colors($colorManager);

        // $colorManager->primary('#00000');
    }
}
