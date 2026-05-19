<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\WorkflowStep;
use App\Services\WorkflowService;
use Illuminate\Http\Request;

class WorkflowController extends Controller
{
    public function __construct(
        private WorkflowService $workflowService
    ) {}

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
}