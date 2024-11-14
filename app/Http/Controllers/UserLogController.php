<?php

namespace App\Http\Controllers;

use App\Models\UserLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserLogController extends Controller
{
    public function index()
    {
        // Check if the user is an admin
        if (Auth::user()->hasRole('admin')) {
            // Admin can view all logs
            $logs = UserLog::with('user')->latest()->paginate(10);
        } else {
            // Regular user can only view their own logs
            $logs = UserLog::with('user')
                ->where('user_id', Auth::id())
                ->latest()
                ->paginate(10);
        }

        return view('logs.index', compact('logs'));
    }
}
