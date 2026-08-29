<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\Document;
use App\Models\User;
use App\Models\WorkflowStep;

use MoonShine\Apexcharts\Components\DonutChartMetric;
use MoonShine\Apexcharts\Components\LineChartMetric;
use MoonShine\Apexcharts\Support\SeriesItem;

use MoonShine\Contracts\UI\ComponentContract;

use MoonShine\Laravel\Pages\Page;
use MoonShine\Laravel\TypeCasts\ModelCaster;

use MoonShine\UI\Components\Heading;

use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Layout\Column;
use MoonShine\UI\Components\Layout\Grid;

use MoonShine\UI\Components\Metrics\Wrapped\ValueMetric;

use MoonShine\UI\Components\Table\TableBuilder;

use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;

#[\MoonShine\MenuManager\Attributes\SkipMenu]
class Dashboard extends Page
{
    public function getBreadcrumbs(): array
    {
        return [
            '#' => 'Dashboard',
        ];
    }

    public function getTitle(): string
    {
        return 'Dashboard';
    }

    /**
     * @return list<ComponentContract>
     */
    protected function components(): iterable
    {
        /*
        |--------------------------------------------------------------------------
        | EN-TÊTE
        |--------------------------------------------------------------------------
        */

        yield Heading::make('Vue d’ensemble');

        /*
        |--------------------------------------------------------------------------
        | KPI PRINCIPAUX
        |--------------------------------------------------------------------------
        */

        yield Grid::make([
            Column::make([
                ValueMetric::make('Total')
                    ->value(
                        Document::count()
                    ),
            ], colSpan: 3, adaptiveColSpan: 6),

            Column::make([
                ValueMetric::make('Validés')
                    ->value(
                        Document::where(
                            'status',
                            'approved'
                        )->count()
                    ),
            ], colSpan: 3, adaptiveColSpan: 6),

            Column::make([
                ValueMetric::make('En relecture')
                    ->value(
                        Document::where(
                            'status',
                            'under_review'
                        )->count()
                    ),
            ], colSpan: 3, adaptiveColSpan: 6),

            Column::make([
                ValueMetric::make('Publiés')
                    ->value(
                        Document::where(
                            'status',
                            'published'
                        )->count()
                    ),
            ], colSpan: 3, adaptiveColSpan: 6),

        ], gap: 6);

        /*
        |--------------------------------------------------------------------------
        | GRAPHIQUES
        |--------------------------------------------------------------------------
        */

        yield Grid::make([
            /*
            | Répartition des documents
            */

            Column::make([
                Box::make([
                    Heading::make(
                        'Répartition des documents'
                    ),

                    DonutChartMetric::make(
                        'Répartition'
                    )
                        ->values([
                            'Soumis' => Document::where(
                                'status',
                                'submitted'
                            )->count(),

                            'En révision' => Document::where(
                                'status',
                                'under_review'
                            )->count(),

                            'Approuvés' => Document::where(
                                'status',
                                'approved'
                            )->count(),

                            'Publiés' => Document::where(
                                'status',
                                'published'
                            )->count(),

                            'Rejetés' => Document::where(
                                'status',
                                'rejected'
                            )->count(),
                        ])
                        ->colors([
                            '#2563EB',
                            '#F59E0B',
                            '#16A34A',
                            '#0EA5E9',
                            '#DC2626',
                        ])
                        ->height(300),
                ]),
            ], colSpan: 5, adaptiveColSpan: 12),

            /*
            | Évolution des documents
            */

            Column::make([
                Box::make([
                    Heading::make(
                        'Évolution des documents'
                    ),

                    LineChartMetric::make(
                        'Documents créés'
                    )
                        ->series([
                            SeriesItem::make(
                                'Documents créés',
                                Document::query()
                                    ->selectRaw(
                                        "DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total"
                                    )
                                    ->groupBy('month')
                                    ->orderBy('month')
                                    ->pluck(
                                        'total',
                                        'month'
                                    )
                                    ->toArray()
                            )
                                ->line()
                                ->color('#2563EB'),
                        ])
                        ->height(300),
                ]),
            ], colSpan: 7, adaptiveColSpan: 12),

        ], gap: 6);

        /*
        |--------------------------------------------------------------------------
        | INFORMATIONS RAPIDES
        |--------------------------------------------------------------------------
        */

        yield Grid::make([
            Column::make([
                Box::make([
                    Heading::make('Workflow'),

                    ValueMetric::make(
                        'En attente'
                    )
                        ->value(
                            WorkflowStep::where(
                                'status',
                                'pending'
                            )->count()
                        ),
                ]),
            ], colSpan: 4, adaptiveColSpan: 12),

            Column::make([
                Box::make([
                    Heading::make(
                        'Documents approuvés'
                    ),

                    ValueMetric::make(
                        'Approuvés'
                    )
                        ->value(
                            Document::where(
                                'status',
                                'approved'
                            )->count()
                        ),
                ]),
            ], colSpan: 4, adaptiveColSpan: 12),

            Column::make([
                Box::make([
                    Heading::make(
                        'Notifications'
                    ),

                    ValueMetric::make(
                        'Non lues'
                    )
                        ->value(
                            \Illuminate\Notifications\DatabaseNotification::query()
                                ->whereNull('read_at')
                                ->count()
                        ),
                ]),
            ], colSpan: 4, adaptiveColSpan: 12),

        ], gap: 6);

        /*
        |--------------------------------------------------------------------------
        | DERNIERS DOCUMENTS
        |--------------------------------------------------------------------------
        */

        yield Box::make([
            Heading::make(
                'Derniers documents'
            ),

            TableBuilder::make()
                ->items(
                    Document::query()
                        ->latest()
                        ->limit(5)
                        ->get()
                )
                ->fields([
                    ID::make(),

                    Text::make(
                        'Titre',
                        'title'
                    ),

                    Text::make(
                        'Référence',
                        'reference'
                    ),

                    Text::make(
                        'Statut',
                        'status',
                        function ($item) {
                            return match ($item->status) {
                                'draft' => 'Brouillon',
                                'submitted' => 'Soumis',
                                'under_review' => 'En relecture',
                                'approved' => 'Approuvé',
                                'published' => 'Publié',
                                'rejected' => 'Rejeté',
                                default => ucfirst(
                                    str_replace(
                                        '_',
                                        ' ',
                                        $item->status ?? ''
                                    )
                                ),
                            };
                        }
                    ),

                    Date::make(
                        'Créé le',
                        'created_at'
                    )->withTime(),
                ])
                ->cast(
                    new ModelCaster(
                        Document::class
                    )
                )
                ->withNotFound(),
        ]);
    }
}