<?php

namespace App\Http\Controllers;

use App\Models\Ritual;
use App\Models\Attendance;
use App\Models\Member;
use App\Models\ActivityLog;
use App\Models\MeritHistory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RitualController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->input('q');
        $query = Ritual::orderBy('start_time');

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhere('location', 'like', "%{$q}%");
            });
        }

        $rituals = $query->paginate(20)->withQueryString();

        return view('rituals.index', ['rituals' => $rituals]);
    }

    public function create(): View
    {
        return view('rituals.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string',
            'start_time' => 'required|date_time',
            'end_time' => 'nullable|date_time|after:start_time',
            'location' => 'nullable|string',
            'capacity' => 'nullable|integer|min:1',
            'is_recurring' => 'boolean',
            'recurrence_pattern' => 'nullable|string|required_if:is_recurring,true',
            'recurrence_end' => 'nullable|date_time',
            'requires_registration' => 'boolean',
            'special_notes' => 'nullable|string',
        ]);

        $ritual = Ritual::create($validated);
        ActivityLog::log('created', 'Ritual', $ritual->id, "New ritual '{$ritual->title}' created");

        return redirect()->route('rituals.show', $ritual)->with('success', 'Ritual created successfully!');
    }

    public function show(Ritual $ritual): View
    {
        $attendances = $ritual->attendances()->with('member')->paginate(20);

        return view('rituals.show', [
            'ritual' => $ritual,
            'attendances' => $attendances,
        ]);
    }

    public function edit(Ritual $ritual): View
    {
        return view('rituals.edit', ['ritual' => $ritual]);
    }

    public function update(Request $request, Ritual $ritual): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string',
            'start_time' => 'required|date_time',
            'end_time' => 'nullable|date_time|after:start_time',
            'location' => 'nullable|string',
            'capacity' => 'nullable|integer|min:1',
            'special_notes' => 'nullable|string',
        ]);

        $oldValues = $ritual->toArray();
        $ritual->update($validated);
        ActivityLog::log('updated', 'Ritual', $ritual->id, "Ritual updated", $oldValues);

        return redirect()->route('rituals.show', $ritual)->with('success', 'Ritual updated successfully!');
    }

    public function registerMember(Ritual $ritual, Member $member): RedirectResponse
    {
        Attendance::firstOrCreate([
            'member_id' => $member->id,
            'ritual_id' => $ritual->id,
        ]);

        MeritHistory::create([
            'member_id' => $member->id,
            'activity_type' => 'ritual_participation',
            'description' => "Attended: {$ritual->title}",
            'activity_date' => now(),
        ]);

        return back()->with('success', 'Member registered for this ritual!');
    }

    public function checkIn(Ritual $ritual, Member $member): RedirectResponse
    {
        $attendance = Attendance::where([
            'member_id' => $member->id,
            'ritual_id' => $ritual->id,
        ])->firstOrCreate([]);

        $attendance->update(['checked_in_at' => now()]);

        return back()->with('success', 'Member checked in!');
    }

    public function checkOut(Ritual $ritual, Member $member): RedirectResponse
    {
        $attendance = Attendance::where([
            'member_id' => $member->id,
            'ritual_id' => $ritual->id,
        ])->firstOrFail();

        $attendance->update(['checked_out_at' => now()]);

        return back()->with('success', 'Member checked out!');
    }
}
