<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReturnWarehouse extends Model
{
    use HasFactory;

     protected $fillable = [
        'branch_id',
        'product_id',
        'return_quantity',
        'price',
        'reason',
        'date',
        'user_id',
        'status',
        'notification_status',
        'deny_status',
        'deny_reason_note'

    ];


    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
}
