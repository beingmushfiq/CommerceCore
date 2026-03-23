<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JournalEntry;
use App\Models\JournalLine;
use App\Models\Account;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JournalEntryController extends Controller
{
    public function index()
    {
        $entries = JournalEntry::with('lines.account', 'user')->latest('date')->latest('id')->paginate(15);
        $accounts = Account::orderBy('code')->get();
        return view('admin.journal.index', compact('entries', 'accounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'reference' => 'required|string|unique:journal_entries',
            'description' => 'nullable|string',
            'lines' => 'required|array|min:2',
            'lines.*.account_id' => 'required|exists:accounts,id',
            'lines.*.debit' => 'required|numeric|min:0',
            'lines.*.credit' => 'required|numeric|min:0',
        ]);

        $totalDebit = collect($request->lines)->sum('debit');
        $totalCredit = collect($request->lines)->sum('credit');

        if (round($totalDebit, 2) !== round($totalCredit, 2)) {
            return back()->with('error', 'Total Debits must equal Total Credits.')->withInput();
        }

        if ($totalDebit == 0) {
            return back()->with('error', 'Journal entry must have a non-zero value.')->withInput();
        }

        DB::transaction(function () use ($request) {
            $storeId = session('admin_store_id') ?? Store::first()->id;

            $entry = JournalEntry::create([
                'store_id' => $storeId,
                'reference' => $request->reference,
                'date' => $request->date,
                'description' => $request->description,
                'user_id' => auth()->user()?->id,
            ]);

            foreach ($request->lines as $line) {
                if ($line['debit'] > 0 || $line['credit'] > 0) {
                    $entry->lines()->create([
                        'account_id' => $line['account_id'],
                        'description' => $line['description'] ?? null,
                        'debit' => $line['debit'],
                        'credit' => $line['credit'],
                    ]);

                    $account = Account::find($line['account_id']);
                    if (in_array($account->gl_type, ['asset', 'expense'])) {
                        $account->balance += $line['debit'];
                        $account->balance -= $line['credit'];
                    } else {
                        $account->balance += $line['credit'];
                        $account->balance -= $line['debit'];
                    }
                    $account->save();
                }
            }
        });

        return back()->with('success', 'Journal Entry created successfully.');
    }
}
