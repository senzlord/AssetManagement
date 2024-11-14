<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Get the currently authenticated user

        log_action('info', "User {$user->name} (ID: {$user->id}) accessed their profile page.");

        // Define permission groups
        $userPermissionsList = config('permissions.user_permissions');
        $adminPermissionsList = config('permissions.admin_permissions');

        // Retrieve all permissions assigned to the user
        $userPermissions = $user->getAllPermissions()->pluck('name');

        // Filter permissions into User and Admin categories
        $groupedPermissions = [
            'User Permissions' => $userPermissions->filter(function ($permission) use ($userPermissionsList) {
                return in_array($permission, $userPermissionsList);
            }),
            'Admin Permissions' => $userPermissions->filter(function ($permission) use ($adminPermissionsList) {
                return in_array($permission, $adminPermissionsList);
            })
        ];

        return view('profile', compact('user', 'groupedPermissions'));
    }

    public function showChangePasswordForm()
    {
        log_action('info', 'User accessed the change password form.');

        return view('auth.change-password');
    }

    public function changePassword(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Check if current password matches
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Your current password does not match our records.',
            ]);
        }

        // Update the user's password
        Auth::user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        log_action('success', "User {Auth::user()->name} (ID: {Auth::user()->id}) successfully changed their password.");

        return redirect()->route('profile')->with('status', 'Password changed successfully.');
    }
}
