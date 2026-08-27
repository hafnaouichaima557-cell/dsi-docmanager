<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Notification\Pages;

use App\MoonShine\Resources\Notification\NotificationResource;

use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Support\ListOf;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Date;

/**
 * @extends IndexPage<NotificationResource>
 */
class NotificationIndexPage extends IndexPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Text::make('Type', 'type_label'),

            Text::make('Message', 'message_label'),

            Text::make('Destinataire', 'recipient_name'),

            Text::make('Statut', 'status_label'),

            Date::make('Lue le', 'read_at'),

            Date::make('Créée le', 'created_at'),
        ];
    }

    protected function buttons(): ListOf
    {
        return parent::buttons();
    }

    protected function filters(): iterable
    {
        return [];
    }
}
