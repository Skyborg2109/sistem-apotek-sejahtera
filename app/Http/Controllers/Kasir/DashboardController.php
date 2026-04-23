<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the kasir dashboard.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $medicines = Medicine::with('supplier')->where('stock', '>', 0)->get();
        
        $filter = $request->get('filter', 'today');
        
        $salesQuery = Sale::withCount('items')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc');

        if ($filter === 'today') {
            $salesQuery->whereDate('created_at', today());
        }
        
        $sales = $salesQuery->get();

        $totalCash = Sale::where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->sum('total_amount');

        return view('kasir.index', compact('user', 'medicines', 'sales', 'totalCash', 'filter'));
    }

    /**
     * Store a new transaction.
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:medicines,id',
            'items.*.qty' => 'required|integer|min:1',
            'total_amount' => 'required|numeric',
            'paid_amount' => 'required|numeric',
            'payment_method' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $sale = Sale::create([
                'invoice_number' => 'INV-' . strtoupper(uniqid()),
                'user_id' => Auth::id(),
                'total_amount' => $request->total_amount,
                'paid_amount' => $request->paid_amount,
                'change_amount' => $request->paid_amount - $request->total_amount,
                'payment_method' => $request->payment_method,
                'cashier_name' => Auth::user()->name,
            ]);

            foreach ($request->items as $item) {
                $medicine = Medicine::find($item['id']);
                
                if ($medicine->stock < $item['qty']) {
                    throw new \Exception("Stok {$medicine->name} tidak mencukupi.");
                }

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'medicine_id' => $item['id'],
                    'quantity' => $item['qty'],
                    'unit_price' => $medicine->selling_price,
                    'total_price' => $medicine->selling_price * $item['qty'],
                ]);

                // Update stock
                $medicine->decrement('stock', $item['qty']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan.',
                'invoice_number' => $sale->invoice_number,
                'sale' => $sale
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get sale details.
     */
    public function show($id)
    {
        $sale = Sale::with('items.medicine')->findOrFail($id);
        return response()->json([
            'success' => true,
            'sale' => $sale
        ]);
    }

    /**
     * Print receipt.
     */
    public function print($id)
    {
        $sale = Sale::with('items.medicine')->findOrFail($id);
        $setting = [
            'app_name' => \App\Models\Setting::getValue('app_name', 'Apotek Sejahtera'),
            'app_address' => \App\Models\Setting::getValue('app_address', 'Jl. Raya Apotek No. 123'),
            'app_phone' => \App\Models\Setting::getValue('app_phone', '0812-3456-7890'),
        ];

        return view('kasir.print', compact('sale', 'setting'));
    }
}
