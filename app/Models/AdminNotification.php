<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Notifications\DatabaseNotification;

class AdminNotification extends DatabaseNotification
{
    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'App\\Notifications\\DocumentActivity'
                => 'Activité document',

            'App\\Notifications\\DocumentPendingApproval'
                => 'Approbation requise',

            'App\\Notifications\\DocumentStatusChanged'
                => 'Changement de statut',

            'App\\Notifications\\UserActivity'
                => 'Activité utilisateur',

            default
                => class_basename((string) $this->type),
        };
    }

    public function getMessageLabelAttribute(): string
    {
        return $this->data['message'] ?? 'Notification système';
    }

    public function getRecipientNameAttribute(): string
    {
        $user = $this->notifiable;

        if ($user && isset($user->name)) {
            return $user->name;
        }

        return 'Utilisateur';
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->read_at ? 'Lue' : 'Non lue';
    }
}
