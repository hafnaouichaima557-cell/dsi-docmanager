<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Afficher la page "Mot de passe oublié".
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Générer et envoyer un code de vérification de 6 chiffres.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Veuillez saisir votre adresse email.',
            'email.email' => 'Veuillez saisir une adresse email valide.',
        ]);

        $email = Str::lower($request->email);

        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()
                ->withInput()
                ->withErrors([
                    'email' => 'Aucun compte ne correspond à cette adresse email.',
                ]);
        }

        // Vérifier que le compte est actif
        if (!$user->is_active) {
            return back()
                ->withInput()
                ->withErrors([
                    'email' => 'Ce compte est désactivé. Contactez l’administrateur.',
                ]);
        }

        // Génération du code à 6 chiffres
        $code = (string) random_int(100000, 999999);

        // Supprimer les anciens codes pour cet email
        DB::table('password_reset_tokens')
            ->where('email', $email)
            ->delete();

        // Stocker le code sous forme sécurisée
        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => Hash::make($code),
            'created_at' => now(),
        ]);

        // Envoyer le code par email
        Mail::raw(
            "Bonjour {$user->name},\n\n"
            . "Vous avez demandé la réinitialisation de votre mot de passe pour l'application DSI Doc Flow.\n\n"
            . "Votre code de vérification est : {$code}\n\n"
            . "Ce code est valable pendant 10 minutes.\n"
            . "Si vous n'êtes pas à l'origine de cette demande, ignorez simplement cet email.\n\n"
            . "Cordialement,\n"
            . "DSI Doc Flow — icosnet",
            function ($message) use ($email) {
                $message->to($email)
                    ->subject('Code de réinitialisation du mot de passe — DSI Doc Flow');
            }
        );

        // On garde l'email en session (pas en flash) pour qu'il survive
        // à la fois à l'affichage de la page ET à la soumission du code.
        session(['email' => $email]);

        return redirect()
            ->route('password.verify')
            ->with('status', 'Un code de vérification à 6 chiffres a été envoyé à votre adresse email.');
    }
}