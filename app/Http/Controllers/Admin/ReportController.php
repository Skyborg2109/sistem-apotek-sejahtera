<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->toDateString());

        // 1. Stats
        $salesQuery = Sale::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        
        $totalTransactions = $salesQuery->count();
        $totalRevenue = $salesQuery->sum('total_amount');
        
        // Estimated Gross Profit = Total Revenue - Total Cost
        $totalCost = SaleItem::whereHas('sale', function($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        })->join('medicines', 'sale_items.medicine_id', '=', 'medicines.id')
          ->select(DB::raw('SUM(sale_items.quantity * medicines.purchase_price) as cost'))
          ->first()->cost ?? 0;

        $grossProfit = $totalRevenue - $totalCost;

        $stats = [
            'total_transactions' => $totalTransactions,
            'total_revenue' => $totalRevenue,
            'gross_profit' => $grossProfit,
        ];

        // 2. Chart Data (Weekly for current month)
        $chartData = Sale::select(
            DB::raw('WEEK(created_at) as week'),
            DB::raw('SUM(total_amount) as total')
        )
        ->whereMonth('created_at', Carbon::now()->month)
        ->groupBy('week')
        ->get();

        // 3. Category Data
        $categories = Medicine::select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->get();

        // 4. Latest Transactions
        $latestSales = Sale::latest()->take(10)->get();

        return view('admin.laporan', compact('stats', 'categories', 'latestSales', 'chartData', 'startDate', 'endDate'));
    }
}
