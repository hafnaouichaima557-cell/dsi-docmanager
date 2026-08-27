<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Notification;

use App\Models\AdminNotification;

use App\MoonShine\Resources\Notification\Pages\NotificationIndexPage;
use App\MoonShine\Resources\Notification\Pages\NotificationFormPage;
use App\MoonShine\Resources\Notification\Pages\NotificationDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Support\Enums\Ability;

class NotificationResource extends ModelResource
{
    protected string $model = AdminNotification::class;

    protected string $title = 'Notifications';

    protected function pages(): array
    {
        return [
            NotificationIndexPage::class,
            NotificationFormPage::class,
            NotificationDetailPage::class,
        ];
    }

    protected function indexQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return AdminNotification::query();
    }

    protected function modifyQuery(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query;
    }

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