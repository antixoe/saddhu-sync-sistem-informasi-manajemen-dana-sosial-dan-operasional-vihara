<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Member;
use App\Models\PettyCash;
use App\Models\Ritual;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function donations(Request $request): View
    {
        $query = Donation::with('member', 'fundCategory');

        if ($request->start_date) {
            $query->whereDate('donated_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('donated_at', '<=', $request->end_date);
        }
        if ($request->fund_category_id) {
            $query->where('fund_category_id', $request->fund_category_id);
        }
        if ($request->donation_method) {
            $query->where('donation_method', $request->donation_method);
        }

        $donations = $query->latest('donated_at')->get();
        
        $totalAmount = $donations->sum('amount');
        $groupedByCategory = $donations->groupBy('fundCategory.name');
        $groupedByMethod = $donations->groupBy('donation_method');

        return view('reports.donations', [
            'donations' => $donations,
            'totalAmount' => $totalAmount,
            'groupedByCategory' => $groupedByCategory,
            'groupedByMethod' => $groupedByMethod,
        ]);
    }

    public function expenses(Request $request): View
    {
        $query = PettyCash::with('user');

        if ($request->start_date) {
            $query->whereDate('transaction_date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('transaction_date', '<=', $request->end_date);
        }
        if ($request->category) {
            $query->where('category', $request->category);
        }

        $expenses = $query->latest('transaction_date')->get();
        $totalExpenses = $expenses->sum('amount');
        $groupedByCategory = $expenses->groupBy('category');

        return view('reports.expenses', [
            'expenses' => $expenses,
            'totalExpenses' => $totalExpenses,
            'groupedByCategory' => $groupedByCategory,
        ]);
    }

    public function memberActivity(Request $request): View
    {
        $member = Member::find($request->member_id);
        
        if (!$member) {
            $members = Member::where('is_active', true)->get();
            return view('reports.member-activity', ['members' => $members]);
        }

        $merits = $member->merits()->latest('activity_date')->get();
        $donations = $member->donations()->with('fundCategory')->latest('donated_at')->get();
        $attendances = $member->attendances()->with('ritual')->latest('checked_in_at')->get();

        return view('reports.member-activity', [
            'member' => $member,
            'merits' => $merits,
            'donations' => $donations,
            'attendances' => $attendances,
            'members' => Member::where('is_active', true)->get(),
        ]);
    }

    public function activityLog(Request $request): View
    {
        $query = ActivityLog::with('user');

        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->action) {
            $query->where('action', $request->action);
        }

        $logs = $query->latest('created_at')->paginate(50);

        return view('reports.activity-log', ['logs' => $logs]);
    }

    public function financialSummary(Request $request): View
    {
        $startDate = $request->start_date ? \Carbon\Carbon::parse($request->start_date) : now()->startOfMonth();
        $endDate = $request->end_date ? \Carbon\Carbon::parse($request->end_date) : now()->endOfMonth();

        $donations = Donation::whereBetween('donated_at', [$startDate, $endDate])
            ->with('fundCategory')
            ->get();
        
        $expenses = PettyCash::whereBetween('transaction_date', [$startDate, $endDate])
            ->get();

        $totalIncome = $donations->sum('amount');
        $totalExpense = $expenses->sum('amount');
        $netBalance = $totalIncome - $totalExpense;

        $byCategory = $donations->groupBy('fundCategory.name')
            ->map(fn($items) => ['amount' => $items->sum('amount'), 'count' => $items->count()]);

        return view('reports.financial-summary', [
            'donations' => $donations,
            'expenses' => $expenses,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'netBalance' => $netBalance,
            'byCategory' => $byCategory,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
}
