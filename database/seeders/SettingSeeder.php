<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'app_name' => 'Apotek Sejahtera',
            'app_sia' => 'SIA/123.45/2024',
            'app_pharmacist' => 'Dra. Siti Aminah, Apt.',
            'app_phone' => '0812-9988-7766',
            'app_email' => 'halo@apoteksejahtera.com',
            'app_address' => 'Jl. Kesehatan No. 123, Kel. Sembuh, Kec. Medis, Kota Sehat 40112',
            'tax_percentage' => '11',
            'tax_enabled' => '1',
            'discount_percentage' => '0',
            'discount_enabled' => '0',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
