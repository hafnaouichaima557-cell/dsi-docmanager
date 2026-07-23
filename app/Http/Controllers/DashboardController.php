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
        // Requête des documents du département de l'utilisateur connecté
        $documentsQuery = Document::query()
            ->whereHas('creator', function ($q) {
                $q->where('department', auth()->user()->department);
            });

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

        // Utilisateur le plus actif du département
        $topUser = User::select('users.id', 'users.name', 'users.department')
            ->selectRaw('COUNT(documents.id) as documents_count')
            ->join('documents', 'documents.created_by', '=', 'users.id')
            ->where('users.department', auth()->user()->department)
            ->groupBy('users.id', 'users.name', 'users.department')
            ->orderByDesc('documents_count')
            ->first();

        // ===== Documents créés vs Documents traités (approuvés/publiés) par mois =====
        // Remplace l'ancien graphique "Derniers documents" : on regarde les 7 derniers mois
        $months = collect(range(6, 0))->map(function ($i) {
            return Carbon::now()->subMonths($i)->startOfMonth();
        });

        $baseQuery = Document::query()->whereHas('creator', function ($q) {
            $q->where('department', auth()->user()->department);
        });

        $ticketsCreated = $months->map(function ($month) use ($baseQuery) {
            return (clone $baseQuery)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        })->values()->all();

        $ticketsSolved = $months->map(function ($month) use ($baseQuery) {
            return (clone $baseQuery)
                ->whereIn('status', ['approved', 'published'])
                ->whereYear('updated_at', $month->year)
                ->whereMonth('updated_at', $month->month)
                ->count();
        })->values()->all();

        $ticketsLabels = $months->map(function ($month) {
            return ucfirst($month->translatedFormat('M'));
        })->values()->all();

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
            'ticketsCreated',
            'ticketsSolved',
            'ticketsLabels'
        ));
    }
}