<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;

class DocumentPolicy
{
    // كل مستخدم مسجل يقدر يشوف قائمة الوثائق
    public function viewAny(User $user): bool
    {
        return true;
    }

    // كل مستخدم مسجل يقدر يشوف وثيقة
    public function view(User $user, Document $document): bool
    {
        return true;
    }

    // كل مستخدم يقدر ينشئ وثيقة
    public function create(User $user): bool
    {
        return true;
    }

    // فقط صاحب الوثيقة أو admin أو responsable
    public function update(User $user, Document $document): bool
    {
        return $user->id === $document->created_by
            || $user->isAdmin()
            || $user->isResponsable();
    }

    public function disable(User $user, Document $document): bool
    {
        return $user->isAdmin()
            || $user->isResponsable();
    }

    public function publish(User $user, Document $document): bool
    {
        return ($user->isAdmin() || $user->isResponsable())
            && $document->canBePublished();
    }

    public function delete(User $user, Document $document): bool
    {
        return $user->isAdmin();
    }
}