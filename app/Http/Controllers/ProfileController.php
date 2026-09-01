<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\NotificationDispatcher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
     * Seuls le nom et la photo de profil peuvent être modifiés
     * (mot de passe et suppression de compte désactivés).
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->name = $request->validated('name');

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

        $changedFields = collect($user->getDirty())->keys()->values();

        if ($photoChanged) {
            $changedFields->push('photo');
        }

        $user->save();

        // Notification : responsables du département + admins
        if ($changedFields->isNotEmpty()) {
            $this->notifier->userEvent($user, 'updated', $changedFields->implode(', '));
        }

        return redirect()->route('profile.edit')->with('status', 'profile-updated');
    }
}