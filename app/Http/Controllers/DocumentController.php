<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentVersion;
use App\Models\DocumentCategory;
use App\Models\AuditLog;
use App\Models\User;
use App\Services\NotificationDispatcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DocumentController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private NotificationDispatcher $notifier
    ) {}

    // قائمة الوثائق
    public function index(Request $request)
    {
        $user = auth()->user();

        // ===== Admin : affichage par département =====
        // Si l'admin n'a pas encore choisi un département, on lui montre
        // la liste des départements avec le nombre de documents de chacun.
        if ($user->isAdmin() && !$request->filled('department')) {

            $departments = User::select('department')
                ->whereNotNull('department')
                ->distinct()
                ->pluck('department');

            $departmentsWithCount = $departments->map(function ($dept) {
                $count = Document::where('department', $dept)->count();

                return [
                    'name'  => $dept,
                    'count' => $count,
                ];
            })->sortByDesc('count')->values();

            return view('documents.departments', compact('departmentsWithCount'));
        }

        $query = Document::with('creator', 'category')->latest();

        $selectedDepartment = null;

        if ($user->hasRole('responsable') || $user->hasRole('utilisateur')) {
            // Responsable / utilisateur : toujours filtré sur son propre département
            $selectedDepartment = $user->department;

            $query->where('department', $user->department);

        } elseif ($user->isAdmin() && $request->filled('department')) {
            // Admin qui a choisi un département précis
            $selectedDepartment = $request->department;

            $query->where('department', $selectedDepartment);
        }

        // بحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                  ->orWhere('reference', 'like', '%'.$search.'%')
                  ->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        // فلترة بالحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $documents = $query->paginate(10)->withQueryString();

        return view('documents.index', compact('documents', 'selectedDepartment'));
    }

    // فورم إضافة وثيقة
    public function create(Request $request)
    {
        $categories = DocumentCategory::all();

        // Département cible : celui choisi par l'admin (depuis l'URL),
        // sinon le département de l'utilisateur connecté
        $targetDepartment = auth()->user()->isAdmin() && $request->filled('department')
            ? $request->department
            : auth()->user()->department;

        return view('documents.create', compact('categories', 'targetDepartment'));
    }

    // حفظ وثيقة جديدة
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority'    => 'nullable|in:low,normal,high,urgent',
            'file'        => 'required|file|mimes:pdf,docx,xlsx,doc,xls,pptx,ppt,png,jpg,jpeg,txt,csv,zip|max:10240',
        ], [
            'title.required' => 'Le titre est obligatoire.',
            'file.required'  => 'Veuillez joindre un fichier.',
            'file.mimes'     => 'Type de fichier non autorisé. Formats acceptés : PDF, DOCX, XLSX, DOC, XLS, PPTX, PPT, PNG, JPG, TXT, CSV, ZIP.',
        ]);

        // Département du document : celui choisi par l'admin (si fourni),
        // sinon le département de l'utilisateur connecté
        $department = auth()->user()->isAdmin() && $request->filled('department')
            ? $request->department
            : auth()->user()->department;

        $document = Document::create([
            'title'       => $request->title,
            'description' => $request->description,
            'priority'    => $request->priority ?? 'normal',
            'created_by'  => auth()->id(),
            'status'      => 'draft',
            'category_id' => $request->category_id ?? null,
            'department'  => $department,
        ]);

        // رفع الملف
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('documents', 'local');

            DocumentVersion::create([
                'document_id'    => $document->id,
                'version_number' => 1,
                'file_path'      => $path,
                'file_name'      => $file->getClientOriginalName(),
                'file_type'      => $file->getClientOriginalExtension(),
                'file_size'      => $file->getSize(),
                'checksum'       => hash_file('sha256', $file->getRealPath()),
                'uploaded_by'    => auth()->id(),
                'is_current'     => true,
            ]);
        }

        // تسجيل في الأوديت
        AuditLog::log(
            action     : 'created',
            module     : 'document',
            description: 'Document créé : ' . $document->title,
            model      : $document
        );

        // Notification : responsables du département + admins
        $this->notifier->documentEvent($document, 'created');

        return redirect()->route('documents.show', $document)
                         ->with('success', 'Document créé avec succès');
    }

    // تفاصيل وثيقة
    public function show(Document $document)
    {
        $document->load('creator', 'category', 'versions', 'workflowSteps.assignedUser');
        return view('documents.show', compact('document'));
    }

    // فورم تعديل
    public function edit(Document $document)
    {
        $this->authorize('update', $document);
        $categories = DocumentCategory::all();
        return view('documents.edit', compact('document', 'categories'));
    }

    // حفظ التعديل
    public function update(Request $request, Document $document)
    {
        $this->authorize('update', $document);

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority'    => 'nullable|in:low,normal,high,urgent',
            'file'        => 'nullable|file|mimes:pdf,docx,xlsx,doc,xls,pptx,ppt,png,jpg,jpeg,txt,csv,zip|max:10240',
        ], [
            'file.mimes' => 'Type de fichier non autorisé. Formats acceptés : PDF, DOCX, XLSX, DOC, XLS, PPTX, PPT, PNG, JPG, TXT, CSV, ZIP.',
        ]);

        $oldValues = $document->toArray();

        $document->update([
            'title'       => $request->title,
            'description' => $request->description,
            'priority'    => $request->priority ?? $document->priority,
            'status'      => 'under_review',
        ]);

        // نسخة جديدة
        if ($request->hasFile('file')) {
            $file    = $request->file('file');
            $version = $document->versions()->count() + 1;
            $path    = $file->store('documents', 'local');

            $document->versions()->update(['is_current' => false]);

            DocumentVersion::create([
                'document_id'    => $document->id,
                'version_number' => $version,
                'file_path'      => $path,
                'file_name'      => $file->getClientOriginalName(),
                'file_type'      => $file->getClientOriginalExtension(),
                'file_size'      => $file->getSize(),
                'checksum'       => hash_file('sha256', $file->getRealPath()),
                'uploaded_by'    => auth()->id(),
                'change_notes'   => $request->change_notes,
                'is_current'     => true,
            ]);
        }

        AuditLog::log(
            action     : 'updated',
            module     : 'document',
            description: 'Document modifié : ' . $document->title,
            model      : $document,
            oldValues  : $oldValues,
            newValues  : $document->fresh()->toArray()
        );

        // Notification : créateur (si ce n'est pas lui qui modifie) + responsables + admins
        $this->notifier->documentEvent($document, 'updated', $document->creator);

        return redirect()->route('documents.show', $document)
                         ->with('success', 'Document mis à jour');
    }

    // تعطيل وثيقة
    public function disable(Request $request, Document $document)
    {
        $this->authorize('disable', $document);

        $document->update([
            'status'      => 'disabled',
            'disabled_at' => now(),
            'disabled_by' => auth()->id(),
        ]);

        AuditLog::log(
            action     : 'disabled',
            module     : 'document',
            description: 'Document désactivé : ' . $document->title,
            model      : $document
        );

        // Notification : créateur + responsables + admins
        $this->notifier->documentEvent($document, 'disabled', $document->creator);

        return redirect()->route('documents.index')
                         ->with('success', 'Document désactivé');
    }

    // نشر وثيقة
    public function publish(Request $request, Document $document)
    {
        $this->authorize('publish', $document);

        $document->update([
            'status'       => 'published',
            'published_at' => now(),
        ]);

        AuditLog::log(
            action     : 'published',
            module     : 'document',
            description: 'Document publié : ' . $document->title,
            model      : $document
        );

        // Notification : créateur + responsables + admins
        $this->notifier->documentEvent($document, 'published', $document->creator);

        return redirect()->route('documents.show', $document)
                         ->with('success', 'Document publié avec succès');
    }

    // عرض / فتح الملف (PDF, DOCX, XLSX, etc.) في المتصفح
    public function voir(Document $document)
    {
        $version = $document->versions()->where('is_current', true)->first();

        if (!$version || !Storage::disk('local')->exists($version->file_path)) {
            return back()->with('error', 'Fichier introuvable.');
        }

       // return response()->file(storage_path('app/' . $version->file_path)); document return
        return response()->file(Storage::disk('local')->path($version->file_path));
    }

    // حذف وثيقة
    public function destroy(Document $document)
    {
        $this->authorize('delete', $document);
        $document->delete();
        return redirect()->route('documents.index')
                         ->with('success', 'Document supprimé');
    }
}