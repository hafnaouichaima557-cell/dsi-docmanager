<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserActivity extends Notification
{
    use Queueable;

    public function __construct(
        private User $targetUser,
        private string $action,
        private ?string $extra = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $labels = [
            'created'      => 'créé',
            'updated'      => 'modifié',
            'disabled'     => 'désactivé',
            'role_changed' => 'rôle modifié',
        ];

        $label = $labels[$this->action] ?? $this->action;
        $message = 'Utilisateur ' . $label . ' : ' . $this->targetUser->name;

        if ($this->extra) {
            $message .= ' (' . $this->extra . ')';
        }

        return [
            'user_id'   => $this->targetUser->id,
            'user_name' => $this->targetUser->name,
            'action'    => $this->action,
            'extra'     => $this->extra,
            'message'   => $message,
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}