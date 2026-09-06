<?php

namespace App\Observers;

use App\Models\Document;
use App\Models\AuditLog;

class DocumentObserver
{
    // Les événements de création (created) et modification (updated) ne sont PAS
    // journalisés automatiquement ici : chaque action métier significative
    // (création via le formulaire, mise à jour manuelle, soumission au workflow,
    // approbation, rejet, publication, désactivation...) enregistre déjà elle-même
    // une entrée d'audit précise et explicite dans son propre contrôleur/service.
    // Journaliser aussi ici créerait des doublons et des entrées "modifié"
    // trompeuses à chaque changement de statut interne (ex: passage en workflow).

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