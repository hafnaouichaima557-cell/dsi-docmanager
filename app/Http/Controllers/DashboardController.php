<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\User;
use App\Models\AuditLog;

class DashboardController extends Controller
{
    public function index()
    {
        $totalDocuments    = Document::count();
        $pendingDocuments  = Document::where('status', 'under_review')->count();
        $approvedDocuments = Document::where('status', 'approved')->count();
        $publishedDocuments = Document::where('status', 'published')->count();

        $recentDocuments = Document::with('creator', 'category')
                                   ->latest()
                                   ->take(5)
                                   ->get();

        $recentAuditLogs = AuditLog::with('user')
                                   ->latest('performed_at')
                                   ->take(5)
                                   ->get();

        return view('dashboard', compact(
            'totalDocuments',
            'pendingDocuments',
            'approvedDocuments',
            'publishedDocuments',
            'recentDocuments',
            'recentAuditLogs',
        ));
    }
}