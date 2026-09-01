<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\AuditLog\Pages;

use MoonShine\Laravel\Pages\Crud\DetailPage;

use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;

class AuditLogDetailPage extends DetailPage
{
    /**
     * Détails de l'opération
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),

            Text::make(
                'Utilisateur',
                'user.name'
            ),

            Text::make(
                'Action',
                'action',
                function ($item) {
                    return match ($item->action) {
                        'created' => 'Création',
                        'updated' => 'Modification',
                        'deleted' => 'Suppression',
                        'approved' => 'Approbation',
                        'rejected' => 'Rejet',
                        'submitted' => 'Soumission',
                        default => ucfirst(
                            str_replace('_', ' ', $item->action ?? '')
                        ),
                    };
                }
            ),

            Text::make(
                'Module',
                'module'
            ),

            Text::make(
                'Description',
                'description'
            ),

            Text::make(
                'Adresse IP',
                'ip_address'
            ),

            Date::make(
                'Date de l’action',
                'performed_at'
            )->withTime(),
        ];
    }

    public function getTitle(): string
    {
        return 'Détail de l’audit';
    }
}