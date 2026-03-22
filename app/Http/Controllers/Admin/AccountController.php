<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::all();
        return view('admin.accounts.index', compact('accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'account_number' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'balance' => 'required|numeric'
        ]);

        Account::create($validated);
        return back()->with('success', 'Account created successfully.');
    }
}
