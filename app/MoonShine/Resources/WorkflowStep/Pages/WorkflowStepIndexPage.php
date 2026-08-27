<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\WorkflowStep\Pages;

use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\QueryTags\QueryTag;
use MoonShine\UI\Components\Metrics\Wrapped\Metric;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;

use MoonShine\UI\Fields\Number;

use MoonShine\UI\Fields\Date;

use App\MoonShine\Resources\WorkflowStep\WorkflowStepResource;
use MoonShine\Support\ListOf;
use Throwable;


/**
 * @extends IndexPage<WorkflowStepResource>
 */
class WorkflowStepIndexPage extends IndexPage
{
    protected bool $isLazy = true;

    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [

        ID::make(),

       Text::make('Document', 'document.title'),
      Number::make('Ordre', 'step_order'),
      Text::make('Étape', 'step_name'),
      Text::make('Responsable', 'assignedUser.name'),
      Text::make('Statut', 'status'),
      Text::make('Commentaire', 'comment'),
      Date::make('Date d\'action', 'acted_at'),
      Date::make('Deadline', 'deadline'),

    ];
}

    /**
     * @return ListOf<ActionButtonContract>
     */
    protected function buttons(): ListOf
    {
        return parent::buttons();
    }

    /**
     * @return list<FieldContract>
     */
    protected function filters(): iterable
    {
        return [];
    }

    /**
     * @return list<QueryTag>
     */
    protected function queryTags(): array
    {
        return [];
    }

    /**
     * @return list<Metric>
     */
    protected function metrics(): array
    {
        return [];
    }

    /**
     * @param  TableBuilder  $component
     *
     * @return TableBuilder
     */
    protected function modifyListComponent(ComponentContract $component): ComponentContract
    {
        return $component;
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function topLayer(): array
    {
        return [
            ...parent::topLayer()
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function mainLayer(): array
    {
        return [
            ...parent::mainLayer()
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function bottomLayer(): array
    {
        return [
            ...parent::bottomLayer()
        ];
    }
}
