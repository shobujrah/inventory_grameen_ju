<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch_Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'product_id',
        'price',
        'stock',
        'batch',
        'details_stockin',
        'remain_details',
        'details_stockout',
    ];


    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

}
