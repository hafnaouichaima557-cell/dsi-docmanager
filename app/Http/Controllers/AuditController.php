<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    // diag 9 : عرض الأوديت — admin فقط
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->latest('performed_at');

        // فلترة بالموديول
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        // فلترة بالمستخدم
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // فلترة بالتاريخ
        if ($request->filled('date')) {
            $query->whereDate('performed_at', $request->date);
        }

        $logs  = $query->paginate(20);
        $modules = AuditLog::distinct()->pluck('module');

        return view('audit.index', compact('logs', 'modules'));
    }
}