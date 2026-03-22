<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Services\AccountingService;
use Illuminate\Http\Request;

class AccountingReportController extends Controller
{
    public function __construct(private AccountingService $accountingService) {}

    public function index(Request $request)
    {
        $storeId = session('admin_store_id') ?? Store::first()->id;
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        $summary = $this->accountingService->getSummary($storeId, $startDate, $endDate);

        return view('admin.reports.accounting', compact('summary'));
    }
}
