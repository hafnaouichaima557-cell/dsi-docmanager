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
        $documentsQuery = Document::query();

        if (!$user->isAdmin()) {
            $documentsQuery->whereHas('creator', function ($q) use ($user) {
                $q->where('department', $user->department);
            });
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

        // Workflow
        $workflowSteps = WorkflowStep::with('document', 'assignedUser')
            ->where('status', 'in_progress')
            ->latest()
            ->take(5)
            ->get();

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

        // ===== Documents par département =====
        // Nombre total de documents créés, groupés par département du créateur
        $documentsByDepartment = User::select('users.department')
            ->selectRaw('COUNT(documents.id) as total')
            ->join('documents', 'documents.created_by', '=', 'users.id')
            ->whereNull('documents.deleted_at')
            ->groupBy('users.department')
            ->orderByDesc('total')
            ->get();

        $departmentLabels = $documentsByDepartment->pluck('department')->map(function ($d) {
            return $d ?: 'Non défini';
        })->values()->all();

        $departmentCounts = $documentsByDepartment->pluck('total')->values()->all();

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
            'departmentLabels',
            'departmentCounts'
        ));
    }
}