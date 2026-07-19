<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\WorkflowStep\App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\WorkflowStep;
use App\MoonShine\Resources\WorkflowStep\Pages\WorkflowStepIndexPage;
use App\MoonShine\Resources\WorkflowStep\Pages\WorkflowStepFormPage;
use App\MoonShine\Resources\WorkflowStep\Pages\WorkflowStepDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends ModelResource<WorkflowStep, WorkflowStepIndexPage, WorkflowStepFormPage, WorkflowStepDetailPage>
 */
class WorkflowStepResource extends ModelResource
{
    protected string $model = WorkflowStep::class;

    protected string $title = 'App\Models\WorkflowSteps';
    
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
}
