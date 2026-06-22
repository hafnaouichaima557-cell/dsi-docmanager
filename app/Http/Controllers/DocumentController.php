<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentVersion;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DocumentController extends Controller
{
    use AuthorizesRequests;

    // قائمة الوثائق
    public function index()
    {
        $documents = Document::with('creator', 'category')
                             ->latest()
                             ->paginate(10);

        return view('documents.index', compact('documents'));
    }

    // فورم إضافة وثيقة
    public function create()
    {
        return view('documents.create');
    }

    // حفظ وثيقة جديدة
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority'    => 'nullable|in:low,normal,high,urgent',
            'file'        => 'required|file|mimes:pdf,docx,xlsx|max:10240',
        ], [
            'title.required' => 'Le titre est obligatoire.',
            'file.required'  => 'Veuillez joindre un fichier.',
            'file.mimes'     => 'Le fichier doit être PDF, DOCX ou XLSX.',
        ]);

        $document = Document::create([
            'title'       => $request->title,
            'description' => $request->description,
            'priority'    => $request->priority ?? 'normal',
            'created_by'  => auth()->id(),
            'status'      => 'draft',
            'category_id' => $request->category_id ?? 1,
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
        return view('documents.edit', compact('document'));
    }

    // حفظ التعديل
    public function update(Request $request, Document $document)
    {
        $this->authorize('update', $document);

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority'    => 'nullable|in:low,normal,high,urgent',
            'file'        => 'nullable|file|mimes:pdf,docx,xlsx|max:10240',
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

        return redirect()->route('documents.show', $document)
                         ->with('success', 'Document publié avec succès');
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