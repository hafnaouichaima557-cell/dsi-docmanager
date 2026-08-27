<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\WorkflowHistory;

use App\Models\WorkflowHistory;
use App\MoonShine\Resources\WorkflowHistory\Pages\WorkflowHistoryDetailPage;
use App\MoonShine\Resources\WorkflowHistory\Pages\WorkflowHistoryFormPage;
use App\MoonShine\Resources\WorkflowHistory\Pages\WorkflowHistoryIndexPage;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Support\Enums\Ability;

/**
 * @extends ModelResource<WorkflowHistory, WorkflowHistoryIndexPage, WorkflowHistoryFormPage, WorkflowHistoryDetailPage>
 */
class WorkflowHistoryResource extends ModelResource
{
    protected string $model = WorkflowHistory::class;

    protected string $title = 'Historique du workflow';

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

    /**
     * Lecture seule :
     * - Pas d'ajout
     * - Pas de modification
     * - Pas de suppression
     */
    protected function isCan(Ability $ability): bool
    {
        if (in_array($ability, [
            Ability::CREATE,
            Ability::UPDATE,
            Ability::DELETE,
            Ability::MASS_DELETE,
        ], true)) {
            return false;
        }

        return true;
    }
}