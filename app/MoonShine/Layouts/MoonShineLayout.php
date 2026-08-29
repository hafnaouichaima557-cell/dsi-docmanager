<?php

declare(strict_types=1);

namespace App\MoonShine\Layouts;

use App\MoonShine\Pages\Dashboard;
use App\MoonShine\Pages\Workflow;

use App\MoonShine\Resources\AuditLog\AuditLogResource;
use App\MoonShine\Resources\Document\DocumentResource;
use App\MoonShine\Resources\Notification\NotificationResource;
use App\MoonShine\Resources\User\UserResource;

use MoonShine\Contracts\ColorManager\ColorManagerContract;
use MoonShine\Laravel\Layouts\AppLayout;
use MoonShine\MenuManager\MenuItem;
use MoonShine\UI\Components\Layout\Favicon;
use MoonShine\UI\Components\Layout\Logo;

final class MoonShineLayout extends AppLayout
{
    /**
     * Assets
     */
    protected function assets(): array
    {
        return [
            ...parent::assets(),
        ];
    }

    /**
     * Logo icosnet
     */
    protected function getLogoComponent(): Logo
    {
        return Logo::make(
            '/admin',
            '/images/logo-icosnet.png',
            '/images/logo-icosnet.png',
            'icosnet - Doc Flow'
        );
    }

    /**
     * Favicon icosnet
     */
    protected function getFaviconComponent(): Favicon
    {
        return parent::getFaviconComponent()->customAssets([
            'apple-touch' => '/images/logo-icosnet.png',
            '32' => '/images/logo-icosnet.png',
            '16' => '/images/logo-icosnet.png',
            'safari-pinned-tab' => '/images/logo-icosnet.png',
        ]);
    }

    /**
     * Menu principal
     */
    protected function menu(): array
    {
        return [
            MenuItem::make(
                Dashboard::class,
                'Dashboard',
                'squares-2x2'
            ),

            MenuItem::make(
                DocumentResource::class,
                'Documents',
                'document-text'
            ),

            MenuItem::make(
                Workflow::class,
                'Workflow',
                'arrow-path'
            ),

            MenuItem::make(
                UserResource::class,
                'Utilisateurs',
                'users'
            ),

            MenuItem::make(
                NotificationResource::class,
                'Notifications',
                'bell'
            ),

            MenuItem::make(
                AuditLogResource::class,
                'Audit',
                'clipboard-document-list'
            ),
        ];
    }

    /**
     * Footer vide
     */
    protected function getFooterMenu(): array
    {
        return [];
    }

    /**
     * Supprimer le copyright MoonShine
     */
    protected function getFooterCopyright(): string
    {
        return '';
    }

    /**
     * Thème Doc Flow
     */
    protected function colors(
        ColorManagerContract $colorManager
    ): void {
        parent::colors($colorManager);

        /*
        |--------------------------------------------------------------------------
        | MODE CLAIR
        |--------------------------------------------------------------------------
        */

        $colorManager
            ->primary('#2563EB')
            ->secondary('#7C3AED')
            ->successBg('#16A34A')
            ->warningBg('#F59E0B')
            ->errorBg('#DC2626')
            ->infoBg('#0EA5E9');

        /*
        |--------------------------------------------------------------------------
        | MODE SOMBRE
        |--------------------------------------------------------------------------
        */

        $colorManager
            ->primary(
                '#60A5FA',
                dark: true
            )
            ->secondary(
                '#A78BFA',
                dark: true
            )
            ->successBg(
                '#22C55E',
                dark: true
            )
            ->warningBg(
                '#FBBF24',
                dark: true
            )
            ->errorBg(
                '#F87171',
                dark: true
            )
            ->infoBg(
                '#38BDF8',
                dark: true
            );

        /*
        |--------------------------------------------------------------------------
        | FONDS DU PANNEAU — DARK MODE
        |--------------------------------------------------------------------------
        */

        $colorManager
            ->set(
                'body',
                '0.11 0.02 250',
                dark: true
            )
            ->theme(
                [
                    'body' => '1 0 0',
                    'stroke' => '1 0 0 / 10%',
                    'default' => '0.23 0.02 250',
                    900 => '0.15 0.025 250',
                ],
                dark: true
            );
    }
}