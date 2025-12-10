<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function __construct()
    {
        // Apply 'auth' middleware to all methods
        $this->middleware('auth');
        
        // Apply 'role' middleware to all methods
        $this->middleware('role:admin');
    }
    
    public function dashboard()
    {
        $users = User::orderBy('last_activity_at', 'desc')->get();
        
        return view('admin.dashboard', compact('users'));
    }
    
    public function statistics()
    {
        $totalUsers = User::count();
        $activeUsers = User::where('last_activity_at', '>=', now()->subDay())->count();
        $admins = User::where('role', 'admin')->count();
        
        return view('admin.statistics', compact('totalUsers', 'activeUsers', 'admins'));
    }
}