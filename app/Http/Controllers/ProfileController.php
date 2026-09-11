<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\NotificationDispatcher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        private NotificationDispatcher $notifier
    ) {}

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     *
     * User simple :
     * - nom
     * - photo
     *
     * Responsable / Administrateur :
     * - nom
     * - email
     * - photo
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Modifier le nom
        $user->name = $request->validated('name');

        /*
         * Seuls le Responsable et l'Administrateur
         * peuvent modifier leur adresse email.
         */
        $canEditEmail =
            $user->hasRole('administrateur') ||
            $user->hasRole('responsable');

        if ($canEditEmail && $request->filled('email')) {

            $newEmail = $request->validated('email');

            if ($newEmail !== $user->email) {
                $user->email = $newEmail;

                // L'email devra être vérifié à nouveau
                $user->email_verified_at = null;
            }
        }

        // Gestion de la photo
        $photoChanged = false;

        if ($request->hasFile('photo')) {

            // Supprimer l'ancienne photo
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }

            // Enregistrer la nouvelle photo
            $path = $request->file('photo')
                ->store('avatars', 'public');

            $user->photo = $path;

            $photoChanged = true;
        }

        // Récupérer les champs modifiés
        $changedFields = collect($user->getDirty())
            ->keys()
            ->reject(function ($field) {
                return $field === 'email_verified_at';
            })
            ->values();

        if ($photoChanged && !$changedFields->contains('photo')) {
            $changedFields->push('photo');
        }

        // Sauvegarder
        $user->save();

        // Notification
        if ($changedFields->isNotEmpty()) {
            $this->notifier->userEvent(
                $user,
                'updated',
                $changedFields->implode(', ')
            );
        }

        return Redirect::route('profile.edit')
            ->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     *
     * Seul l'Administrateur peut supprimer son compte.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        /*
         * Suppression réservée à l'Administrateur.
         */
        abort_unless(
            $user->hasRole('administrateur'),
            403
        );

        // Vérification du mot de passe
        $request->validateWithBag('userDeletion', [
            'password' => [
                'required',
                'current_password',
            ],
        ]);

        // Supprimer la photo
        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }

        // Déconnexion
        Auth::logout();

        // Suppression définitive du compte
        $user->forceDelete();

        // Invalider la session
        $request->session()->invalidate();

        // Nouveau token CSRF
        $request->session()->regenerateToken();

        // Retour à l'accueil
        return Redirect::to('/');
    }
}