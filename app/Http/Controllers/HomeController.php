<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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

        // Chart data: aktivitas 7 hari terakhir
        $days = collect(range(6, 0))->map(fn ($i) => Carbon::today()->subDays($i));

        $activityCounts = AuditLog::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', Carbon::today()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->pluck('count', 'date');

        $chartData = [
            'labels' => $days->map(fn ($d) => $d->format('d M'))->values()->toArray(),
            'values' => $days->map(fn ($d) => $activityCounts->get($d->toDateString(), 0))->values()->toArray(),
        ];

        $latestProducts = Product::latest()->limit(5)->get();

        return view('home', compact('stats', 'chartData', 'latestProducts'));
    }
}
