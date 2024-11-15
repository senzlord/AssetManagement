<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class UserListController extends Controller
{
    private function isProtectedUser($id)
    {
        // Prevent any user other than user ID 1 from modifying user ID 1
        return $id == 1 && auth()->id() !== 1;
    }

    public function index()
    {
        log_action('info', 'User viewed the user list');

        $this->authorize('create account');

        // Retrieve all users to display, including soft-deleted ones
        $users = User::withTrashed()->get();

        return view('users.index', compact('users'));
    }

    // Show the form for creating a new user
    public function create()
    {
        log_action('info', 'User accessed the create user form');

        $this->authorize('create account');

        if (Auth::user()->hasRole('user')) {
            $roles = Role::where('name', 'user')->get(); // Only user role is available for regular users
        } else {
            $roles = Role::all(); // Admin can see all roles
        }

        return view('users.create', compact('roles'));
    }

    // Handle form submission to store a new user
    public function store(Request $request)
    {
        $this->authorize('create account');

        // Check if the authenticated user is an admin
        if (Auth::user()->hasRole('user') && $request->role !== 'user') {
            return redirect()->route('users.index')->with('error', 'You can only create users.');
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'exists:roles,name'],
        ];

        // Run the validator
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            log_action('error', 'Validation failed during user creation: ' . json_encode($validator->errors()));
            return redirect()->route('users.create')
                ->withErrors($validator) // Pass validation errors to the session
                ->withInput(); // Keep the input data for repopulation
        }

        // Define permission groups
        $userPermissionsList = config('permissions.user_permissions');
        $adminPermissionsList = config('permissions.admin_permissions');

        // Create the new user
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign the selected role
        $user->assignRole($request->role);

        // Assign default permissions based on role
        if ($request->role === 'admin') {
            $allPermissions = array_merge($userPermissionsList, $adminPermissionsList);
            foreach ($allPermissions as $permission) {
                $user->givePermissionTo($permission);
            }
        } elseif ($request->role === 'user') {
            foreach ($userPermissionsList as $permission) {
                $user->givePermissionTo($permission);
            }
        }

        log_action('success', "New user '{$user->name}' (ID: {$user->id}) created with role '{$request->role}'.");
        // Redirect back to user list with success message
        return redirect()->route('users.index')->with('success', 'User created successfully with assigned role.');
    }

    public function edit($id)
    {
        // Find the user, including soft-deleted users
        $user = User::withTrashed()->find($id);

        // Check if the user exists and if it's soft-deleted
        if (!$user) {
            // If the user does not exist, redirect with an error message
            return redirect()->route('users.index')
                ->withErrors(['error' => 'User not found.']);
        }

        // If the user is soft-deleted, redirect back with an error message
        if ($user->trashed()) {
            return redirect()->route('users.index')
                ->withErrors(['error' => 'The user has been deleted and cannot be edited.']);
        }

        log_action('info', "User accessed the edit form for user '{$user->name}' (ID: {$user->id})");

        // If the user is not deleted, proceed with the normal edit process
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        // Validate the request
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $id], // Ensure username uniqueness except for current user
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id], // Ensure email uniqueness except for current user
            'role' => ['required', 'exists:roles,name'], // Validate the role
        ];

        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            log_action('error', 'Validation failed during user update (ID: ' . $id . '): ' . json_encode($validator->errors()));
            return redirect()->route('users.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        // Find the user
        $user = User::withTrashed()->find($id);

        // Check if the user exists
        if (!$user) {
            return redirect()->route('users.index')
                ->withErrors(['error' => 'User not found.']);
        }

        // If the user is trashed (soft-deleted)
        if ($user->trashed()) {
            return redirect()->route('users.index')
                ->withErrors(['error' => 'The user has been deleted and cannot be updated.']);
        }

        // Update user details, excluding password and permissions
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;

        // Save the updated user details
        $user->save();

        // Sync the role (this will remove all other roles and assign the new one)
        $user->syncRoles($request->role);

        log_action('success', "User '{$user->name}' (ID: {$user->id}) updated with role '{$request->role}'.");
        // Redirect with success message
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $this->authorize('delete account');

        // Prevent the authenticated user from deleting their own account
        if (auth()->id() === $id) {
            log_action('warning', 'User attempted to delete their own account.');
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account.');
        }

        // Check if user ID 1 is being accessed by another user
        if ($this->isProtectedUser($id)) {
            log_action('warning', 'User attempted to delete a protected account (ID: 1).');
            return redirect()->route('users.index')->with('error', 'You cannot delete this account.');
        }

        // Find the user and perform soft delete
        $user = User::findOrFail($id);
        $user->delete();

        log_action('success', "User '{$user->name}' (ID: {$user->id}) was deleted.");
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function restore($id)
    {
        $this->authorize('delete account');

        // Check if user ID 1 is being accessed by another user
        if ($this->isProtectedUser($id)) {
            log_action('warning', 'User attempted to restore a protected account (ID: 1).');
            return redirect()->route('users.index')->with('error', 'You cannot restore this account.');
        }

        // Find the soft-deleted user and restore them
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        log_action('success', "User '{$user->name}' (ID: {$user->id}) was restored.");
        return redirect()->route('users.index')->with('success', 'User restored successfully.');
    }

    public function changeAccess($id)
    {
        $this->authorize('manage data access');

        // Prevent users from accessing their own change-access page
        if (auth()->id() == $id) {
            log_action('warning', 'User attempted to change their own access.');
            return redirect()->route('users.index')->with('error', 'You cannot change your own access.');
        }

        // Check if user ID 1 is being accessed by another user
        if ($this->isProtectedUser($id)) {
            log_action('warning', 'User attempted to change access for a protected account (ID: 1).');
            return redirect()->route('users.index')->with('error', 'You cannot change access for this account.');
        }

        $user = User::findOrFail($id);

        $userPermissionsList = config('permissions.user_permissions');
        $adminPermissionsList = config('permissions.admin_permissions');

        $userPermissions = Permission::whereIn('name', $userPermissionsList)->get();
        $adminPermissions = Permission::whereIn('name', $adminPermissionsList)->get();
        $userAssignedPermissions = $user->permissions->pluck('name')->toArray();

        return view('users.change-access', compact('user', 'userPermissions', 'adminPermissions', 'userAssignedPermissions'));
    }

    public function updateAccess(Request $request, $id)
    {
        $this->authorize('manage data access');

        // Check if user ID 1 is being accessed by another user
        if ($this->isProtectedUser($id)) {
            log_action('warning', 'User attempted to update access for a protected account (ID: 1).');
            return redirect()->route('users.index')->with('error', 'You cannot update access for this account.');
        }

        $user = User::findOrFail($id);

        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $user->syncPermissions($request->input('permissions', []));

        return redirect()->route('users.index')->with('success', 'User permissions updated successfully.');
    }
}
