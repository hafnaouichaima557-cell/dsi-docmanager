<?php
declare(strict_types=1);
namespace App\MoonShine\Resources\User;
use App\Models\User;
use App\MoonShine\Resources\User\Pages\UserIndexPage;
use App\MoonShine\Resources\User\Pages\UserFormPage;
use App\MoonShine\Resources\User\Pages\UserDetailPage;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Email;
use MoonShine\UI\Fields\Password;
use MoonShine\UI\Fields\Select;
use MoonShine\Support\Enums\Ability;

class UserResource extends ModelResource
{
    protected string $model = User::class;
    protected string $title = 'Utilisateurs';

    protected function pages(): array
    {
        return [
            UserIndexPage::class,
            UserFormPage::class,
            UserDetailPage::class,
        ];
    }

    protected function fields(): iterable
    {
        return [
            ID::make(),
            Text::make('Nom', 'name'),
            Email::make('Email', 'email'),
            Password::make('Mot de passe', 'password')->hideOnIndex(),
            Select::make('Département', 'department')->options([
                'DSI' => 'DSI',
                'RH'  => 'RH',
            ]),
        ];
    }

    // Lecture seule : bloque create/update/delete, autorise seulement la vue
    protected function isCan(Ability $ability): bool
    {
        if (in_array($ability, [Ability::CREATE, Ability::UPDATE, Ability::DELETE, Ability::MASS_DELETE], true)) {
            return false;
        }

        return true;
    }
}