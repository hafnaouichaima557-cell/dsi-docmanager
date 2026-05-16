<?php

namespace App\Services;

use App\Models\Document;
use App\Models\WorkflowStep;
use App\Models\WorkflowHistory;
use App\Models\AuditLog;
use App\Notifications\DocumentStatusChanged;
use App\Notifications\DocumentPendingApproval;
use Illuminate\Support\Facades\DB;

class WorkflowService
{
    public function submit(Document $document, array $steps): void
    {
        DB::transaction(function () use ($document, $steps) {

            foreach ($steps as $order => $step) {
                WorkflowStep::create([
                    'document_id' => $document->id,
                    'step_order'  => $order + 1,
                    'step_name'   => $step['name'],
                    'assigned_to' => $step['user_id'],
                    'deadline'    => $step['deadline'] ?? null,
                    'status'      => $order === 0 ? 'in_progress' : 'pending',
                ]);
            }

            $document->update([
                'status'       => 'submitted',
                'current_step' => 1,
                'submitted_at' => now(),
            ]);

            $this->logHistory($document, 'submitted', 'draft', 'submitted');

            AuditLog::log(
                action     : 'submitted',
                module     : 'workflow',
                description: 'Document soumis : ' . $document->title,
                model      : $document
            );

            // diag 2 : notification للمسؤول عن الخطوة الأولى
            $firstStep = $document->workflowSteps()->first();
            if ($firstStep && $firstStep->assignedUser) {
                $firstStep->assignedUser->notify(
                    new DocumentPendingApproval($document, $firstStep)
                );
            }
        });
    }

    public function approve(WorkflowStep $step, ?string $comment = null): void
    {
        DB::transaction(function () use ($step, $comment) {

            $step->update([
                'status'   => 'approved',
                'comment'  => $comment,
                'acted_at' => now(),
            ]);

            $document = $step->document;
            $nextStep = $document->workflowSteps()
                                 ->where('step_order', $step->step_order + 1)
                                 ->first();

            if ($nextStep) {
                $nextStep->update(['status' => 'in_progress']);
                $document->update([
                    'status'       => 'under_review',
                    'current_step' => $nextStep->step_order,
                ]);
                $this->logHistory($document, 'approved_step', 'under_review', 'under_review');

                // notification للمسؤول عن الخطوة الجاية
                if ($nextStep->assignedUser) {
                    $nextStep->assignedUser->notify(
                        new DocumentPendingApproval($document, $nextStep)
                    );
                }
            } else {
                $document->update([
                    'status'      => 'approved',
                    'approved_at' => now(),
                ]);
                $this->logHistory($document, 'approved', 'under_review', 'approved');

                // diag 2 : notification لصاحب الوثيقة
                if ($document->creator) {
                    $document->creator->notify(
                        new DocumentStatusChanged($document, 'under_review', 'approved', $comment)
                    );
                }
            }

            AuditLog::log(
                action     : 'approved',
                module     : 'workflow',
                description: 'Étape approuvée : ' . $step->step_name,
                model      : $document
            );
        });
    }

    public function reject(WorkflowStep $step, string $comment): void
    {
        DB::transaction(function () use ($step, $comment) {

            $step->update([
                'status'   => 'rejected',
                'comment'  => $comment,
                'acted_at' => now(),
            ]);

            $document = $step->document;
            $document->update(['status' => 'rejected']);

            $this->logHistory($document, 'rejected', 'under_review', 'rejected');

            // diag 2 : notification لصاحب الوثيقة
            if ($document->creator) {
                $document->creator->notify(
                    new DocumentStatusChanged($document, 'under_review', 'rejected', $comment)
                );
            }

            AuditLog::log(
                action     : 'rejected',
                module     : 'workflow',
                description: 'Étape rejetée : ' . $step->step_name,
                model      : $document
            );
        });
    }

    public function publish(Document $document): void
    {
        DB::transaction(function () use ($document) {

            $document->update([
                'status'       => 'published',
                'published_at' => now(),
            ]);

            $this->logHistory($document, 'published', 'approved', 'published');

            // diag 5 : notification لصاحب الوثيقة
            if ($document->creator) {
                $document->creator->notify(
                    new DocumentStatusChanged($document, 'approved', 'published')
                );
            }

            AuditLog::log(
                action     : 'published',
                module     : 'workflow',
                description: 'Document publié : ' . $document->title,
                model      : $document
            );
        });
    }

    public function disable(Document $document): void
    {
        DB::transaction(function () use ($document) {

            $oldStatus = $document->status;

            $document->update([
                'status'      => 'disabled',
                'disabled_at' => now(),
                'disabled_by' => auth()->id(),
            ]);

            $this->logHistory($document, 'disabled', $oldStatus, 'disabled');

            // diag 3 : notification لصاحب الوثيقة
            if ($document->creator) {
                $document->creator->notify(
                    new DocumentStatusChanged($document, $oldStatus, 'disabled')
                );
            }

            AuditLog::log(
                action     : 'disabled',
                module     : 'workflow',
                description: 'Document désactivé : ' . $document->title,
                model      : $document
            );
        });
    }

    private function logHistory(
        Document $document,
        string $action,
        string $fromStatus,
        string $toStatus,
        ?string $comment = null
    ): void {
        WorkflowHistory::create([
            'document_id'  => $document->id,
            'user_id'      => auth()->id(),
            'action'       => $action,
            'from_status'  => $fromStatus,
            'to_status'    => $toStatus,
            'comment'      => $comment,
            'ip_address'   => request()->ip(),
            'performed_at' => now(),
        ]);
    }
}