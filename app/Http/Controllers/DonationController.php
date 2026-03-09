<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Member;
use App\Models\FundCategory;
use App\Models\MeritHistory;
use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DonationController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->input('q');
        $category = $request->input('category');

        $query = Donation::with('member.user', 'fundCategory');

        if ($category) {
            $query->where('fund_category_id', $category);
        }

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('transaction_id', 'like', "%{$q}%")
                    ->orWhereHas('member.user', function ($u) use ($q) {
                        $u->where('name', 'like', "%{$q}%");
                    })
                    ->orWhereHas('fundCategory', function ($f) use ($q) {
                        $f->where('name', 'like', "%{$q}%");
                    });
            });
        }

        $donations = $query->latest('donated_at')->paginate(20)->withQueryString();
        $fundCategories = FundCategory::all();
        $members = Member::where('is_active', true)->get(); // for modal create

        return view('donations.index', [
            'donations' => $donations,
            'fundCategories' => $fundCategories,
            'members' => $members,
        ]);
    }

    public function create(): View
    {
        $members = Member::where('is_active', true)->get();
        $fundCategories = FundCategory::all();

        return view('donations.create', [
            'members' => $members,
            'fundCategories' => $fundCategories,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'member_id' => 'nullable|exists:members,id',
            'fund_category_id' => 'required|exists:fund_categories,id',
            'amount' => 'required|numeric|min:1',
            'donation_method' => 'required|string',
            'transaction_id' => 'nullable|string|unique:donations',
            'is_anonymous' => 'boolean',
            'is_regular' => 'boolean',
            'frequency' => 'nullable|string|required_if:is_regular,true',
            'notes' => 'nullable|string',
        ]);

        $validated['donated_at'] = now();
        $donation = Donation::create($validated);

        // Log activity
        ActivityLog::log('created', 'Donation', $donation->id, 
            "New donation of Rp" . number_format((float) $donation->amount));

        // Create merit history if not anonymous
        if (!$donation->is_anonymous && $donation->member_id) {
            MeritHistory::create([
                'member_id' => $donation->member_id,
                'activity_type' => 'donation',
                'description' => "Donated Rp" . number_format((float) $donation->amount) . " to {$donation->fundCategory->name}",
                'activity_date' => now(),
                'amount' => $donation->amount,
            ]);
        }

        return redirect()->route('donations.show', $donation)->with('success', 'Donation recorded successfully!');
    }

    public function show(Donation $donation): View
    {
        return view('donations.show', ['donation' => $donation]);
    }

    /**
     * Public donation form accessible without authentication.
     */
    public function publicCreate(Request $request): View
    {
        $fundCategories = FundCategory::all();
        $qrCode = Setting::getValue('donation_qr_code');
        $bankDetails = Setting::getValue('donation_bank_details');
        $virtualAccounts = Setting::getValue('donation_virtual_accounts');

        // Pre-fill form data from URL parameters
        $preFillData = [
            'fund_category_id' => $request->query('fund_category_id'),
            'donation_method' => $request->query('donation_method'),
        ];

        // Map payment method from URL to form values
        if ($preFillData['donation_method']) {
            $methodMapping = [
                'qris' => 'qris',
                'bank' => 'bank_transfer',
                'virtual' => 'virtual',
                'cash' => 'cash',
            ];
            $preFillData['donation_method'] = $methodMapping[$preFillData['donation_method']] ?? $preFillData['donation_method'];
        }

        return view('donations.public', compact('fundCategories', 'qrCode', 'bankDetails', 'virtualAccounts', 'preFillData'));
    }

    /**
     * Public donation menu page.
     */
    public function menu(): View
    {
        $fundCategories = FundCategory::all();
        $qrCode = Setting::getValue('donation_qr_code');
        $bankDetails = Setting::getValue('donation_bank_details');
        $virtualAccounts = Setting::getValue('donation_virtual_accounts');

        return view('donations.menu', compact('fundCategories', 'qrCode', 'bankDetails', 'virtualAccounts'));
    }

    /**
     * Handle submission from the public donation page.
     */
    public function publicStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'fund_category_id' => 'required|exists:fund_categories,id',
            'amount' => 'required|numeric|min:1',
            'donation_method' => 'required|string',
            'transaction_id' => 'nullable|string|unique:donations',
            'notes' => 'nullable|string',
        ]);

        $validated['donated_at'] = now();
        // treat all public submissions as anonymous by default
        $validated['is_anonymous'] = true;

        $donation = Donation::create($validated);

        ActivityLog::log('created', 'Donation', $donation->id,
            "Public donation of Rp" . number_format((float) $donation->amount));

        return redirect()->route('donate.thankyou');
    }

    public function thankyou(): View
    {
        return view('donations.thankyou');
    }

    public function edit(Donation $donation): View
    {
        $members = Member::where('is_active', true)->get();
        $fundCategories = FundCategory::all();

        return view('donations.edit', [
            'donation' => $donation,
            'members' => $members,
            'fundCategories' => $fundCategories,
        ]);
    }

    public function update(Request $request, Donation $donation): RedirectResponse
    {
        $validated = $request->validate([
            'member_id' => 'nullable|exists:members,id',
            'fund_category_id' => 'required|exists:fund_categories,id',
            'amount' => 'required|numeric|min:1',
            'donation_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $oldValues = $donation->toArray();
        $donation->update($validated);

        // Log activity
        ActivityLog::log('updated', 'Donation', $donation->id,
            "Donation updated", $oldValues, $validated);

        return redirect()->route('donations.show', $donation)->with('success', 'Donation updated successfully!');
    }

    public function verify(Donation $donation): RedirectResponse
    {
        $donation->update(['verified_at' => now()]);
        ActivityLog::log('verified', 'Donation', $donation->id, "Donation verified");

        return back()->with('success', 'Donation verified!');
    }

    public function sendReceipt(Donation $donation): RedirectResponse
    {
        // TODO: Implement email/WhatsApp receipt sending
        $donation->update(['receipt_sent' => true]);
        ActivityLog::log('updated', 'Donation', $donation->id, "Receipt sent to member");

        return back()->with('success', 'Receipt sent to member!');
    }

    public function destroy(Donation $donation): RedirectResponse
    {
        ActivityLog::log('deleted', 'Donation', $donation->id, "Donation of Rp" . number_format((float) $donation->amount) . " deleted");
        $donation->delete();
        return redirect()->route('donations.index')->with('success', 'Donation removed successfully!');
    }
}
