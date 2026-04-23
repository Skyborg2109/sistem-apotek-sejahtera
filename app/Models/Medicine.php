<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'sku', 'category', 'purchase_price', 'selling_price', 'stock', 'unit', 'expiry_date', 'supplier_id', 'image'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
