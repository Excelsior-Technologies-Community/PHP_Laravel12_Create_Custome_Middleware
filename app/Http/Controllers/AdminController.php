<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{


    /**
     * Admin Dashboard
     */
    public function dashboard(Request $request)
    {
        $query = User::query();

        // ---------------- SEARCH ----------------
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('role', 'LIKE', "%{$search}%");
            });
        }

        // ---------------- ROLE FILTER ----------------
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // ---------------- SORT (IMPORTANT FIX) ----------------
        // 👉 for your requirement (1,2,3 pagination correct order)
        $query->orderBy('id', 'asc');

        // ---------------- PAGINATION ----------------
        $users = $query->paginate(2)->withQueryString();

        // ---------------- STATS ----------------
        $totalUsers = User::count();

        $activeUsers = User::where('last_activity_at', '>=', now()->subDay())->count();

        $inactiveUsers = User::where(function ($q) {
            $q->whereNull('last_activity_at')
                ->orWhere('last_activity_at', '<', now()->subDay());
        })->count();

        $admins = User::where('role', 'admin')->count();

        $moderators = User::where('role', 'moderator')->count();

        $normalUsers = User::where('role', 'user')->count();

        return view('admin.dashboard', compact(
            'users',
            'totalUsers',
            'activeUsers',
            'inactiveUsers',
            'admins',
            'moderators',
            'normalUsers'
        ));
    }

    /**
     * Statistics Page
     */
    public function statistics()
    {
        $totalUsers = User::count();

        $activeUsers = User::where(
            'last_activity_at',
            '>=',
            now()->subDay()
        )->count();

        $inactiveUsers = User::where(function ($q) {
            $q->whereNull('last_activity_at')
                ->orWhere('last_activity_at', '<', now()->subDay());
        })->count();

        $admins = User::where('role', 'admin')->count();

        $moderators = User::where('role', 'moderator')->count();

        $users = User::where('role', 'user')->count();

        $onlineUsers = User::where(
            'last_activity_at',
            '>=',
            now()->subMinutes(5)
        )->count();

        $weeklyActive = User::where(
            'last_activity_at',
            '>=',
            now()->subWeek()
        )->count();

        $monthlyInactive = User::where(function ($q) {
            $q->whereNull('last_activity_at')
                ->orWhere('last_activity_at', '<=', now()->subDays(30));
        })->count();

        $latestUsers = User::latest()
            ->take(5)
            ->get();

        return view('admin.statistics', compact(
            'totalUsers',
            'activeUsers',
            'inactiveUsers',
            'admins',
            'moderators',
            'users',
            'onlineUsers',
            'weeklyActive',
            'monthlyInactive',
            'latestUsers'
        ));
    }
}