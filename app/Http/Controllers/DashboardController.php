<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\AuditLog;
use App\Models\WorkflowStep;

class DashboardController extends Controller
{
    public function index()
    {
        $totalDocuments     = Document::count();
        $pendingDocuments   = Document::where('status', 'under_review')->count();
        $approvedDocuments  = Document::where('status', 'approved')->count();
        $publishedDocuments = Document::where('status', 'published')->count();
        $rejectedDocuments  = Document::where('status', 'rejected')->count();

        $recentDocuments = Document::with('creator', 'category')
                                   ->latest()
                                   ->take(5)
                                   ->get();

        $recentAuditLogs = AuditLog::with('user')
                                   ->latest('created_at')
                                   ->take(5)
                                   ->get();

        $workflowSteps = WorkflowStep::with('document', 'assignedUser')
                                     ->where('status', 'in_progress')
                                     ->latest()
                                     ->take(5)
                                     ->get();

        return view('dashboard', compact(
            'totalDocuments',
            'pendingDocuments',
            'approvedDocuments',
            'publishedDocuments',
            'rejectedDocuments',
            'recentDocuments',
            'recentAuditLogs',
            'workflowSteps',
        ));
    }
}