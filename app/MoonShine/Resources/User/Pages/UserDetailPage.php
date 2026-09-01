<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\User\Pages;

use App\MoonShine\Resources\User\UserResource;

use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\Support\ListOf;

use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\Email;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;

class UserDetailPage extends DetailPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),

            Text::make(
                'Nom',
                'name'
            ),

            Email::make(
                'Email',
                'email'
            ),

            Text::make(
                'Département',
                'department'
            ),

            Text::make(
                'Rôle',
                'roles',
                fn ($item) => $item->roles
                    ->pluck('name')
                    ->join(', ') ?: 'Aucun rôle'
            ),

            Date::make(
                'Date de création',
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