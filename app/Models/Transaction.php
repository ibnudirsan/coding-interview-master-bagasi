<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'product_id',
        'voucher_id',
        'total_price',
        'discount_amount',
        'total_pay',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
}
