<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\View\View;

class AdminAuditLogController extends Controller
{
    /**
     * Display all Audit Logs of administrative actions.
     */
    public function index(): View
    {
        $logs = AuditLog::with('user')->latest('id')->paginate(30);
        return view('admin.audit-logs.index', compact('logs'));
    }
}
