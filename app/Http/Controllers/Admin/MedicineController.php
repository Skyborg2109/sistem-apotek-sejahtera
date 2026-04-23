<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class MedicineController extends Controller
{
    public function index()
    {
        $medicines = Medicine::with('supplier')->latest()->paginate(10);
        $suppliers = Supplier::where('is_active', true)->get();
        return view('admin.obat', compact('medicines', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'expiry_date' => 'required|date',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'image' => 'nullable|image|max:5120',
        ]);

        $validated['sku'] = 'MED-' . Str::upper(Str::random(6));
        $validated['unit'] = 'Strip';

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('medicines', 'public');
            $validated['image'] = $path;
        }

        Medicine::create($validated);

        return redirect()->route('admin.obat')->with('success', 'Obat berhasil ditambahkan!');
    }

    public function update(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'expiry_date' => 'required|date',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'image' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($medicine->image) {
                Storage::disk('public')->delete($medicine->image);
            }
            $path = $request->file('image')->store('medicines', 'public');
            $validated['image'] = $path;
        }

        $medicine->update($validated);

        return redirect()->route('admin.obat')->with('success', 'Data obat berhasil diperbarui!');
    }

    public function destroy(Medicine $medicine)
    {
        if ($medicine->image) {
            Storage::disk('public')->delete($medicine->image);
        }
        $medicine->delete();
        return redirect()->route('admin.obat')->with('success', 'Obat berhasil dihapus!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');
        fgetcsv($handle);

        $count = 0;
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            Medicine::create([
                'sku' => 'MED-' . Str::upper(Str::random(6)),
                'name' => $data[0],
                'category' => $data[1],
                'purchase_price' => $data[2],
                'selling_price' => $data[3],
                'stock' => $data[4],
                'unit' => 'Strip',
                'expiry_date' => $data[5],
                'supplier_id' => $data[6] ?? null,
            ]);
            $count++;
        }

        fclose($handle);

        return redirect()->route('admin.obat')->with('success', "$count data obat berhasil diimport!");
    }
}
