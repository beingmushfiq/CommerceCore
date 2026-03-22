<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Store;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $storeId = session('admin_store_id') ?? Store::first()->id;
        $expenses = Expense::where('store_id', $storeId)
            ->orderBy('date', 'desc')
            ->paginate(20);
            
        return view('admin.expenses.index', compact('expenses'));
    }

    public function create()
    {
        return view('admin.expenses.create');
    }

    public function store(Request $request)
    {
        $storeId = session('admin_store_id') ?? Store::first()->id;
        
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'date' => 'required|date'
        ]);

        $validated['store_id'] = $storeId;

        Expense::create($validated);

        return redirect()->route('admin.expenses.index')->with('success', 'Expense logged!');
    }

    public function edit(Expense $expense)
    {
        return view('admin.expenses.edit', compact('expense'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'date' => 'required|date'
        ]);

        $expense->update($validated);

        return redirect()->route('admin.expenses.index')->with('success', 'Expense updated!');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('admin.expenses.index')->with('success', 'Expense deleted!');
    }
}
