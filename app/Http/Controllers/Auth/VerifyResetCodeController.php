<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class VerifyResetCodeController extends Controller
{
    /**
     * Afficher la page de vérification du code.
     */
    public function create(Request $request): View|RedirectResponse
    {
        $email = session('email');

        if (!$email) {
            return redirect()
                ->route('password.request')
                ->withErrors([
                    'email' => 'Veuillez d’abord demander un code de vérification.',
                ]);
        }

        return view('auth.verify-code', [
            'email' => $email,
        ]);
    }

    /**
     * Vérifier le code à 6 chiffres.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => [
                'required',
                'digits:6',
            ],
        ], [
            'code.required' => 'Veuillez saisir le code reçu par email.',
            'code.digits' => 'Le code doit contenir exactement 6 chiffres.',
        ]);

        $email = session('email');

        if (!$email) {
            return redirect()
                ->route('password.request')
                ->withErrors([
                    'email' => 'Votre session a expiré. Veuillez recommencer.',
                ]);
        }

        $reset = DB::table('password_reset_tokens')
            ->where('email', Str::lower($email))
            ->first();

        if (!$reset) {
            return back()->withErrors([
                'code' => 'Code invalide ou expiré.',
            ]);
        }

        // Le code est valable pendant 10 minutes
        if (!$reset->created_at ||
            now()->diffInMinutes($reset->created_at) >= 10
        ) {
            DB::table('password_reset_tokens')
                ->where('email', $email)
                ->delete();

            return redirect()
                ->route('password.request')
                ->withErrors([
                    'email' => 'Le code a expiré. Veuillez demander un nouveau code.',
                ]);
        }

        // Vérification du code
        if (!Hash::check($request->code, $reset->token)) {
            return back()->withErrors([
                'code' => 'Le code de vérification est incorrect.',
            ]);
        }

        // Code correct
        session([
            'password_reset_verified' => true,
            'password_reset_email' => $email,
        ]);

        return redirect()->route('password.reset.form');
    }
}