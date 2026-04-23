<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\Supplier;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_medicines' => Medicine::count(),
            'total_suppliers' => Supplier::count(),
            'today_sales' => 142, // Mocked for now
            'monthly_revenue' => '84.5M', // Mocked for now
            'revenue_target_percent' => 75,
            'low_stock_count' => Medicine::where('stock', '<', 20)->count(),
            'expired_soon_count' => Medicine::where('expiry_date', '<', now()->addDays(30))->count(),
        ];

        // Alerts: Combined list of low stock and expiring soon
        $alerts = Medicine::where('stock', '<', 20)
            ->orWhere('expiry_date', '<', now()->addDays(30))
            ->orderBy('expiry_date', 'asc')
            ->take(5)
            ->get();

        // Best Sellers (Mocked based on stock logic or just random for now as we don't have sales table)
        $best_sellers = Medicine::orderBy('stock', 'asc')->take(5)->get();

        return view('admin.index', compact('stats', 'alerts', 'best_sellers'));
    }
}
