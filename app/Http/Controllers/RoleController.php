<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RoleController extends Controller
{
    public function __construct()
    {
        // only authenticated admins may manage roles
        // @phpstan-ignore-next-line - Laravel provides middleware() via base Controller
        $this->middleware('auth');
        // @phpstan-ignore-next-line - closure middleware added via Controller
        $this->middleware(function ($request, $next) {
            if (!auth()->user() || !auth()->user()->isAdmin()) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index(Request $request): View
    {
        $q = $request->input('q');
        $query = Role::query();

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('label', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        $roles = $query->latest()->paginate(20)->withQueryString();
        return view('roles.index', ['roles' => $roles]);
    }

    public function create(): View
    {
        return view('roles.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'label' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $role = Role::create($validated);
        ActivityLog::log('created', 'Role', $role->id, "Role '{$role->name}' created");

        return redirect()->route('roles.index')->with('success', 'Role created successfully!');
    }

    public function show(Role $role): View
    {
        return view('roles.show', ['role' => $role]);
    }

    public function edit(Role $role): View
    {
        return view('roles.edit', ['role' => $role]);
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'label' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $oldValues = $role->toArray();
        $role->update($validated);
        ActivityLog::log('updated', 'Role', $role->id, "Role '{$role->name}' updated", $oldValues);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully!');
    }

    public function destroy(Role $role): RedirectResponse
    {
        ActivityLog::log('deleted', 'Role', $role->id, "Role '{$role->name}' deleted");
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully!');
    }
}
