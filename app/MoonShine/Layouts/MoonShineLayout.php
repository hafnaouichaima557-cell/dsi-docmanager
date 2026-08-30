<?php

declare(strict_types=1);

namespace App\MoonShine\Layouts;

use App\MoonShine\Pages\Dashboard;
use App\MoonShine\Pages\Workflow;

use App\MoonShine\Palettes\DocFlowPalette;

use App\MoonShine\Resources\AuditLog\AuditLogResource;
use App\MoonShine\Resources\Document\DocumentResource;
use App\MoonShine\Resources\Notification\NotificationResource;
use App\MoonShine\Resources\User\UserResource;

use MoonShine\Laravel\Layouts\AppLayout;
use MoonShine\MenuManager\MenuItem;
use MoonShine\UI\Components\Layout\Favicon;
use MoonShine\UI\Components\Layout\Logo;

final class MoonShineLayout extends AppLayout
{
    /**
     * Palette personnalisée Doc Flow
     */
    protected ?string $palette = DocFlowPalette::class;

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
}