<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\User\Pages;

use App\MoonShine\Resources\User\UserResource;

use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;

use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Laravel\QueryTags\QueryTag;

use MoonShine\Support\ListOf;

use MoonShine\UI\Components\Metrics\Wrapped\Metric;
use MoonShine\UI\Components\Table\TableBuilder;

use MoonShine\UI\Fields\Email;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;

use Throwable;

/**
 * @extends IndexPage<UserResource>
 */
class UserIndexPage extends IndexPage
{
    protected bool $isLazy = true;

    /**
     * Champs affichés dans la liste.
     *
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

            Select::make(
                'Département',
                'department'
            )->options([
                'DSI' => 'DSI',
                'RH' => 'RH',
                'Développement' => 'Développement',
                'Cloud' => 'Cloud',
                'Support' => 'Support',
                'Sécurité Réseaux' => 'Sécurité Réseaux',
            ]),
        ];
    }

    /**
     * Boutons de la liste.
     *
     * @return ListOf
     */
    protected function buttons(): ListOf
    {
        return parent::buttons();
    }

    /**
     * Filtres
     *
     * @return list<FieldContract>
     */
    protected function filters(): iterable
    {
        return [];
    }

    /**
     * Tags de recherche
     *
     * @return list<QueryTag>
     */
    protected function queryTags(): array
    {
        return [];
    }

    /**
     * Metrics
     *
     * @return list<Metric>
     */
    protected function metrics(): array
    {
        return [];
    }

    /**
     * Modification du composant de liste.
     */
    protected function modifyListComponent(
        ComponentContract $component
    ): ComponentContract {
        return $component;
    }

    /**
     * Couche supérieure.
     *
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function topLayer(): array
    {
        return [
            ...parent::topLayer(),
        ];
    }

    /**
     * Couche principale.
     *
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function mainLayer(): array
    {
        return [
            ...parent::mainLayer(),
        ];
    }

    /**
     * Couche inférieure.
     *
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function bottomLayer(): array
    {
        return [
            ...parent::bottomLayer(),
        ];
    }
}