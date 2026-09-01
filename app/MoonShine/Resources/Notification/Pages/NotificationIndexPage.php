<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Notification\Pages;

use App\MoonShine\Resources\Notification\NotificationResource;

use MoonShine\Laravel\Pages\Crud\IndexPage;

use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Date;

/**
 * @extends IndexPage<NotificationResource>
 */
class NotificationIndexPage extends IndexPage
{
    protected function fields(): iterable
    {
        return [
            Text::make(
                'Notification',
                'message_label'
            ),

            Text::make(
                'Type',
                'type_label'
            ),

            Text::make(
                'Destinataire',
                'recipient_name'
            ),

            Text::make(
                'État',
                'status_label'
            )->badge(
                function ($status) {
                    return match ($status) {
                        'Lue' => 'success',
                        'Non lue' => 'warning',
                        default => 'gray',
                    };
                }
            ),

            Date::make(
                'Date',
                'created_at'
            )->withTime(),
        ];
    }

    protected function filters(): iterable
    {
        return [];
    }
}