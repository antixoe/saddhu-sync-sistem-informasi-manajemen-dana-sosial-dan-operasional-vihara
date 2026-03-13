<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Member;
use App\Models\PettyCash;
use App\Models\Ritual;
use App\Models\ActivityLog;
use App\Models\InventoryItem;
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

    public function inventory(Request $request): View
    {
        $query = InventoryItem::query();

        if ($request->category) {
            $query->where('category', $request->category);
        }

        if ($request->status === 'low_stock') {
            $items = $query->get()->filter(fn($item) => $item->isLowStock());
        } else {
            $items = $query->get();
        }

        $itemsByCategory = $items->groupBy('category');
        $totalValue = $items->sum(fn($item) => ($item->purchase_price ?? 0) * $item->quantity);
        $lowStockCount = $items->filter(fn($item) => $item->isLowStock())->count();
        $categoryCount = $itemsByCategory->count();
        $avgPrice = $items->count() > 0 ? $items->avg('purchase_price') : 0;

        return view('reports.inventory', [
            'items' => collect($items)->sortByDesc(fn($item) => ($item->purchase_price ?? 0) * $item->quantity),
            'itemsByCategory' => $itemsByCategory,
            'totalValue' => $totalValue,
            'lowStockCount' => $lowStockCount,
            'categoryCount' => $categoryCount,
            'avgPrice' => $avgPrice,
        ]);
    }

    public function schedule(Request $request): View
    {
        $query = Ritual::query();

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->status === 'upcoming') {
            $rituals = $query->get()->filter(fn($r) => $r->isUpcoming());
        } elseif ($request->status === 'past') {
            $rituals = $query->get()->filter(fn($r) => $r->isPast());
        } else {
            $rituals = $query->get();
        }

        if ($request->start_date) {
            $startDate = \Carbon\Carbon::parse($request->start_date);
            $rituals = $rituals->filter(fn($r) => $r->start_time >= $startDate);
        }

        $eventsByType = collect($rituals)->groupBy('type');
        $upcomingCount = collect($rituals)->filter(fn($r) => $r->isUpcoming())->count();
        $recurringCount = collect($rituals)->filter(fn($r) => $r->is_recurring)->count();
        $totalAttendance = collect($rituals)->sum(fn($r) => $r->attendances()->count());
        $totalRituals = count($rituals);
        $avgCapacity = $totalRituals > 0 ? intval(collect($rituals)->avg('capacity') ?? 0) : 0;

        return view('reports.schedule', [
            'rituals' => collect($rituals)->sortBy('start_time'),
            'eventsByType' => $eventsByType,
            'upcomingCount' => $upcomingCount,
            'recurringCount' => $recurringCount,
            'totalAttendance' => $totalAttendance,
            'totalRituals' => $totalRituals,
            'avgCapacity' => $avgCapacity,
        ]);
    }

    public function donationsExport(Request $request)
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
        $exportType = $request->get('format', 'excel');

        if ($exportType === 'excel') {
            return $this->exportDonationsToExcel($donations, $request);
        } else {
            return $this->exportDonationsToPdf($donations);
        }
    }

    private function exportDonationsToExcel($donations, Request $request)
    {
        $filename = 'donations-report-' . now()->format('Y-m-d-His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($donations) {
            $file = fopen('php://output', 'w');
            // add contact/address columns for public donations
            fputcsv($file, ['Date', 'Donor', 'Contact Name', 'Contact Phone', 'Province', 'City', 'Postal Code', 'Address', 'Latitude', 'Longitude', 'Category', 'Method', 'Amount (Rp)', 'Type', 'Status']);

            foreach ($donations as $donation) {
                fputcsv($file, [
                    $donation->donated_at->format('M d, Y'),
                    $donation->is_anonymous ? 'Anonymous' : $donation->member->name ?? 'Unknown',
                    $donation->contact_name,
                    $donation->contact_phone,
                    $donation->province,
                    $donation->city,
                    $donation->postal_code,
                    $donation->address,
                    $donation->latitude,
                    $donation->longitude,
                    $donation->fundCategory->name ?? '-',
                    str_replace('_', ' ', $donation->donation_method),
                    number_format($donation->amount, 0),
                    $donation->is_regular ? 'Regular' : 'One-time',
                    $donation->verified_at ? 'Verified' : 'Pending',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportDonationsToPdf($donations)
    {
        $totalAmount = $donations->sum('amount');

        return view('reports.exports.donations-pdf', [
            'donations' => $donations,
            'totalAmount' => $totalAmount,
        ]);
    }

    public function inventoryExport(Request $request)
    {
        $query = InventoryItem::query();

        if ($request->category) {
            $query->where('category', $request->category);
        }

        if ($request->status === 'low_stock') {
            $items = $query->get()->filter(fn($item) => $item->isLowStock());
        } else {
            $items = $query->get();
        }

        $exportType = $request->get('format', 'excel');

        if ($exportType === 'excel') {
            return $this->exportInventoryToExcel($items, $request);
        } else {
            return $this->exportInventoryToPdf($items);
        }
    }

    private function exportInventoryToExcel($items, Request $request)
    {
        $filename = 'inventory-report-' . now()->format('Y-m-d-His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($items) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Item Name', 'Category', 'Quantity', 'Unit', 'Unit Price (Rp)', 'Total Value (Rp)', 'Reorder Level', 'Status']);

            foreach ($items as $item) {
                fputcsv($file, [
                    $item->name,
                    $item->category,
                    $item->quantity,
                    $item->unit,
                    number_format($item->purchase_price ?? 0, 0),
                    number_format(($item->purchase_price ?? 0) * $item->quantity, 0),
                    $item->reorder_level ?? '-',
                    $item->isLowStock() ? 'Low Stock' : 'In Stock',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportInventoryToPdf($items)
    {
        $totalValue = $items->sum(fn($item) => ($item->purchase_price ?? 0) * $item->quantity);

        return view('reports.exports.inventory-pdf', [
            'items' => collect($items)->sortByDesc(fn($item) => ($item->purchase_price ?? 0) * $item->quantity),
            'totalValue' => $totalValue,
        ]);
    }

    public function scheduleExport(Request $request)
    {
        $query = Ritual::query();

        if ($request->input('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->status === 'upcoming') {
            $rituals = $query->get()->filter(fn($r) => $r->isUpcoming());
        } elseif ($request->status === 'past') {
            $rituals = $query->get()->filter(fn($r) => $r->isPast());
        } else {
            $rituals = $query->get();
        }

        if ($request->start_date) {
            $startDate = \Carbon\Carbon::parse($request->start_date);
            $rituals = $rituals->filter(fn($r) => $r->start_time >= $startDate);
        }

        $exportType = $request->get('format', 'excel');

        if ($exportType === 'excel') {
            return $this->exportScheduleToExcel($rituals);
        } else {
            return $this->exportScheduleToPdf($rituals);
        }
    }

    private function exportScheduleToExcel($rituals)
    {
        $filename = 'schedule-report-' . now()->format('Y-m-d-His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($rituals) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Event Title', 'Type', 'Start Time', 'Location', 'Attendance', 'Capacity', 'Recurring', 'Status']);

            foreach ($rituals as $ritual) {
                fputcsv($file, [
                    $ritual->title,
                    $ritual->type ?? '-',
                    $ritual->start_time->format('M d, Y g:i A'),
                    $ritual->location,
                    $ritual->attendances()->count(),
                    $ritual->capacity ?? 'Unlimited',
                    $ritual->is_recurring ? 'Yes' : 'No',
                    $ritual->isUpcoming() ? 'Upcoming' : ($ritual->isPast() ? 'Past' : 'Current'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportScheduleToPdf($rituals)
    {
        return view('reports.exports.schedule-pdf', [
            'rituals' => $rituals->sortBy('start_time'),
            'totalRituals' => count($rituals),
        ]);
    }
}
