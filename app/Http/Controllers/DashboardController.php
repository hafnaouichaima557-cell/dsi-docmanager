<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\AuditLog;
use App\Models\WorkflowStep;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Requête de base
        $documentsQuery = Document::query();

        // Responsable et utilisateur voient uniquement les documents
        // créés par les utilisateurs de leur département
        if (
            auth()->user()->hasRole('responsable') ||
            auth()->user()->hasRole('utilisateur')
        ) {
            $documentsQuery->whereHas('creator', function ($q) {
                $q->where('department', auth()->user()->department);
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

        // Audit logs
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

        // Utilisateur le plus actif
        $topUserQuery = User::select('users.id', 'users.name', 'users.department')
            ->selectRaw('COUNT(documents.id) as documents_count')
            ->join('documents', 'documents.created_by', '=', 'users.id')
            ->groupBy('users.id', 'users.name', 'users.department');

        if (
            auth()->user()->hasRole('responsable') ||
            auth()->user()->hasRole('utilisateur')
        ) {
            $topUserQuery->where('users.department', auth()->user()->department);
        }

        $topUser = $topUserQuery
            ->orderByDesc('documents_count')
            ->first();

        return view('dashboard', compact(
            'totalDocuments',
            'pendingDocuments',
            'approvedDocuments',
            'publishedDocuments',
            'rejectedDocuments',
            'recentDocuments',
            'recentAuditLogs',
            'workflowSteps',
            'topUser'
        ));
    }
}