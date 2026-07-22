<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Seuls les administrateurs et les responsables peuvent accéder
        if (! $user->hasRole('administrateur') && ! $user->hasRole('responsable')) {
            abort(403, 'Accès refusé.');
        }

        $query = AuditLog::with('user')->latest('performed_at');

        // Responsable : voit uniquement les logs de son département
        // et ne voit jamais les logs des administrateurs
        if ($user->hasRole('responsable')) {

            $query->whereHas('user', function ($q) use ($user) {

                // Même département
                $q->where('department', $user->department);

                // Exclure les administrateurs
                $q->whereDoesntHave('roles', function ($role) {
                    $role->where('name', 'administrateur');
                });
            });
        }

        // Filtre par module
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        // Filtre par utilisateur
        if ($request->filled('user_id')) {

            $query->where('user_id', $request->user_id);

            if ($user->hasRole('responsable')) {

                $query->whereHas('user', function ($q) use ($user) {

                    $q->where('department', $user->department);

                    $q->whereDoesntHave('roles', function ($role) {
                        $role->where('name', 'administrateur');
                    });
                });
            }
        }

        // Filtre par date
        if ($request->filled('date')) {
            $query->whereDate('performed_at', $request->date);
        }

        $logs = $query->paginate(20);

        $modules = AuditLog::distinct()->pluck('module');

        return view('audit.index', compact('logs', 'modules'));
    }
}