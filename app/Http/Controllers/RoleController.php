<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RoleController extends Controller
{
    /**
     * Display a listing of the roles.
     */
    public function index(): View
    {
        $roles = Role::withCount(['permissions', 'users'])
            ->orderBy('name')
            ->paginate(20);

        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create(): View
    {
        return view('roles.create', [
            'role' => new Role(),
            'permissionGroups' => $this->permissionGroups(),
            'selected' => [],
        ]);
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        $role = Role::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        $role->permissions()->sync($data['permissions'] ?? []);

        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified role (redirect to edit).
     */
    public function show(Role $role): RedirectResponse
    {
        return redirect()->route('roles.edit', $role);
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role): View
    {
        if ($role->is_locked) {
            return redirect()->route('roles.index')
                ->with('error', 'The Administrator role cannot be edited.');
        }

        return view('roles.edit', [
            'role' => $role,
            'permissionGroups' => $this->permissionGroups(),
            'selected' => $role->permissions->pluck('id')->all(),
        ]);
    }

    /**
     * Update the specified role.
     */
    public function update(Request $request, Role $role): RedirectResponse
    {
        if ($role->is_locked) {
            return redirect()->route('roles.index')
                ->with('error', 'The Administrator role cannot be edited.');
        }

        $data = $this->validated($request, $role);

        $role->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        $role->permissions()->sync($data['permissions'] ?? []);

        return redirect()->route('roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified role.
     */
    public function destroy(Role $role): RedirectResponse
    {
        if ($role->is_locked) {
            return redirect()->route('roles.index')
                ->with('error', 'The Administrator role cannot be deleted.');
        }

        if ($role->users()->exists()) {
            return redirect()->route('roles.index')
                ->with('error', 'This role is assigned to users and cannot be deleted.');
        }

        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully.');
    }

    /**
     * Validate the role request.
     *
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Role $role = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->ignore($role)],
            'description' => ['nullable', 'string', 'max:255'],
            'permissions' => ['array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ]);
    }

    /**
     * Permissions grouped by module for the role editor.
     *
     * @return \Illuminate\Support\Collection
     */
    private function permissionGroups()
    {
        $grouped = Permission::orderBy('label')->get()->groupBy('group');

        // Present the groups in the same order as the screen registry
        // (Access Control, Settings, Clients) so it mirrors the sidebar.
        $order = array_keys(config('permissions.groups', []));

        return $grouped->sortBy(fn ($permissions, $group) => array_search($group, $order, true));
    }
}
