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

use MoonShine\AssetManager\InlineCss;

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
     * Styles uniquement pour le Dashboard.
     */
    protected function assets(): array
    {
        return [
            ...parent::assets(),

            InlineCss::make(<<<'CSS'

                /* =========================================================
                   TITRE
                ========================================================= */

                .docflow-dashboard-title {
                    margin-bottom: 1.5rem;
                }

                /* =========================================================
                   KPI CARDS
                   Même identité visuelle que l'application principale
                ========================================================= */

                .docflow-kpi {
                    position: relative;
                    border: none !important;
                    border-radius: 16px !important;
                    overflow: hidden;
                    color: #ffffff !important;

                    box-shadow:
                        0 8px 24px rgba(13, 43, 107, 0.20) !important;

                    transition:
                        transform 0.3s cubic-bezier(.4, 0, .2, 1),
                        box-shadow 0.3s ease;
                }

                .docflow-kpi:hover {
                    transform: translateY(-4px);

                    box-shadow:
                        0 16px 32px rgba(13, 43, 107, 0.25) !important;
                }

                /*
                Cercle décoratif haut droit
                */

                .docflow-kpi::before {
                    content: '';
                    position: absolute;

                    top: -30px;
                    right: -30px;

                    width: 120px;
                    height: 120px;

                    border-radius: 50%;

                    background: rgba(255, 255, 255, 0.08);

                    pointer-events: none;
                }

                /*
                Cercle décoratif bas gauche
                */

                .docflow-kpi::after {
                    content: '';
                    position: absolute;

                    bottom: -20px;
                    left: -20px;

                    width: 80px;
                    height: 80px;

                    border-radius: 50%;

                    background: rgba(255, 255, 255, 0.06);

                    pointer-events: none;
                }

                /* =========================================================
                   COULEURS EXACTEMENT COMME L'APPLICATION PRINCIPALE
                ========================================================= */

                /* Total — Bleu */
                .docflow-kpi-blue {
                    background: linear-gradient(
                        135deg,
                        #466BB9 0%,
                        #1A4FA0 100%
                    ) !important;
                }

                /* Validés — Vert */
                .docflow-kpi-green {
                    background: linear-gradient(
                        135deg,
                        #059669 0%,
                        #34D399 100%
                    ) !important;
                }

                /* En relecture — Rose */
                .docflow-kpi-orange {
                    background: linear-gradient(
                        135deg,
                        #DB2777 0%,
                        #F472B6 100%
                    ) !important;
                }

                /* Publiés — Bleu clair */
                .docflow-kpi-cyan {
                    background: linear-gradient(
                        135deg,
                        #0EA5E9 0%,
                        #38BDF8 100%
                    ) !important;
                }

                /* =========================================================
                   TEXTE KPI
                ========================================================= */

                .docflow-kpi h1,
                .docflow-kpi h2,
                .docflow-kpi h3,
                .docflow-kpi h4,
                .docflow-kpi p,
                .docflow-kpi span,
                .docflow-kpi strong,
                .docflow-kpi label {
                    color: #ffffff !important;
                }

                /* =========================================================
                   ICÔNES KPI
                ========================================================= */

                .docflow-kpi .icon {
                    width: 48px !important;
                    height: 48px !important;

                    border-radius: 14px !important;

                    background: rgba(
                        255,
                        255,
                        255,
                        0.18
                    ) !important;

                    color: #ffffff !important;

                    backdrop-filter: blur(4px);
                }

                /* =========================================================
                   CARTES DES GRAPHIQUES
                ========================================================= */

                .docflow-chart {
                    background: #ffffff !important;

                    border: none !important;

                    border-radius: 16px !important;

                    box-shadow:
                        0 2px 16px rgba(13, 43, 107, 0.08)
                        !important;

                    overflow: hidden;
                }

                .docflow-chart h3 {
                    color: #0D2B6B !important;

                    font-weight: 700 !important;
                }

                /* =========================================================
                   INFORMATIONS RAPIDES
                ========================================================= */

                .docflow-info {
                    background: #ffffff !important;

                    border: none !important;

                    border-radius: 16px !important;

                    box-shadow:
                        0 2px 16px rgba(13, 43, 107, 0.08)
                        !important;
                }

                .docflow-info .icon {
                    width: 42px !important;
                    height: 42px !important;

                    border-radius: 12px !important;

                    color: #1A4FA0 !important;

                    background: #E8F0FE !important;
                }

                /* =========================================================
                   TABLEAU
                ========================================================= */

                .docflow-table {
                    background: #ffffff !important;

                    border: none !important;

                    border-radius: 16px !important;

                    box-shadow:
                        0 2px 16px rgba(13, 43, 107, 0.08)
                        !important;

                    overflow: hidden;
                }

                /* =========================================================
                   DARK MODE
                ========================================================= */

                html.dark .docflow-kpi {
                    box-shadow: none !important;
                }

                html.dark .docflow-chart,
                html.dark .docflow-info,
                html.dark .docflow-table {
                    background: #111827 !important;

                    border-color: #1F2937 !important;

                    box-shadow: none !important;
                }

                html.dark .docflow-chart h3 {
                    color: #F1F5F9 !important;
                }

                html.dark .docflow-info .icon {
                    color: #60A5FA !important;

                    background: rgba(
                        37,
                        99,
                        235,
                        0.18
                    ) !important;
                }

                /*
                KPI dark mode
                */

                html.dark .docflow-kpi-blue {
                    background: linear-gradient(
                        135deg,
                        #315FAE 0%,
                        #173F82 100%
                    ) !important;
                }

                html.dark .docflow-kpi-green {
                    background: linear-gradient(
                        135deg,
                        #047857 0%,
                        #16A36A 100%
                    ) !important;
                }

                html.dark .docflow-kpi-orange {
                    background: linear-gradient(
                        135deg,
                        #BE185D 0%,
                        #DB4A91 100%
                    ) !important;
                }

                html.dark .docflow-kpi-cyan {
                    background: linear-gradient(
                        135deg,
                        #0284C7 0%,
                        #0EA5E9 100%
                    ) !important;
                }

            CSS),
        ];
    }

    /**
     * @return list<ComponentContract>
     */
    protected function components(): iterable
    {
        /*
        |--------------------------------------------------------------------------
        | TITRE
        |--------------------------------------------------------------------------
        */

        yield Heading::make(
            'Vue d’ensemble'
        )
            ->class(
                'docflow-dashboard-title'
            );

        /*
        |--------------------------------------------------------------------------
        | KPI PRINCIPAUX
        |--------------------------------------------------------------------------
        */

        yield Grid::make([
            Column::make([
                Box::make([
                    ValueMetric::make(
                        'Total'
                    )
                        ->value(
                            Document::count()
                        )
                        ->icon(
                            'document-text'
                        ),
                ])
                    ->class(
                        'docflow-kpi docflow-kpi-blue'
                    ),
            ], colSpan: 3, adaptiveColSpan: 6),

            Column::make([
                Box::make([
                    ValueMetric::make(
                        'Validés'
                    )
                        ->value(
                            Document::where(
                                'status',
                                'approved'
                            )->count()
                        )
                        ->icon(
                            'check-circle'
                        ),
                ])
                    ->class(
                        'docflow-kpi docflow-kpi-green'
                    ),
            ], colSpan: 3, adaptiveColSpan: 6),

            Column::make([
                Box::make([
                    ValueMetric::make(
                        'En relecture'
                    )
                        ->value(
                            Document::where(
                                'status',
                                'under_review'
                            )->count()
                        )
                        ->icon(
                            'eye'
                        ),
                ])
                    ->class(
                        'docflow-kpi docflow-kpi-orange'
                    ),
            ], colSpan: 3, adaptiveColSpan: 6),

            Column::make([
                Box::make([
                    ValueMetric::make(
                        'Publiés'
                    )
                        ->value(
                            Document::where(
                                'status',
                                'published'
                            )->count()
                        )
                        ->icon(
                            'globe-alt'
                        ),
                ])
                    ->class(
                        'docflow-kpi docflow-kpi-cyan'
                    ),
            ], colSpan: 3, adaptiveColSpan: 6),

        ], gap: 6);

        /*
        |--------------------------------------------------------------------------
        | GRAPHIQUES
        |--------------------------------------------------------------------------
        */

        yield Grid::make([
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
                            '#1A4FA0',
                            '#DB2777',
                            '#059669',
                            '#0EA5E9',
                            '#DC2626',
                        ])
                        ->height(
                            300
                        ),
                ])
                    ->class(
                        'docflow-chart'
                    ),
            ], colSpan: 5, adaptiveColSpan: 12),

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
                                    ->groupBy(
                                        'month'
                                    )
                                    ->orderBy(
                                        'month'
                                    )
                                    ->pluck(
                                        'total',
                                        'month'
                                    )
                                    ->toArray()
                            )
                                ->line()
                                ->color(
                                    '#1A4FA0'
                                ),
                        ])
                        ->height(
                            300
                        ),
                ])
                    ->class(
                        'docflow-chart'
                    ),
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
                    ValueMetric::make(
                        'Workflow en attente'
                    )
                        ->value(
                            WorkflowStep::where(
                                'status',
                                'pending'
                            )->count()
                        )
                        ->icon(
                            'clock'
                        ),
                ])
                    ->class(
                        'docflow-info'
                    ),
            ], colSpan: 4, adaptiveColSpan: 12),

            Column::make([
                Box::make([
                    ValueMetric::make(
                        'Documents approuvés'
                    )
                        ->value(
                            Document::where(
                                'status',
                                'approved'
                            )->count()
                        )
                        ->icon(
                            'check-circle'
                        ),
                ])
                    ->class(
                        'docflow-info'
                    ),
            ], colSpan: 4, adaptiveColSpan: 12),

            Column::make([
                Box::make([
                    ValueMetric::make(
                        'Notifications non lues'
                    )
                        ->value(
                            \Illuminate\Notifications\DatabaseNotification::query()
                                ->whereNull(
                                    'read_at'
                                )
                                ->count()
                        )
                        ->icon(
                            'bell'
                        ),
                ])
                    ->class(
                        'docflow-info'
                    ),
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
                            return match (
                                $item->status
                            ) {
                                'draft'
                                    => 'Brouillon',

                                'submitted'
                                    => 'Soumis',

                                'under_review'
                                    => 'En relecture',

                                'approved'
                                    => 'Approuvé',

                                'published'
                                    => 'Publié',

                                'rejected'
                                    => 'Rejeté',

                                default
                                    => ucfirst(
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
                    )
                        ->withTime(),
                ])
                ->cast(
                    new ModelCaster(
                        Document::class
                    )
                )
                ->withNotFound(),
        ])
            ->class(
                'docflow-table'
            );
    }
}