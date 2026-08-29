<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;

class DocumentPolicy
{
    // Compare 2 departments sans tenir compte de la casse (ex: "DSI" === "dsi")
    private function sameDepartment(?string $a, ?string $b): bool
    {
        return $a !== null && $b !== null && strtolower(trim($a)) === strtolower(trim($b));
    }

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

    // صاحب الوثيقة، admin، responsable، أو أي مستخدم من نفس القسم (département)
    public function update(User $user, Document $document): bool
    {
        return $user->id === $document->created_by
            || $user->isAdmin()
            || $user->isResponsable()
            || $this->sameDepartment($user->department, $document->department);
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