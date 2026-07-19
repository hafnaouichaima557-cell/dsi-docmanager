<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\WorkflowHistory;

use Illuminate\Database\Eloquent\Model;
use App\Models\WorkflowHistory;
use App\MoonShine\Resources\WorkflowHistory\Pages\WorkflowHistoryIndexPage;
use App\MoonShine\Resources\WorkflowHistory\Pages\WorkflowHistoryFormPage;
use App\MoonShine\Resources\WorkflowHistory\Pages\WorkflowHistoryDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends ModelResource<WorkflowHistory, WorkflowHistoryIndexPage, WorkflowHistoryFormPage, WorkflowHistoryDetailPage>
 */
class WorkflowHistoryResource extends ModelResource
{
    protected string $model = WorkflowHistory::class;

    protected string $title = 'WorkflowHistories';
    
    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            WorkflowHistoryIndexPage::class,
            WorkflowHistoryFormPage::class,
            WorkflowHistoryDetailPage::class,
        ];
    }
}
