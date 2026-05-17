<?php

namespace App\Observers;

use App\Models\Document;
use App\Models\AuditLog;

class DocumentObserver
{
    // كي تتخلق وثيقة جديدة
    public function created(Document $document): void
    {
        AuditLog::log(
            action     : 'created',
            module     : 'document',
            description: 'Document créé : ' . $document->title,
            model      : $document,
            newValues  : $document->toArray()
        );
    }

    // كي تتبدل وثيقة
    public function updated(Document $document): void
    {
        AuditLog::log(
            action     : 'updated',
            module     : 'document',
            description: 'Document modifié : ' . $document->title,
            model      : $document,
            oldValues  : $document->getOriginal(),
            newValues  : $document->getDirty()
        );
    }

    // كي تتحذف وثيقة (soft delete)
    public function deleted(Document $document): void
    {
        AuditLog::log(
            action     : 'deleted',
            module     : 'document',
            description: 'Document supprimé : ' . $document->title,
            model      : $document,
            oldValues  : $document->toArray()
        );
    }

    // كي تترجع وثيقة بعد الحذف
    public function restored(Document $document): void
    {
        AuditLog::log(
            action     : 'restored',
            module     : 'document',
            description: 'Document restauré : ' . $document->title,
            model      : $document
        );
    }
}