<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Afficher la page de nouveau mot de passe.
     */
    public function create(Request $request): View|RedirectResponse
    {
        if (
            !session('password_reset_verified') ||
            !session('password_reset_email')
        ) {
            return redirect()
                ->route('password.request')
                ->withErrors([
                    'email' => 'Veuillez vérifier votre code avant de continuer.',
                ]);
        }

        return view('auth.reset-password', [
            'email' => session('password_reset_email'),
        ]);
    }

    /**
     * Enregistrer le nouveau mot de passe.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        if (
            !session('password_reset_verified') ||
            !session('password_reset_email')
        ) {
            return redirect()
                ->route('password.request')
                ->withErrors([
                    'email' => 'Votre session a expiré. Veuillez recommencer.',
                ]);
        }

        $request->validate([
            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults(),
            ],
        ], [
            'password.required' => 'Veuillez saisir un nouveau mot de passe.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        $email = session('password_reset_email');

        $user = User::where('email', $email)->first();

        if (!$user) {
            session()->forget([
                'password_reset_verified',
                'password_reset_email',
                'email',
            ]);

            return redirect()
                ->route('password.request')
                ->withErrors([
                    'email' => 'Utilisateur introuvable.',
                ]);
        }

        // Modifier le mot de passe
        $user->forceFill([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60),
        ])->save();

        event(new PasswordReset($user));

        // Supprimer le code utilisé
        DB::table('password_reset_tokens')
            ->where('email', $email)
            ->delete();

        // Nettoyer la session
        session()->forget([
            'password_reset_verified',
            'password_reset_email',
            'email',
        ]);

        return redirect()
            ->route('login')
            ->with('status', 'Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter.');
    }
}