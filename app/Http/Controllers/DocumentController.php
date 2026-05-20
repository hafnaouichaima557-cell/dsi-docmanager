<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentVersion;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    // diag 1 : قائمة الوثائق
    public function index()
    {
        $documents = Document::with('creator', 'category')
                             ->latest()
                             ->paginate(10);

        return view('documents.index', compact('documents'));
    }

    // diag 1 : فورم إضافة وثيقة
    public function create()
    {
        $categories = \App\Models\DocumentCategory::active()->get();
        return view('documents.create', compact('categories'));
    }

    // diag 1 : حفظ وثيقة جديدة
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:document_categories,id',
            'file'        => 'required|file|max:10240',
        ]);

        $document = Document::create([
            'title'       => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'created_by'  => auth()->id(),
            'status'      => 'draft',
        ]);

        // رفع الملف
        if ($request->hasFile('file')) {
            $file    = $request->file('file');
            $path    = $file->store('documents', 'local');

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

        // تسجيل في الأوديت — diag 3
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

    // diag 2 : فورم تعديل
    public function edit(Document $document)
    {
        $this->authorize('update', $document);
        $categories = \App\Models\DocumentCategory::active()->get();
        return view('documents.edit', compact('document', 'categories'));
    }

    // diag 2 : حفظ التعديل
    public function update(Request $request, Document $document)
    {
        $this->authorize('update', $document);

        $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'required|exists:document_categories,id',
            'file'        => 'nullable|file|max:10240',
        ]);

        $oldValues = $document->toArray();

        $document->update([
            'title'       => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'status'      => 'under_review', // diag 2
        ]);

        // نسخة جديدة — diag 2
        if ($request->hasFile('file')) {
            $file    = $request->file('file');
            $version = $document->versions()->count() + 1;
            $path    = $file->store('documents', 'local');

            // إلغاء النسخة الحالية
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

        // تسجيل في الأوديت — diag 2
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

    // diag 3 : تعطيل وثيقة
    public function disable(Request $request, Document $document)
    {
        $this->authorize('disable', $document);

        $document->update([
            'status'      => 'disabled',
            'disabled_at' => now(),
            'disabled_by' => auth()->id(),
        ]);

        // تسجيل في الأوديت — diag 3
        AuditLog::log(
            action     : 'disabled',
            module     : 'document',
            description: 'Document désactivé : ' . $document->title,
            model      : $document
        );

        return redirect()->route('documents.index')
                         ->with('success', 'Document désactivé');
    }

    // diag 5 : نشر وثيقة
    public function publish(Request $request, Document $document)
    {
        $this->authorize('publish', $document);

        $document->update([
            'status'       => 'published',
            'published_at' => now(),
        ]);

        // تسجيل في الأوديت — diag 5
        AuditLog::log(
            action     : 'published',
            module     : 'document',
            description: 'Document publié : ' . $document->title,
            model      : $document
        );

        return redirect()->route('documents.show', $document)
                         ->with('success', 'Document publié avec succès');
    }

    public function destroy(Document $document)
    {
        $this->authorize('delete', $document);
        $document->delete();
        return redirect()->route('documents.index')
                         ->with('success', 'Document supprimé');
    }
}