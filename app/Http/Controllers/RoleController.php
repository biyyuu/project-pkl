<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::paginate(10);
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // Create the generic user for this role
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'recovery_email' => $request->email, // Using the same email for recovery initially
        ]);

        $role = Role::create(['name' => $request->name]);

        // Assign permissions to the role
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        // Assign the role to the user
        $user->assignRole($role);

        return redirect()->route('roles.index')->with('success', 'Role dan Akses Login berhasil ditambahkan');
    }

    public function edit(Role $role)
    {
        if ($role->name === 'admin' || $role->name === 'kasub' || $role->name === 'kabid') {
            return redirect()->route('roles.index')->with('error', 'Role default sistem tidak dapat diubah.');
        }

        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        $user = \App\Models\User::role($role->name)->first(); // Get the primary user for this role

        return view('roles.edit', compact('role', 'permissions', 'rolePermissions', 'user'));
    }

    public function update(Request $request, Role $role)
    {
        if ($role->name === 'admin' || $role->name === 'kasub' || $role->name === 'kabid') {
            return redirect()->route('roles.index')->with('error', 'Role default sistem tidak dapat diubah.');
        }
        
        $user = \App\Models\User::role($role->name)->first();

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'email' => 'required|email' . ($user ? '|unique:users,email,'.$user->id : '|unique:users,email'),
            'password' => 'nullable|min:6',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $oldRoleName = $role->name;
        $role->update(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        } else {
            $role->syncPermissions([]);
        }

        if ($user) {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            if ($request->filled('password')) {
                $user->update([
                    'password' => \Illuminate\Support\Facades\Hash::make($request->password)
                ]);
            }
            
            // Re-assign role if name changed (Spatie handles role ids, but just in case)
            if ($oldRoleName !== $request->name) {
                 $user->removeRole($oldRoleName);
                 $user->assignRole($role->name);
            }
        }

        return redirect()->route('roles.index')->with('success', 'Role dan pengaturan akses berhasil diperbarui');
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'admin' || $role->name === 'kasub' || $role->name === 'kabid') {
            return redirect()->route('roles.index')->with('error', 'Role default sistem tidak dapat dihapus.');
        }

        $user = \App\Models\User::role($role->name)->first();

        if ($user) {
            $user->delete();
        }

        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role dan akun pengguna terkait berhasil dihapus');
    }
}
