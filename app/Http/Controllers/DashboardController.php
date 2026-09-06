<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\AuditLog;
use App\Models\WorkflowStep;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Requête des documents : l'admin voit tout, les autres uniquement leur département
        // (basé sur le département du DOCUMENT lui-même, pas celui du créateur).
        $documentsQuery = Document::query();

        if (!$user->isAdmin()) {
            $documentsQuery->whereRaw('LOWER(department) = ?', [strtolower(trim($user->department ?? ''))]);
        }

        // Statistiques
        $totalDocuments = (clone $documentsQuery)->count();

        $pendingDocuments = (clone $documentsQuery)
            ->where('status', 'under_review')
            ->count();

        $approvedDocuments = (clone $documentsQuery)
            ->where('status', 'approved')
            ->count();

        $publishedDocuments = (clone $documentsQuery)
            ->where('status', 'published')
            ->count();

        $rejectedDocuments = (clone $documentsQuery)
            ->where('status', 'rejected')
            ->count();

        // Derniers documents
        $recentDocuments = (clone $documentsQuery)
            ->with('creator', 'category')
            ->latest()
            ->take(5)
            ->get();

        // Derniers audits
        $recentAuditLogs = AuditLog::with('user')
            ->latest('created_at')
            ->take(5)
            ->get();

        // Workflow en cours : uniquement les documents du département de l'utilisateur
        // (l'administrateur voit tous les départements)
        $workflowStepsQuery = WorkflowStep::with('document', 'assignedUser')
            ->where('status', 'in_progress');

        if (!$user->isAdmin()) {
            $workflowStepsQuery->whereHas('document', function ($q) use ($user) {
                $q->whereRaw('LOWER(department) = ?', [strtolower(trim($user->department ?? ''))]);
            });
        }

        $workflowSteps = $workflowStepsQuery->latest()->take(5)->get();

        // Utilisateur le plus actif (global pour l'admin, du département pour les autres)
        $topUserQuery = User::select('users.id', 'users.name', 'users.department')
            ->selectRaw('COUNT(documents.id) as documents_count')
            ->join('documents', 'documents.created_by', '=', 'users.id')
            ->groupBy('users.id', 'users.name', 'users.department')
            ->orderByDesc('documents_count');

        if (!$user->isAdmin()) {
            $topUserQuery->where('users.department', $user->department);
        }

        $topUser = $topUserQuery->first();

        // ===== Graphique principal =====
        // Admin : répartition des documents par département (comparaison globale utile).
        // Responsable / Utilisateur : n'ayant qu'un seul département, on affiche à la place
        // la répartition des documents par utilisateur au sein de LEUR département,
        // pour voir qui a créé le plus de documents.
        if ($user->isAdmin()) {

            $deptQuery = Document::select('department')
                ->selectRaw('COUNT(*) as total')
                ->whereNull('deleted_at')
                ->groupBy('department')
                ->orderByDesc('total');

            $rows = $deptQuery->get();

            $chartTitle  = 'Documents par département';
            $chartLabels = $rows->pluck('department')->map(fn ($d) => $d ?: 'Non défini')->values()->all();
            $chartCounts = $rows->pluck('total')->values()->all();

        } else {

            $rows = User::select('users.id', 'users.name')
                ->selectRaw('COUNT(documents.id) as total')
                ->join('documents', 'documents.created_by', '=', 'users.id')
                ->whereRaw('LOWER(documents.department) = ?', [strtolower(trim($user->department ?? ''))])
                ->whereNull('documents.deleted_at')
                ->groupBy('users.id', 'users.name')
                ->orderByDesc('total')
                ->get();

            $chartTitle  = 'Documents par utilisateur — ' . ($user->department ?? '');
            $chartLabels = $rows->pluck('name')->values()->all();
            $chartCounts = $rows->pluck('total')->values()->all();
        }

        return view('dashboard', compact(
            'totalDocuments',
            'pendingDocuments',
            'approvedDocuments',
            'publishedDocuments',
            'rejectedDocuments',
            'recentDocuments',
            'recentAuditLogs',
            'workflowSteps',
            'topUser',
            'chartTitle',
            'chartLabels',
            'chartCounts'
        ));
    }
}