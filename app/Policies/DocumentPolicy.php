<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;

class DocumentPolicy
{
    // كل مستخدم مسجل يقدر يشوف
    public function view(User $user, Document $document): bool
    {
        return true;
    }

    // كل مستخدم يقدر يخلق وثيقة
    public function create(User $user): bool
    {
        return true;
    }

    // diag 2 : فقط صاحب الوثيقة أو admin
    public function update(User $user, Document $document): bool
    {
        return $user->id === $document->created_by
            || $user->isAdmin()
            || $user->isResponsable();
    }

    // diag 3 : فقط responsable أو admin
    public function disable(User $user, Document $document): bool
    {
        return $user->isAdmin()
            || $user->isResponsable();
    }

    // diag 5 : فقط responsable أو admin
    public function publish(User $user, Document $document): bool
    {
        return ($user->isAdmin() || $user->isResponsable())
            && $document->canBePublished();
    }

    // فقط admin يحذف
    public function delete(User $user, Document $document): bool
    {
        return $user->isAdmin();
    }
}