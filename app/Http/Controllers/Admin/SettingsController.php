<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->all();
        
        // Default values if empty
        $defaults = [
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
            'app_logo' => null,
        ];

        $settings = array_merge($defaults, $settings);

        return view('admin.pengaturan', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', 'app_logo']);
        
        // Handle Booleans
        $data['tax_enabled'] = $request->has('tax_enabled') ? '1' : '0';
        $data['discount_enabled'] = $request->has('discount_enabled') ? '1' : '0';

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // Handle Logo Upload
        if ($request->hasFile('app_logo')) {
            $oldLogo = Setting::where('key', 'app_logo')->first();
            if ($oldLogo && $oldLogo->value) {
                Storage::disk('public')->delete($oldLogo->value);
            }
            
            $path = $request->file('app_logo')->store('settings', 'public');
            Setting::updateOrCreate(['key' => 'app_logo'], ['value' => $path]);
        }

        return redirect()->back()->with('success', 'Pengaturan sistem berhasil diperbarui!');
    }

    public function deleteLogo()
    {
        $logoSetting = Setting::where('key', 'app_logo')->first();
        if ($logoSetting && $logoSetting->value) {
            Storage::disk('public')->delete($logoSetting->value);
            $logoSetting->update(['value' => null]);
        }
        return redirect()->back()->with('success', 'Logo berhasil dihapus!');
    }
}
