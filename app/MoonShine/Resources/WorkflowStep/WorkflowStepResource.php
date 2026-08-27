<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\WorkflowStep;

use Illuminate\Database\Eloquent\Model;
use App\Models\WorkflowStep;
use App\MoonShine\Resources\WorkflowStep\Pages\WorkflowStepIndexPage;
use App\MoonShine\Resources\WorkflowStep\Pages\WorkflowStepFormPage;
use App\MoonShine\Resources\WorkflowStep\Pages\WorkflowStepDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Support\Enums\Ability;

/**
 * @extends ModelResource<WorkflowStep, WorkflowStepIndexPage, WorkflowStepFormPage, WorkflowStepDetailPage>
 */
class WorkflowStepResource extends ModelResource
{
    protected string $model = WorkflowStep::class;

    protected string $title = 'Étapes de workflow';

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            WorkflowStepIndexPage::class,
            WorkflowStepFormPage::class,
            WorkflowStepDetailPage::class,
        ];
    }

    protected function isCan(Ability $ability): bool
    {
        if (in_array($ability, [Ability::CREATE, Ability::UPDATE, Ability::DELETE, Ability::MASS_DELETE], true)) {
            return false;
        }

        return true;
    }
}