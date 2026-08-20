<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\WorkflowStep;
use App\Models\AuditLog;
use App\Services\WorkflowService;
use App\Services\NotificationDispatcher;
use Illuminate\Http\Request;

class WorkflowController extends Controller
{
    public function __construct(
        private WorkflowService $workflowService,
        private NotificationDispatcher $notifier
    ) {}

    // قائمة الـ workflow
    public function index()
    {
        $steps = \App\Models\WorkflowStep::with(['document', 'assignedUser'])
                    ->where('status', 'in_progress')
                    ->whereHas('document', function ($query) {
                        $query->where('created_by', '!=', auth()->id());
                    })
                    ->latest()
                    ->paginate(10);
        return view('workflow.index', compact('steps'));
    }

    // diag 2 : تبعث الوثيقة للworkflow
    public function submit(Request $request, Document $document)
    {
        $request->validate([
            'steps'                => 'required|array|min:1',
            'steps.*.user_id'      => 'required|exists:users,id',
            'steps.*.name'         => 'required|string',
            'steps.*.deadline'     => 'nullable|date',
        ]);

        if (!$document->canBeSubmitted()) {
            return back()->with('error', 'Ce document ne peut pas être soumis');
        }

        $this->workflowService->submit($document, $request->steps);

        return redirect()->route('documents.show', $document)
                         ->with('success', 'Document soumis pour validation');
    }

    // diag 2 : قبول خطوة
    public function approve(Request $request, WorkflowStep $step)
    {
        $request->validate([
            'comment' => 'nullable|string|max:500',
        ]);

        $this->workflowService->approve($step, $request->comment);

        return redirect()->route('documents.show', $step->document_id)
                         ->with('success', 'Étape approuvée');
    }

    // diag 2 : رفض خطوة
    public function reject(Request $request, WorkflowStep $step)
    {
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        $this->workflowService->reject($step, $request->comment);

        return redirect()->route('documents.show', $step->document_id)
                         ->with('success', 'Étape rejetée');
    }

    // diag 5 : نشر
    public function publish(Request $request, Document $document)
    {
        if (!$document->canBePublished()) {
            return back()->with('error', 'Ce document ne peut pas être publié');
        }

        $this->workflowService->publish($document);

        return redirect()->route('documents.show', $document)
                         ->with('success', 'Document publié avec succès');
    }

    /**
     * Validation rapide réservée à l'administrateur : approuve et publie
     * le document en une seule action, sans passer par un circuit de
     * validation manuel avec d'autres utilisateurs.
     */
    public function quickApprove(Request $request, Document $document)
    {
        $user = auth()->user();

        if (!$user->isAdmin()) {
            abort(403, "Seul l'administrateur peut utiliser la validation rapide.");
        }

        if (!in_array($document->status, ['draft', 'submitted', 'under_review', 'rejected'])) {
            return back()->with('error', 'Ce document ne peut pas être validé rapidement dans son état actuel.');
        }

        $oldStatus = $document->status;

        // Étape de workflow symbolique, assignée à l'admin lui-même, déjà approuvée
        $step = WorkflowStep::create([
            'document_id' => $document->id,
            'step_order'  => 1,
            'step_name'   => 'Validation administrateur',
            'assigned_to' => $user->id,
            'status'      => 'approved',
            'comment'     => 'Validation rapide par l\'administrateur',
            'acted_at'    => now(),
        ]);

        $document->update([
            'status'       => 'approved',
            'current_step' => 1,
            'submitted_at' => $document->submitted_at ?? now(),
            'approved_at'  => now(),
        ]);

        AuditLog::log(
            action     : 'approved',
            module     : 'workflow',
            description: 'Validation rapide (admin) : ' . $document->title,
            model      : $document,
            oldValues  : ['status' => $oldStatus],
            newValues  : ['status' => 'approved']
        );

        $this->notifier->documentEvent($document, 'approved', $document->creator);

        // Publication immédiate à la suite de la validation
        $this->workflowService->publish($document);

        return redirect()->route('documents.show', $document)
                         ->with('success', 'Document validé et publié avec succès');
    }
}