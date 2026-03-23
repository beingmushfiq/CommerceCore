<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ErpDashboardController extends Controller
{
    public function index()
    {
        // Placeholder data for the ERP dashboard
        $financialSummary = [
            'revenue' => 450000,
            'expenses' => 125000,
            'net_profit' => 325000,
            'ar_aging' => [
                'current' => 50000,
                'over_30' => 15000,
                'over_60' => 5000,
                'over_90' => 2000
            ],
            'ap_aging' => [
                'current' => 20000,
                'over_30' => 5000,
                'over_60' => 1000,
                'over_90' => 0
            ]
        ];

        $operationalAlerts = [
            ['type' => 'warning', 'message' => '5 Purchase Orders pending approval.'],
            ['type' => 'danger', 'message' => '3 Inventory items below minimum reorder point.'],
            ['type' => 'info', 'message' => 'Payroll processing due in 3 days.']
        ];

        $hrSummary = [
            'total_employees' => 45,
            'present_today' => 42,
            'on_leave' => 3,
            'pending_leave_requests' => 2
        ];

        return view('admin.erp.dashboard', compact('financialSummary', 'operationalAlerts', 'hrSummary'));
    }
}
