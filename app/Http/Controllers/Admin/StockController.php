<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = Medicine::with('supplier');

        $filter = $request->query('filter', 'all');

        if ($filter == 'low_stock') {
            $query->where('stock', '<', 20);
        } elseif ($filter == 'expired') {
            $query->where('expiry_date', '<', now());
        } elseif ($filter == 'expiring_soon') {
            $query->whereBetween('expiry_date', [now(), now()->addDays(30)]);
        }

        $medicines = $query->orderBy('stock', 'asc')->paginate(15);

        $stats = [
            'low_stock' => Medicine::where('stock', '<', 20)->count(),
            'expired' => Medicine::where('expiry_date', '<', now())->count(),
            'expiring_soon' => Medicine::whereBetween('expiry_date', [now(), now()->addDays(30)])->count(),
        ];

        return view('admin.stok', compact('medicines', 'stats', 'filter'));
    }
}
