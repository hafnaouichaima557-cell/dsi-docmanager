<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\AuditLog;

use App\Models\AuditLog;
use App\MoonShine\Resources\AuditLog\Pages\AuditLogDetailPage;
use App\MoonShine\Resources\AuditLog\Pages\AuditLogIndexPage;

use MoonShine\Contracts\Core\PageContract;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Support\Enums\Ability;

class AuditLogResource extends ModelResource
{
    protected string $model = AuditLog::class;

    protected string $title = 'Audit';

    /**
     * Pages disponibles pour Audit.
     *
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            AuditLogIndexPage::class,
            AuditLogDetailPage::class,
        ];
    }

    /**
     * Audit en lecture seule.
     */
    protected function isCan(Ability $ability): bool
    {
        return ! in_array(
            $ability,
            [
                Ability::CREATE,
                Ability::UPDATE,
                Ability::DELETE,
                Ability::MASS_DELETE,
            ],
            true
        );
    }
}