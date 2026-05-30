<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Expense;
use App\Models\Outlet;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->check() && auth()->user()->hasRole('super-admin')) {
            $stats = [
                'total_tenants' => \App\Models\User::whereHas('role', function($q) { $q->where('slug', 'admin-outlet'); })->count(),
                'total_outlets' => Outlet::count(),
                'total_transactions' => Transaction::count(),
                'total_revenue' => Transaction::sum('grand_total'),
            ];

            $recentTenants = \App\Models\User::with('role')
                ->whereHas('role', function($q) { $q->where('slug', 'admin-outlet'); })
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            // Monthly Revenue Growth Chart for Current Year
            $growthChart = Transaction::select(
                DB::raw('EXTRACT(MONTH FROM transaction_date) as month'),
                DB::raw('SUM(grand_total) as total')
            )
            ->whereYear('transaction_date', date('Y'))
            ->groupBy(DB::raw('EXTRACT(MONTH FROM transaction_date)'))
            ->orderBy('month')
            ->get();

            return view('page.superadmin.dashboard.index', compact('stats', 'recentTenants', 'growthChart'));
        }

        $today = now()->startOfDay();
        $thisMonth = now()->startOfMonth();

        $stats = [
            'today_transactions' => Transaction::whereDate('transaction_date', $today)->count(),
            'today_revenue' => Transaction::whereDate('transaction_date', $today)->sum('grand_total'),
            'monthly_revenue' => Transaction::whereMonth('transaction_date', now()->month)->sum('grand_total'),
            'process_count' => Transaction::whereIn('status', ['Diterima', 'Dicuci', 'Disetrika'])->count(),
            'completed_count' => Transaction::where('status', 'Selesai')->count(),
            'total_outlets' => Outlet::count(),
            'total_customers' => \App\Models\Customer::count(),
        ];

        // Grafik 7 Hari Terakhir
        $chartData = Transaction::select(
            DB::raw('CAST(transaction_date AS DATE) as date'),
            DB::raw('SUM(grand_total) as total')
        )
        ->where('transaction_date', '>=', now()->subDays(7))
        ->groupBy(DB::raw('CAST(transaction_date AS DATE)'))
        ->orderBy('date')
        ->get();

        $recentTransactions = Transaction::with(['customer', 'details'])
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        return view('page.admin.dashboard.index', compact('stats', 'chartData', 'recentTransactions'));
    }
}
