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
        
        // Define permission groups
        $userPermissionsList = [
            'view device data',         
            'search device data',       
            'edit device data',         
            'generate reports',         
            'edit account',             
            'modify device count',      
        ];

        $adminPermissionsList = [
            'add category',             
            'add device data',          
            'delete device data',       
            'create account',           
            'delete account',           
            'manage data access',       
        ];

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

        return redirect()->route('profile')->with('status', 'Password changed successfully.');
    }
}
