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
     * L'utilisateur simple ne peut modifier que son nom et sa photo.
     * L'admin et le responsable peuvent également modifier leur email.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->name = $request->validated('name');

        $canEditEmail = $user->hasRole('administrateur') || $user->hasRole('responsable');

        if ($canEditEmail && $request->filled('email')) {
            $user->email = $request->validated('email');

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }
        }

        $photoChanged = false;

        // Photo de profil (optionnelle) — remplace l'ancienne si une nouvelle est envoyée
        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }

            $path = $request->file('photo')->store('avatars', 'public');
            $user->photo = $path;
            $photoChanged = true;
        }

        $changedFields = collect($user->getDirty())->keys()
            ->reject(fn ($field) => in_array($field, ['email_verified_at']))
            ->values();

        if ($photoChanged) {
            $changedFields->push('photo');
        }

        $user->save();

        // Notification : responsables du département + admins
        if ($changedFields->isNotEmpty()) {
            $this->notifier->userEvent($user, 'updated', $changedFields->implode(', '));
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     * Réservé à l'administrateur uniquement.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user->hasRole('administrateur'), 403);

        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }

        Auth::logout();

        $user->forceDelete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}