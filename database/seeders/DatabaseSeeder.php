<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 10 suppliers, and for each supplier create some medicines
        \App\Models\Supplier::factory(10)->create()->each(function ($supplier) {
            \App\Models\Medicine::factory(rand(5, 15))->create([
                'supplier_id' => $supplier->id,
            ]);
        });

        // Add some Alat Kesehatan specifically
        $alkes = [
            ['name' => 'Masker Medis 3-Ply', 'sku' => 'ALK-001', 'category' => 'Alat Kesehatan', 'purchase_price' => 25000, 'selling_price' => 35000, 'stock' => 100, 'unit' => 'Box'],
            ['name' => 'Thermometer Digital', 'sku' => 'ALK-002', 'category' => 'Alat Kesehatan', 'purchase_price' => 45000, 'selling_price' => 65000, 'stock' => 50, 'unit' => 'Pcs'],
            ['name' => 'Tensimeter Digital', 'sku' => 'ALK-003', 'category' => 'Alat Kesehatan', 'purchase_price' => 250000, 'selling_price' => 325000, 'stock' => 20, 'unit' => 'Unit'],
            ['name' => 'Hand Sanitizer 500ml', 'sku' => 'ALK-004', 'category' => 'Alat Kesehatan', 'purchase_price' => 15000, 'selling_price' => 25000, 'stock' => 80, 'unit' => 'Botol'],
            ['name' => 'Kasa Steril', 'sku' => 'ALK-005', 'category' => 'Alat Kesehatan', 'purchase_price' => 5000, 'selling_price' => 8500, 'stock' => 200, 'unit' => 'Pack'],
        ];

        foreach ($alkes as $item) {
            \App\Models\Medicine::create($item + [
                'expiry_date' => \Carbon\Carbon::now()->addYears(3),
                'supplier_id' => \App\Models\Supplier::first()->id,
            ]);
        }

        // Create Admin
        User::create([
            'name' => 'Budi Santoso',
            'username' => 'budisantoso',
            'email' => 'admin@apotek.com',
            'password' => bcrypt('password'),
            'role' => 'Administrator',
            'is_active' => true,
        ]);

        // Create Kasir
        User::create([
            'name' => 'Siti Rahma',
            'username' => 'sitirahma',
            'email' => 'siti@apotek.com',
            'password' => bcrypt('password'),
            'role' => 'Kasir',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Joko Anwar',
            'username' => 'jokoanwar',
            'email' => 'joko@apotek.com',
            'password' => bcrypt('password'),
            'role' => 'Kasir',
            'is_active' => false,
        ]);

        // Seed Settings
        $this->call(SettingSeeder::class);

        // Seed Sales
        $this->call(SaleSeeder::class);
    }
}
