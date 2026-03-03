<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MemberController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->input('q');
        $query = Member::with('user');

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('member_id', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhereHas('user', function ($u) use ($q) {
                        $u->where('name', 'like', "%{$q}%")
                          ->orWhere('email', 'like', "%{$q}%");
                    });
            });
        }

        $members = $query->latest()->paginate(20)->withQueryString();
        $roles = \App\Models\Role::pluck('name'); // available role names for modal

        return view('members.index', [
            'members' => $members,
            'roles' => $roles,
        ]);
    }

    public function create(): View
    {
        $roles = \App\Models\Role::pluck('name');
        return view('members.create', ['roles' => $roles]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'role' => 'required|string|exists:roles,name',
            'phone' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'province' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'profile_image' => 'nullable|string', // base64 data URI
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt('password123'), // Default password
            'role' => $validated['role'],
        ]);

        // Create member
        $memberData = [
            'user_id' => $user->id,
            'member_id' => 'MBR-' . date('YmdHis'),
            'phone' => $validated['phone'] ?? null,
            'birth_date' => $validated['birth_date'] ?? null,
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? null,
            'province' => $validated['province'] ?? null,
            'postal_code' => $validated['postal_code'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'join_date' => now(),
            'qr_code_token' => \Illuminate\Support\Str::random(20),
        ];

        if (!empty($validated['profile_image'])) {
            // extract base64 data
            $base = $validated['profile_image'];
            if (preg_match('/^data:image\/([a-zA-Z]+);base64,(.+)$/', $base, $matches)) {
                $ext = strtolower($matches[1]);
                $data = base64_decode($matches[2]);
                $filename = 'profile_' . time() . '.' . $ext;
                \Illuminate\Support\Facades\Storage::put('public/profile_images/'.$filename, $data);
                $memberData['profile_image'] = 'profile_images/'.$filename;
            }
        }

        $member = Member::create($memberData);

        ActivityLog::log('created', 'Member', $member->id, "New member {$user->name} registered");

        return redirect()->route('members.show', $member)->with('success', 'Member created successfully!');
    }

    public function show(Member $member): View
    {
        $merits = $member->merits()->latest('activity_date')->paginate(10);
        $donations = $member->donations()->with('fundCategory')->latest('donated_at')->paginate(10);
        $attendances = $member->attendances()->with('ritual')->latest('checked_in_at')->paginate(10);

        $roles = \App\Models\Role::pluck('name');

        return view('members.show', [
            'member' => $member,
            'merits' => $merits,
            'donations' => $donations,
            'attendances' => $attendances,
            'roles' => $roles,
        ]);
    }

    public function edit(Member $member): View
    {
        return view('members.edit', ['member' => $member]);
    }

    public function update(Request $request, Member $member): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $member->user_id,
            'role' => 'required|string|exists:roles,name',
            'phone' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'province' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Update user
        $member->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ]);

        // Update member
        $oldValues = $member->toArray();
        $updateData = [
            'phone' => $validated['phone'] ?? null,
            'birth_date' => $validated['birth_date'] ?? null,
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? null,
            'province' => $validated['province'] ?? null,
            'postal_code' => $validated['postal_code'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ];
        if (!empty($validated['profile_image'])) {
            if (preg_match('/^data:image\/([a-zA-Z]+);base64,(.+)$/', $validated['profile_image'], $matches)) {
                $ext = strtolower($matches[1]);
                $data = base64_decode($matches[2]);
                $filename = 'profile_' . time() . '.' . $ext;
                \Illuminate\Support\Facades\Storage::put('public/profile_images/'.$filename, $data);
                $updateData['profile_image'] = 'profile_images/'.$filename;
            }
        }
        $member->update($updateData);

        ActivityLog::log('updated', 'Member', $member->id, "Member {$member->user->name} updated", $oldValues);

        return redirect()->route('members.show', $member)->with('success', 'Member updated successfully!');
    }

    public function deactivate(Member $member): RedirectResponse
    {
        $member->update(['is_active' => false]);
        ActivityLog::log('updated', 'Member', $member->id, "Member deactivated");

        return back()->with('success', 'Member deactivated!');
    }

    public function activate(Member $member): RedirectResponse
    {
        $member->update(['is_active' => true]);
        ActivityLog::log('updated', 'Member', $member->id, "Member activated");

        return back()->with('success', 'Member activated!');
    }
}
