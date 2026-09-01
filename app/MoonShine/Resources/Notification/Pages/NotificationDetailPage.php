<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Notification\Pages;

use App\MoonShine\Resources\Notification\NotificationResource;

use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\Support\ListOf;

use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;

class NotificationDetailPage extends DetailPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),

            Text::make(
                'Type',
                'type_label'
            ),

            Text::make(
                'Message',
                'message_label'
            ),

            Text::make(
                'Destinataire',
                'notifiable.name'
            ),

            Text::make(
                'Statut',
                'read_at',
                fn ($item) => $item->read_at
                    ? 'Lue'
                    : 'Non lue'
            ),

            Date::make(
                'Lue le',
                'read_at'
            )->withTime(),

            Date::make(
                'Créée le',
                'created_at'
            )->withTime(),
        ];
    }

    /**
     * Boutons de la page détail.
     */
    protected function buttons(): ListOf
    {
        return parent::buttons();
    }
}