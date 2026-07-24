<?php

namespace App\Services;

use App\Models\Document;
use App\Models\User;
use App\Notifications\DocumentActivity;
use App\Notifications\UserActivity;

class NotificationDispatcher
{
    /**
     * Diffuse un événement lié à un document :
     * - à l'utilisateur directement concerné (créateur, assigné à l'étape...)
     * - aux responsables du département du créateur du document
     * - à tous les administrateurs (ils reçoivent absolument tout)
     */
    public function documentEvent(Document $document, string $action, ?User $directUser = null, ?string $comment = null): void
    {
        $actorId = auth()->id();
        $department = optional($document->creator)->department;
        $notifiedIds = [];

        // 1. Utilisateur directement concerné (si ce n'est pas l'auteur de l'action lui-même)
        if ($directUser && $directUser->id !== $actorId) {
            $directUser->notify(new DocumentActivity($document, $action, $comment));
            $notifiedIds[] = $directUser->id;
        }

        // 2. Responsables du département concerné
        if ($department) {
            User::role('responsable')
                ->where('department', $department)
                ->whereNotIn('id', array_merge($notifiedIds, [$actorId]))
                ->get()
                ->each(function ($responsable) use ($document, $action, $comment, &$notifiedIds) {
                    $responsable->notify(new DocumentActivity($document, $action, $comment));
                    $notifiedIds[] = $responsable->id;
                });
        }

        // 3. Tous les administrateurs, sans exception
        User::role('administrateur')
            ->whereNotIn('id', array_merge($notifiedIds, [$actorId]))
            ->get()
            ->each(function ($admin) use ($document, $action, $comment) {
                $admin->notify(new DocumentActivity($document, $action, $comment));
            });
    }

    /**
     * Diffuse un événement lié à un utilisateur (création, modification, désactivation, changement de rôle) :
     * - aux responsables du même département que l'utilisateur concerné
     * - à tous les administrateurs
     */
    public function userEvent(User $targetUser, string $action, ?string $extra = null): void
    {
        $actorId = auth()->id();
        $notifiedIds = [];

        if ($targetUser->department) {
            User::role('responsable')
                ->where('department', $targetUser->department)
                ->whereNotIn('id', array_merge([$actorId, $targetUser->id], $notifiedIds))
                ->get()
                ->each(function ($responsable) use ($targetUser, $action, $extra, &$notifiedIds) {
                    $responsable->notify(new UserActivity($targetUser, $action, $extra));
                    $notifiedIds[] = $responsable->id;
                });
        }

        User::role('administrateur')
            ->whereNotIn('id', array_merge([$actorId, $targetUser->id], $notifiedIds))
            ->get()
            ->each(function ($admin) use ($targetUser, $action, $extra) {
                $admin->notify(new UserActivity($targetUser, $action, $extra));
            });
    }
}