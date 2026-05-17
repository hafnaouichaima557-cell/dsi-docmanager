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

        return (new MailMessage)
            ->subject('Document ' . $newLabel . ' : ' . $this->document->title)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Le statut du document "' . $this->document->title . '" a changé.')
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
        return [
            'document_id'   => $this->document->id,
            'document_title'=> $this->document->title,
            'old_status'    => $this->oldStatus,
            'new_status'    => $this->newStatus,
            'comment'       => $this->comment,
            'message'       => 'Statut changé : ' . $this->oldStatus . ' → ' . $this->newStatus,
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}