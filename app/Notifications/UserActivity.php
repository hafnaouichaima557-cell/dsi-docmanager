<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
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
        return ['database', 'mail'];
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

    public function toMail(object $notifiable): MailMessage
    {
        $labels = [
            'created'      => 'créé',
            'updated'      => 'modifié',
            'disabled'     => 'désactivé',
            'role_changed' => 'rôle modifié',
        ];

        $label = $labels[$this->action] ?? $this->action;

        $mail = (new MailMessage)
            ->subject('DSI DocManager — Utilisateur ' . $label . ' : ' . $this->targetUser->name)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Utilisateur ' . $label . ' : ' . $this->targetUser->name);

        if ($this->extra) {
            $mail->line('Détails : ' . $this->extra);
        }

        $mail->action('Voir les utilisateurs', route('users.index'))
             ->line('Merci d\'utiliser DSI DocManager.');

        return $mail;
    }
}