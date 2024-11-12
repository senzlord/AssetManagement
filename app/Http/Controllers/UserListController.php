<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserListController extends Controller
{
    public function index()
    {
        // Check if the user has the "create account" permission
        if (!Auth::user()->can('create account')) {
            abort(403, 'Unauthorized action.');
        }

        // Retrieve all users to display
        $users = User::all();

        return view('users.index', compact('users'));
    }
}
