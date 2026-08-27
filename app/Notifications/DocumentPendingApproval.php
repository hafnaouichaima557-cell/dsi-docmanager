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
        $actorName = auth()->user()->name ?? 'Système';

        return (new MailMessage)
            ->subject('Action requise : ' . $this->document->title)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line($actorName . ' a soumis un document qui nécessite votre validation.')
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
        $actorName = auth()->user()->name ?? 'Système';

        return [
            'document_id'    => $this->document->id,
            'document_title' => $this->document->title,
            'step_id'        => $this->step->id,
            'step_name'      => $this->step->step_name,
            'actor_name'     => $actorName,
            'message'        => $actorName . ' a soumis "' . $this->document->title . '" — en attente de votre validation (' . $this->step->step_name . ')',
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}