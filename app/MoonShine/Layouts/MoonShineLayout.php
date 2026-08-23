<?php

declare(strict_types=1);

namespace App\MoonShine\Layouts;

use App\MoonShine\Pages\Dashboard;
use App\MoonShine\Pages\Workflow;

use App\MoonShine\Resources\Document\DocumentResource;
use App\MoonShine\Resources\Notification\NotificationResource;

use MoonShine\Laravel\Layouts\AppLayout;

use MoonShine\ColorManager\Palettes\PurplePalette;
use MoonShine\Contracts\ColorManager\ColorManagerContract;
use MoonShine\Contracts\ColorManager\PaletteContract;

use MoonShine\MenuManager\MenuGroup;
use MoonShine\MenuManager\MenuItem;

use MoonShine\UI\Components\Layout\Logo;
use App\MoonShine\Resources\AuditLog\AuditLogResource;

final class MoonShineLayout extends AppLayout
{
    /**
     * Palette MoonShine
     */
    protected ?string $palette = PurplePalette::class;

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
     * Menu principal
     */
    protected function menu(): array
    {
        return [
            /*
            |--------------------------------------------------------------------------
            | Dashboard
            |--------------------------------------------------------------------------
            */

            MenuItem::make(
                Dashboard::class,
                'Dashboard'
            ),

            /*
            |--------------------------------------------------------------------------
            | Documents
            |--------------------------------------------------------------------------
            */

            MenuItem::make(
                DocumentResource::class,
                'Documents'
            ),

            /*
            |--------------------------------------------------------------------------
            | Workflow
            |--------------------------------------------------------------------------
            | Page واحدة فقط
            */

            MenuItem::make(
                Workflow::class,
                'Workflow'
            ),

            /*
            |--------------------------------------------------------------------------
            | Notifications
            |--------------------------------------------------------------------------
            */

            MenuItem::make(
                NotificationResource::class,
                'Notifications'
            ),
            MenuItem::make(AuditLogResource::class, 'AuditLogs'),
        ];
    }

    /**
     * Footer menu
     * نخليه فارغ
     */
    protected function getFooterMenu(): array
    {
        return [];
    }

    /**
     * Copyright
     * نحيو Made with ❤️ by CutCode
     */
    protected function getFooterCopyright(): string
    {
        return '';
    }

    /**
     * Couleurs
     */
    protected function colors(
        ColorManagerContract $colorManager
    ): void {
        parent::colors($colorManager);
    }
}
