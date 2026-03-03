<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Member;
use App\Models\Ritual;
use App\Models\FundCategory;
use App\Models\InventoryItem;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalMembers = Member::where('is_active', true)->count();
        $totalDonations = Donation::sum('amount');
        $totalDonationsMonth = Donation::whereMonth('donated_at', now()->month)->sum('amount');
        $upcomingRituals = Ritual::where('start_time', '>', now())->orderBy('start_time')->limit(5)->get();
        $fundCategories = FundCategory::withCount('donations')->get();
        $lowStockItems = InventoryItem::whereNotNull('reorder_level')
            ->whereRaw('quantity <= reorder_level')
            ->limit(5)
            ->get();

        $recentDonations = Donation::with('member', 'fundCategory')
            ->latest('donated_at')
            ->limit(10)
            ->get();

        $donationTrend = Donation::selectRaw('DATE(donated_at) as date, SUM(amount) as total')
            ->where('donated_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->get();

        return view('dashboard.index', [
            'totalMembers' => $totalMembers,
            'totalDonations' => $totalDonations,
            'totalDonationsMonth' => $totalDonationsMonth,
            'upcomingRituals' => $upcomingRituals,
            'fundCategories' => $fundCategories,
            'lowStockItems' => $lowStockItems,
            'recentDonations' => $recentDonations,
            'donationTrend' => $donationTrend,
        ]);
    }
}
