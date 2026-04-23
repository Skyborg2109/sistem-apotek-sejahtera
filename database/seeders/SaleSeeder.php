<?php

namespace Database\Seeders;

use App\Models\Medicine;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SaleSeeder extends Seeder
{
    public function run(): void
    {
        $medicines = Medicine::all();
        if ($medicines->isEmpty()) return;

        $paymentMethods = ['Tunai', 'QRIS / E-Wallet', 'Kartu Debit/Kredit'];
        $cashierUsers = \App\Models\User::all();

        // Generate sales for the last 30 days
        for ($i = 0; $i < 50; $i++) {
            $date = Carbon::now()->subDays(rand(0, 30));
            $user = $cashierUsers->random();
            
            $sale = Sale::create([
                'invoice_number' => 'INV-' . $date->format('md') . '-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'user_id' => $user->id,
                'total_amount' => 0,
                'paid_amount' => 0,
                'change_amount' => 0,
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'cashier_name' => $user->name,
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            $totalAmount = 0;
            $itemCount = rand(1, 5);
            
            for ($j = 0; $j < $itemCount; $j++) {
                $medicine = $medicines->random();
                $qty = rand(1, 3);
                $subtotal = $medicine->selling_price * $qty;
                
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'medicine_id' => $medicine->id,
                    'quantity' => $qty,
                    'unit_price' => $medicine->selling_price,
                    'total_price' => $subtotal,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);

                $totalAmount += $subtotal;
            }

            $sale->update([
                'total_amount' => $totalAmount,
                'paid_amount' => $totalAmount + rand(0, 50000), // Simulate cash payment
                'change_amount' => 0 // In seeder we can leave it 0 or calc it
            ]);
        }
    }
}
