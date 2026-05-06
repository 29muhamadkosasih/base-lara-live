<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Product;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'products' => Product::count(),
            'activities' => AuditLog::count(),
            'today_activities' => AuditLog::whereDate('created_at', today())->count(),
        ];

        $recentActivities = AuditLog::with('user')->latest()->limit(8)->get();

        return view('home', compact('stats', 'recentActivities'));
    }
}
