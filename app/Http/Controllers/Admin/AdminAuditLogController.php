<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminAuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $logs = AdminAuditLog::with('admin')
            ->when($request->action, fn ($q, $action) => $q->where('action', $action))
            ->when($request->admin_id, fn ($q, $id) => $q->where('admin_id', $id))
            ->latest()
            ->paginate(50)
            ->withQueryString();

        $actions = AdminAuditLog::select('action')->distinct()->pluck('action');

        return view('admin.audit-log', [
            'logs'    => $logs,
            'actions' => $actions,
        ]);
    }
}
