<?php

namespace App\Http\Controllers;

use App\Models\PettyCash;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PettyCashController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->input('q');
        $category = $request->input('category');
        $query = PettyCash::with('user')->latest('transaction_date');

        if ($category) {
            $query->where('category', $category);
        }

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('category', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        $transactions = $query->paginate(20)->withQueryString();
        
        $totalToday = PettyCash::whereDate('transaction_date', today())
            ->sum('amount');
        
        $totalMonth = PettyCash::whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('amount');
        
        // Get all unique categories
        $categories = PettyCash::distinct()->pluck('category')->sort()->filter()->values();

        return view('petty-cash.index', [
            'transactions' => $transactions,
            'totalToday' => $totalToday,
            'totalMonth' => $totalMonth,
            'categories' => $categories,
        ]);
    }

    public function create(): View
    {
        return view('petty-cash.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string',
            'transaction_date' => 'required|date',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $transaction = PettyCash::create([
            ...$validated,
            'user_id' => auth()->id(),
        ]);

        ActivityLog::log('created', 'PettyCash', $transaction->id,
            "Petty cash: {$validated['category']} - Rp" . number_format($validated['amount']));

        return redirect()->route('petty-cash.index')->with('success', 'Petty cash transaction recorded successfully!');
    }

    public function edit(PettyCash $pettyCash): View
    {
        return view('petty-cash.edit', ['transaction' => $pettyCash]);
    }

    public function update(Request $request, PettyCash $pettyCash): RedirectResponse
    {
        $validated = $request->validate([
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string',
            'transaction_date' => 'required|date',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $oldValues = $pettyCash->toArray();
        $pettyCash->update($validated);
        ActivityLog::log('updated', 'PettyCash', $pettyCash->id, "Petty cash updated", $oldValues);

        return redirect()->route('petty-cash.index')->with('success', 'Petty cash transaction updated successfully!');
    }

    public function destroy(PettyCash $pettyCash): RedirectResponse
    {
        ActivityLog::log('deleted', 'PettyCash', $pettyCash->id, "Petty cash deleted");
        $pettyCash->delete();

        return redirect()->route('petty-cash.index')->with('success', 'Petty cash transaction deleted!');
    }
}
