<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\WorkflowStep;

use Illuminate\Database\Eloquent\Builder;

use MoonShine\AssetManager\InlineCss;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Laravel\Pages\Page;
use MoonShine\Support\Enums\Color;

use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Components\FlexibleRender;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Table\TableBuilder;

use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\Text;

class Workflow extends Page
{
    public function getBreadcrumbs(): array
    {
        return [
            '/admin' => 'Dashboard',
            '#' => 'Workflow',
        ];
    }

    public function getTitle(): string
    {
        return 'Workflow';
    }

    /**
     * CSS uniquement pour cette page.
     */
    protected function onLoad(): void
    {
        parent::onLoad();

        $this->getAssetManager()->add(
            InlineCss::make(<<<'CSS'
                /* =====================================================
                   WORKFLOW DOC FLOW
                ===================================================== */

                .df-workflow-page {
                    background: #f4f7fb !important;
                    border: 1px solid #dce5f2 !important;
                    border-radius: 18px !important;
                    padding: 22px !important;
                }

                .df-workflow-header {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 20px;
                    margin-bottom: 22px;
                }

                .df-workflow-title {
                    display: flex;
                    align-items: center;
                    gap: 13px;
                }

                .df-workflow-title-icon {
                    width: 46px;
                    height: 46px;
                    border-radius: 13px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    background: linear-gradient(135deg, #1749a8, #2563eb);
                    color: white;
                    box-shadow: 0 8px 18px rgba(37, 99, 235, .22);
                }

                .df-workflow-title h1 {
                    margin: 0;
                    color: #123b7a;
                    font-size: 24px;
                    font-weight: 700;
                }

                .df-workflow-subtitle {
                    margin: 4px 0 0;
                    color: #667085;
                    font-size: 13px;
                }

                .df-workflow-back {
                    display: inline-flex;
                    align-items: center;
                    gap: 7px;
                    padding: 8px 13px;
                    border-radius: 9px;
                    background: white;
                    color: #1749a8 !important;
                    border: 1px solid #cbdaf1;
                    font-size: 12px;
                    font-weight: 600;
                    text-decoration: none !important;
                    transition: .2s ease;
                }

                .df-workflow-back:hover {
                    background: #1749a8;
                    color: white !important;
                    border-color: #1749a8;
                }

                .df-workflow-stats {
                    display: grid;
                    grid-template-columns: repeat(4, minmax(0, 1fr));
                    gap: 14px;
                    margin-bottom: 18px;
                }

                .df-workflow-stat {
                    position: relative;
                    overflow: hidden;
                    border-radius: 14px;
                    padding: 16px;
                    min-height: 90px;
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    border: 1px solid transparent;
                }

                .df-workflow-stat-icon {
                    width: 40px;
                    height: 40px;
                    border-radius: 11px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex-shrink: 0;
                }

                .df-workflow-stat strong {
                    display: block;
                    font-size: 24px;
                    line-height: 1;
                    margin-bottom: 5px;
                }

                .df-workflow-stat span {
                    font-size: 12px;
                    font-weight: 600;
                }

                .df-pending {
                    background: #eef2f7;
                    color: #475467;
                    border-color: #d9e0ea;
                }

                .df-pending .df-workflow-stat-icon {
                    background: #64748b;
                    color: white;
                }

                .df-progress {
                    background: #fff3df;
                    color: #a15c00;
                    border-color: #f7d79c;
                }

                .df-progress .df-workflow-stat-icon {
                    background: #e98a00;
                    color: white;
                }

                .df-approved {
                    background: #e5f8ef;
                    color: #087443;
                    border-color: #bde9d2;
                }

                .df-approved .df-workflow-stat-icon {
                    background: #129b5a;
                    color: white;
                }

                .df-rejected {
                    background: #fde9e9;
                    color: #b42318;
                    border-color: #f5c2c0;
                }

                .df-rejected .df-workflow-stat-icon {
                    background: #d92d20;
                    color: white;
                }

                .df-workflow-search {
                    background: white;
                    border: 1px solid #dce5f2;
                    border-radius: 14px;
                    padding: 14px;
                    margin-bottom: 18px;
                }

                .df-workflow-search form {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    margin: 0;
                }

                .df-workflow-search-wrap {
                    position: relative;
                    flex: 1;
                }

                .df-workflow-search-wrap svg {
                    position: absolute;
                    left: 12px;
                    top: 50%;
                    transform: translateY(-50%);
                    color: #7b8da8;
                    pointer-events: none;
                }

                .df-workflow-search input {
                    width: 100%;
                    height: 40px;
                    border: 1px solid #d4deed;
                    border-radius: 9px;
                    padding: 0 13px 0 38px;
                    outline: none;
                    background: #f8fafc;
                    color: #1d2939;
                    font-size: 12px;
                }

                .df-workflow-search input:focus {
                    background: white;
                    border-color: #3576df;
                    box-shadow: 0 0 0 3px rgba(53, 118, 223, .11);
                }

                .df-search-button {
                    height: 40px;
                    border: 0;
                    border-radius: 9px;
                    padding: 0 16px;
                    background: linear-gradient(135deg, #1749a8, #2563eb);
                    color: white;
                    font-size: 12px;
                    font-weight: 600;
                    cursor: pointer;
                    display: inline-flex;
                    align-items: center;
                    gap: 7px;
                }

                .df-search-button:hover {
                    background: #123b7a;
                }

                .df-workflow-table {
                    background: white !important;
                    border: 1px solid #dce5f2 !important;
                    border-radius: 14px !important;
                    overflow: hidden;
                }

                @media (max-width: 900px) {
                    .df-workflow-stats {
                        grid-template-columns: repeat(2, minmax(0, 1fr));
                    }

                    .df-workflow-header {
                        align-items: flex-start;
                        flex-direction: column;
                    }
                }

                @media (max-width: 600px) {
                    .df-workflow-stats {
                        grid-template-columns: 1fr;
                    }

                    .df-workflow-search form {
                        flex-direction: column;
                    }

                    .df-workflow-search-wrap,
                    .df-search-button {
                        width: 100%;
                    }
                }
            CSS)
        );
    }

    /**
     * @return list<ComponentContract>
     */
    protected function components(): iterable
    {
        $search = trim(
            (string) request()->query('q', '')
        );

        /*
        |--------------------------------------------------------------------------
        | COUNTS
        |--------------------------------------------------------------------------
        */

        $pending = WorkflowStep::where(
            'status',
            'pending'
        )->count();

        $inProgress = WorkflowStep::where(
            'status',
            'in_progress'
        )->count();

        $approved = WorkflowStep::where(
            'status',
            'approved'
        )->count();

        $rejected = WorkflowStep::where(
            'status',
            'rejected'
        )->count();

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        $query = WorkflowStep::query()
            ->with([
                'document',
                'assignedUser',
            ]);

        if ($search !== '') {
            $query->where(function (Builder $q) use ($search) {
                $q->whereHas(
                    'document',
                    function (Builder $documentQuery) use ($search) {
                        $documentQuery
                            ->where(
                                'title',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'reference',
                                'like',
                                "%{$search}%"
                            );
                    }
                )
                ->orWhere(
                    'step_name',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'status',
                    'like',
                    "%{$search}%"
                )
                ->orWhereHas(
                    'assignedUser',
                    function (Builder $userQuery) use ($search) {
                        $userQuery->where(
                            'name',
                            'like',
                            "%{$search}%"
                        );
                    }
                );
            });
        }

        $items = $query
            ->latest('acted_at')
            ->latest('id')
            ->limit(25)
            ->get()
            ->map(function (WorkflowStep $step) {
                return [
                    'document' =>
                        $step->document?->title ?? '—',

                    'reference' =>
                        $step->document?->reference ?? '—',

                    'step' =>
                        $step->step_name ?? '—',

                    'assigned' =>
                        $step->assignedUser?->name
                        ?? 'Non assigné',

                    'status' => match ($step->status) {
                        'pending' => 'En attente',
                        'in_progress' => 'En cours',
                        'approved' => 'Approuvé',
                        'rejected' => 'Rejeté',
                        default => (string) $step->status,
                    },

                    'deadline' =>
                        $step->deadline,

                    'acted_at' =>
                        $step->acted_at,
                ];
            });

        /*
        |--------------------------------------------------------------------------
        | PAGE
        |--------------------------------------------------------------------------
        */

        yield Box::make([
            /*
            |--------------------------------------------------------------------------
            | HEADER
            |--------------------------------------------------------------------------
            */

            FlexibleRender::make(
                '
                <div class="df-workflow-header">

                    <div class="df-workflow-title">
                        <div class="df-workflow-title-icon">
                            <svg width="22" height="22" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round">
                                <path d="M12 2v4"/>
                                <path d="M12 18v4"/>
                                <path d="M4.93 4.93l2.83 2.83"/>
                                <path d="M16.24 16.24l2.83 2.83"/>
                                <path d="M2 12h4"/>
                                <path d="M18 12h4"/>
                                <path d="M4.93 19.07l2.83-2.83"/>
                                <path d="M16.24 7.76l2.83-2.83"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </div>

                        <div>
                            <h1>Workflow</h1>
                            <p class="df-workflow-subtitle">
                                Documents en attente de validation
                            </p>
                        </div>
                    </div>

                    <a class="df-workflow-back" href="/admin">
                        <svg width="15" height="15" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round">
                            <path d="M19 12H5"/>
                            <path d="M12 19l-7-7 7-7"/>
                        </svg>
                        Retour au Dashboard
                    </a>

                </div>
                '
            ),

            /*
            |--------------------------------------------------------------------------
            | STATISTICS
            |--------------------------------------------------------------------------
            */

            FlexibleRender::make(
                '
                <div class="df-workflow-stats">

                    <div class="df-workflow-stat df-pending">
                        <div class="df-workflow-stat-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round">
                                <circle cx="12" cy="12" r="9"/>
                                <path d="M12 7v5l3 2"/>
                            </svg>
                        </div>
                        <div>
                            <strong>' . $pending . '</strong>
                            <span>En attente</span>
                        </div>
                    </div>

                    <div class="df-workflow-stat df-progress">
                        <div class="df-workflow-stat-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round">
                                <path d="M3 12a9 9 0 0 1 15.5-6.3L21 8"/>
                                <path d="M21 3v5h-5"/>
                                <path d="M21 12a9 9 0 0 1-15.5 6.3L3 16"/>
                                <path d="M3 21v-5h5"/>
                            </svg>
                        </div>
                        <div>
                            <strong>' . $inProgress . '</strong>
                            <span>En cours</span>
                        </div>
                    </div>

                    <div class="df-workflow-stat df-approved">
                        <div class="df-workflow-stat-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round">
                                <circle cx="12" cy="12" r="9"/>
                                <path d="m9 12 2 2 4-4"/>
                            </svg>
                        </div>
                        <div>
                            <strong>' . $approved . '</strong>
                            <span>Approuvé</span>
                        </div>
                    </div>

                    <div class="df-workflow-stat df-rejected">
                        <div class="df-workflow-stat-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round">
                                <circle cx="12" cy="12" r="9"/>
                                <path d="m9 9 6 6"/>
                                <path d="m15 9-6 6"/>
                            </svg>
                        </div>
                        <div>
                            <strong>' . $rejected . '</strong>
                            <span>Rejeté</span>
                        </div>
                    </div>

                </div>
                '
            ),

            /*
            |--------------------------------------------------------------------------
            | SEARCH
            |--------------------------------------------------------------------------
            */

            FlexibleRender::make(
                '
                <div class="df-workflow-search">
                    <form method="GET"
                          action="/admin/page/workflow">

                        <div class="df-workflow-search-wrap">

                            <svg width="16" height="16"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2"
                                 stroke-linecap="round"
                                 stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"/>
                                <path d="m21 21-4.3-4.3"/>
                            </svg>

                            <input
                                type="text"
                                name="q"
                                value="' . e($search) . '"
                                placeholder="Rechercher un document, une référence, une étape ou un responsable..."
                            >
                        </div>

                        <button type="submit"
                                class="df-search-button">

                            <svg width="15" height="15"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2"
                                 stroke-linecap="round"
                                 stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"/>
                                <path d="m21 21-4.3-4.3"/>
                            </svg>

                            Rechercher
                        </button>

                    </form>
                </div>
                '
            ),

            /*
            |--------------------------------------------------------------------------
            | TABLE UNIQUE
            |--------------------------------------------------------------------------
            */

            Box::make([
                TableBuilder::make()
                    ->items($items)
                    ->fields([
                        Text::make(
                            'Document',
                            'document'
                        ),

                        Text::make(
                            'Référence',
                            'reference'
                        ),

                        Text::make(
                            'Étape',
                            'step'
                        ),

                        Text::make(
                            'Assigné à',
                            'assigned'
                        ),

                        Text::make(
                            'Statut',
                            'status'
                        )->prettyLimit(
                            color: fn ($value) => match ($value) {
                                'En attente' =>
                                    Color::SECONDARY,

                                'En cours' =>
                                    Color::WARNING,

                                'Approuvé' =>
                                    Color::SUCCESS,

                                'Rejeté' =>
                                    Color::ERROR,

                                default =>
                                    Color::SECONDARY,
                            },
                            limit: 101
                        ),

                        Date::make(
                            'Deadline',
                            'deadline'
                        ),

                        Date::make(
                            'Dernière action',
                            'acted_at'
                        ),
                    ])
                    ->withNotFound(),
            ])->setAttribute(
                'class',
                'df-workflow-table'
            ),

        ])->setAttribute(
            'class',
            'df-workflow-page'
        );
    }
}
