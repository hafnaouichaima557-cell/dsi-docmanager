<?php

namespace App\Notifications;

use App\Models\Document;
use App\Models\WorkflowStep;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DocumentPendingApproval extends Notification
{
    use Queueable;

    public function __construct(
        private Document $document,
        private WorkflowStep $step
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    // diag 2 : إيميل للمسؤول عن الخطوة
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Action requise : ' . $this->document->title)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Un document nécessite votre validation.')
            ->line('Document : ' . $this->document->title)
            ->line('Étape : ' . $this->step->step_name)
            ->when($this->step->deadline, fn($mail) =>
                $mail->line('Date limite : ' . $this->step->deadline->format('d/m/Y'))
            )
            ->action('Valider le document', url('/documents/' . $this->document->id))
            ->line('Merci d\'utiliser DSI DocManager.');
    }

    // تسجيل في قاعدة البيانات
    public function toDatabase(object $notifiable): array
    {
        return [
            'document_id'    => $this->document->id,
            'document_title' => $this->document->title,
            'step_id'        => $this->step->id,
            'step_name'      => $this->step->step_name,
            'message'        => 'Document en attente de validation : ' . $this->document->title,
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}