<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
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

        // ===== Admin : affichage par département =====
        // Si l'admin n'a pas encore choisi un département, on lui montre
        // la liste des départements avec le nombre de logs de chacun.
        if ($user->hasRole('administrateur') && !$request->filled('department')) {

            $departments = User::select('department')
                ->whereNotNull('department')
                ->distinct()
                ->pluck('department');

            $departmentsWithCount = $departments->map(function ($dept) {
                $count = AuditLog::whereHas('user', function ($q) use ($dept) {
                    $q->where('department', $dept);
                })->count();

                return [
                    'name'  => $dept,
                    'count' => $count,
                ];
            })->sortByDesc('count')->values();

            return view('audit.departments', compact('departmentsWithCount'));
        }

        $query = AuditLog::with('user')->latest('performed_at');

        $selectedDepartment = null;

        // Responsable : voit uniquement les logs de son département
        // et ne voit jamais les logs des administrateurs
        if ($user->hasRole('responsable')) {

            $selectedDepartment = $user->department;

            $query->whereHas('user', function ($q) use ($user) {

                // Même département
                $q->where('department', $user->department);

                // Exclure les administrateurs
                $q->whereDoesntHave('roles', function ($role) {
                    $role->where('name', 'administrateur');
                });
            });

        } elseif ($user->hasRole('administrateur') && $request->filled('department')) {
            // Admin qui a choisi un département précis
            $selectedDepartment = $request->department;

            $query->whereHas('user', function ($q) use ($selectedDepartment) {
                $q->where('department', $selectedDepartment);
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

        $logs = $query->paginate(20)->withQueryString();

        $modules = AuditLog::distinct()->pluck('module');

        return view('audit.index', compact('logs', 'modules', 'selectedDepartment'));
    }
}