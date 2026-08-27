<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\Document;
use Carbon\Carbon;

use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Laravel\Pages\Page;
use MoonShine\Laravel\TypeCasts\ModelCaster;

use MoonShine\Apexcharts\Components\DonutChartMetric;
use MoonShine\Apexcharts\Components\LineChartMetric;
use MoonShine\Apexcharts\Support\SeriesItem;

use MoonShine\UI\Components\Heading;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\UI\Components\Layout\Grid;
use MoonShine\UI\Components\Metrics\Wrapped\ValueMetric;
use MoonShine\UI\Components\Table\TableBuilder;

use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;

#[\MoonShine\MenuManager\Attributes\SkipMenu]
class Dashboard extends Page
{
    public function getBreadcrumbs(): array
    {
        return [
            '#' => $this->getTitle(),
        ];
    }

    public function getTitle(): string
    {
        return 'Dashboard';
    }

    protected function components(): iterable
    {
        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

        yield Heading::make('Vue d’ensemble');

        /*
        |--------------------------------------------------------------------------
        | KPI
        |--------------------------------------------------------------------------
        */

        yield Grid::make([
            Column::make([
                ValueMetric::make('Documents')
                    ->value(Document::count()),
            ], colSpan: 3, adaptiveColSpan: 6),

            Column::make([
                ValueMetric::make('Soumis')
                    ->value(
                        Document::where('status', 'submitted')->count()
                    ),
            ], colSpan: 3, adaptiveColSpan: 6),

            Column::make([
                ValueMetric::make('En révision')
                    ->value(
                        Document::where('status', 'under_review')->count()
                    ),
            ], colSpan: 3, adaptiveColSpan: 6),

            Column::make([
                ValueMetric::make('Approuvés')
                    ->value(
                        Document::where('status', 'approved')->count()
                    ),
            ], colSpan: 3, adaptiveColSpan: 6),
        ], gap: 6);

        /*
        |--------------------------------------------------------------------------
        | CHARTS
        |--------------------------------------------------------------------------
        */

        yield Grid::make([
            Column::make([
                DonutChartMetric::make('Répartition des documents')
                    ->values([
                        'Soumis' => Document::where('status', 'submitted')->count(),
                        'En révision' => Document::where('status', 'under_review')->count(),
                        'Approuvés' => Document::where('status', 'approved')->count(),
                        'Publiés' => Document::where('status', 'published')->count(),
                        'Rejetés' => Document::where('status', 'rejected')->count(),
                    ])
                    ->colors([
                        '#2563eb',
                        '#f59e0b',
                        '#22c55e',
                        '#8b5cf6',
                        '#ef4444',
                    ])
                    ->height(300)
                    ->columnSpan(5),
            ], colSpan: 5, adaptiveColSpan: 12),

            Column::make([
                LineChartMetric::make('Évolution des documents')
                    ->series([
                        SeriesItem::make(
                            'Documents créés',
                            Document::query()
                                ->selectRaw(
                                    "DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total"
                                )
                                ->groupBy('month')
                                ->orderBy('month')
                                ->pluck('total', 'month')
                                ->toArray()
                        )
                            ->line()
                            ->color('#2563eb'),
                    ])
                    ->height(300)
                    ->columnSpan(7),
            ], colSpan: 7, adaptiveColSpan: 12),
        ], gap: 6);

        /*
        |--------------------------------------------------------------------------
        | DERNIERS DOCUMENTS
        |--------------------------------------------------------------------------
        */

        yield Box::make([
            Heading::make('Derniers documents'),

            TableBuilder::make()
                ->items(
                    Document::query()
                        ->latest()
                        ->limit(5)
                        ->get()
                )
                ->fields([
                    ID::make(),

                    Text::make('Titre', 'title'),

                    Text::make('Référence', 'reference'),

                    Text::make('Statut', 'status'),
                ])
                ->cast(new ModelCaster(Document::class))
                ->withNotFound(),
        ]);
    }
}