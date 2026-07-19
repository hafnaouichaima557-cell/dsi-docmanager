<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Workflow\App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\WorkflowHistory;
use App\MoonShine\Resources\Workflow\Pages\WorkflowIndexPage;
use App\MoonShine\Resources\Workflow\Pages\WorkflowFormPage;
use App\MoonShine\Resources\Workflow\Pages\WorkflowDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends ModelResource<Workflow, WorkflowIndexPage, WorkflowFormPage, WorkflowDetailPage>
 */
class WorkflowResource extends ModelResource
{
    protected string $model = Workflow::class;

    protected string $title = 'App\Models\WorkflowHistory';
    
    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            WorkflowIndexPage::class,
            WorkflowFormPage::class,
            WorkflowDetailPage::class,
        ];
    }
}
