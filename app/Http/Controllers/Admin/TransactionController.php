<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\Store;
use App\Services\AccountingService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(private AccountingService $accountingService) {}

    public function index(Request $request)
    {
        $transactions = Transaction::with('account')->latest()->paginate(20);
        $accounts = Account::where('is_active', true)->get();
        return view('admin.transactions.index', compact('transactions', 'accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0.01',
            'category' => 'required|string',
            'reference' => 'nullable|string',
            'description' => 'nullable|string'
        ]);

        $storeId = session('admin_store_id') ?? Store::first()->id; // Fallback for demo

        $this->accountingService->recordTransaction(
            $validated['account_id'],
            $storeId,
            $validated['type'],
            $validated['amount'],
            $validated['category'],
            $validated['reference'],
            $validated['description']
        );

        return back()->with('success', 'Transaction recorded successfully.');
    }
}
