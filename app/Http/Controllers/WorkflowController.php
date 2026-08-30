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

    // Compare 2 departments sans tenir compte de la casse (ex: "DSI" === "dsi")
    private function sameDepartment(?string $a, ?string $b): bool
    {
        return $a !== null && $b !== null && strtolower(trim($a)) === strtolower(trim($b));
    }

    // قائمة الـ workflow — chaque utilisateur ne voit QUE les documents de son
    // propre département (l'administrateur voit tous les départements).
    // Les étapes "Validation responsable" du circuit département (step_order 2,
    // sans utilisateur assigné) n'apparaissent PAS ici : elles vivent uniquement
    // sur la page dédiée "Validation".
    public function index()
    {
        $user = auth()->user();

        $query = WorkflowStep::with(['document', 'assignedUser'])
                    ->where('status', 'in_progress')
                    ->where(function ($q) {
                        $q->where('step_order', '!=', 2)
                          ->orWhereNotNull('assigned_to');
                    })
                    ->whereHas('document', function ($q) use ($user) {
                        $q->where('created_by', '!=', $user->id);
                    });

        if (!$user->isAdmin()) {
            $query->whereHas('document', function ($q) use ($user) {
                $q->whereRaw('LOWER(department) = ?', [strtolower(trim($user->department ?? ''))]);
            });
        }

        $steps = $query->latest()->paginate(10);

        return view('workflow.index', compact('steps'));
    }

    // Page dédiée : documents en attente de validation finale (responsable/admin uniquement)
    public function pendingValidation()
    {
        $user = auth()->user();
        abort_unless($user->hasRole('responsable') || $user->isAdmin(), 403);

        $query = WorkflowStep::with(['document', 'document.creator'])
                    ->where('step_order', 2)
                    ->where('status', 'in_progress');

        if (!$user->isAdmin()) {
            $query->whereHas('document', function ($q) use ($user) {
                $q->whereRaw('LOWER(department) = ?', [strtolower(trim($user->department ?? ''))]);
            });
        }

        $steps = $query->latest()->paginate(10);

        return view('workflow.validation', compact('steps'));
    }

    // diag 2 : تبعث الوثيقة للworkflow (manuel, khtwa b khtwa)
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

    // Bouton "Soumettre au workflow" (circuit département automatique)
    public function submitDepartment(Document $document)
    {
        if (!$document->canBeSubmitted()) {
            return back()->with('error', 'Ce document ne peut pas être soumis.');
        }

        $this->workflowService->submitDepartmentWorkflow($document);

        return redirect()->route('documents.show', $document)
                         ->with('success', 'Document soumis au circuit de validation.');
    }

    // Validation #1 — n'importe qui nfes department (comparaison insensible à la casse), machi creator
    public function validateDepartmentStep(Request $request, WorkflowStep $step)
    {
        $user = auth()->user();
        $document = $step->document;

        abort_unless($step->step_order == 1, 403);
        abort_unless($step->status === 'in_progress', 403, 'Étape déjà traitée.');
        abort_unless($this->sameDepartment($user->department, $document->department), 403, 'Département différent.');
        abort_unless($user->id !== $document->created_by, 403, 'Vous ne pouvez pas valider votre propre document.');

        $this->workflowService->approve($step, $request->comment);

        return back()->with('success', 'Document validé, en attente de validation responsable.');
    }

    // Validation #2 — Admin (tous départements) ou Responsable DU MÊME département.
    public function validateResponsableStep(Request $request, WorkflowStep $step)
    {
        $user = auth()->user();
        $document = $step->document;

        abort_unless($step->step_order == 2, 403);
        abort_unless($step->status === 'in_progress', 403, 'Étape déjà traitée.');

        $isSameDeptResponsable = $user->hasRole('responsable') && $this->sameDepartment($user->department, $document->department);

        abort_unless($user->isAdmin() || $isSameDeptResponsable, 403);

        $this->workflowService->approve($step, $request->comment);

        return redirect()->route('documents.show', $document)
                         ->with('success', 'Document validé. Vous pouvez maintenant le publier.');
    }

    // Rejet à l'étape département (step 1)
    public function rejectDepartmentStep(Request $request, WorkflowStep $step)
    {
        $request->validate(['comment' => 'required|string|max:500']);

        $user = auth()->user();
        $document = $step->document;

        abort_unless($step->step_order == 1, 403);
        abort_unless($step->status === 'in_progress', 403, 'Étape déjà traitée.');
        abort_unless($this->sameDepartment($user->department, $document->department), 403, 'Département différent.');
        abort_unless($user->id !== $document->created_by, 403, 'Vous ne pouvez pas rejeter votre propre document.');

        $this->workflowService->reject($step, $request->comment);

        return back()->with('success', 'Document rejeté.');
    }

    // Rejet à l'étape responsable (step 2)
    public function rejectResponsableStep(Request $request, WorkflowStep $step)
    {
        $request->validate(['comment' => 'required|string|max:500']);

        $user = auth()->user();
        $document = $step->document;

        abort_unless($step->step_order == 2, 403);
        abort_unless($step->status === 'in_progress', 403, 'Étape déjà traitée.');

        $isSameDeptResponsable = $user->hasRole('responsable') && $this->sameDepartment($user->department, $document->department);

        abort_unless($user->isAdmin() || $isSameDeptResponsable, 403);

        $this->workflowService->reject($step, $request->comment);

        return back()->with('success', 'Document rejeté.');
    }

    // diag 2 : قبول خطوة (manuel)
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

        $this->workflowService->publish($document);

        return redirect()->route('documents.show', $document)
                         ->with('success', 'Document validé et publié avec succès');
    }
}