<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DocumentStatusChanged extends Notification
{
    use Queueable;

    public function __construct(
        private Document $document,
        private string $oldStatus,
        private string $newStatus,
        private ?string $comment = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    // diag 2, 3, 5 : إيميل للمستخدم
    public function toMail(object $notifiable): MailMessage
    {
        $statusLabels = [
            'draft'        => 'Brouillon',
            'submitted'    => 'Soumis',
            'under_review' => 'En révision',
            'approved'     => 'Approuvé',
            'rejected'     => 'Rejeté',
            'published'    => 'Publié',
            'disabled'     => 'Désactivé',
        ];

        $newLabel = $statusLabels[$this->newStatus] ?? $this->newStatus;
        $actorName = auth()->user()->name ?? 'Système';

        return (new MailMessage)
            ->subject('Document ' . $newLabel . ' : ' . $this->document->title)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line($actorName . ' a changé le statut du document "' . $this->document->title . '".')
            ->line('Nouveau statut : ' . $newLabel)
            ->when($this->comment, fn($mail) =>
                $mail->line('Commentaire : ' . $this->comment)
            )
            ->action('Voir le document', url('/documents/' . $this->document->id))
            ->line('Merci d\'utiliser DSI DocManager.');
    }

    // تسجيل في قاعدة البيانات
    public function toDatabase(object $notifiable): array
    {
        $actorName = auth()->user()->name ?? 'Système';

        return [
            'document_id'   => $this->document->id,
            'document_title'=> $this->document->title,
            'old_status'    => $this->oldStatus,
            'new_status'    => $this->newStatus,
            'comment'       => $this->comment,
            'actor_name'    => $actorName,
            'message'       => $actorName . ' : statut changé (' . $this->oldStatus . ' → ' . $this->newStatus . ') pour "' . $this->document->title . '"'
                                . ($this->comment ? ' — ' . $this->comment : ''),
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}