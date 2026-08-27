<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DocumentActivity extends Notification
{
    use Queueable;

    public function __construct(
        private Document $document,
        private string $action,
        private ?string $comment = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $labels = [
            'created'   => 'créé',
            'updated'   => 'modifié',
            'submitted' => 'soumis',
            'approved'  => 'approuvé',
            'rejected'  => 'rejeté',
            'published' => 'publié',
            'disabled'  => 'désactivé',
        ];

        $label = $labels[$this->action] ?? $this->action;
        $actorName = auth()->user()->name ?? 'Système';

        return [
            'document_id'    => $this->document->id,
            'document_title' => $this->document->title,
            'action'         => $this->action,
            'comment'        => $this->comment,
            'actor_name'     => $actorName,
            'message'        => $actorName . ' a ' . $label . ' le document : ' . $this->document->title
                                 . ($this->comment ? ' — ' . $this->comment : ''),
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}