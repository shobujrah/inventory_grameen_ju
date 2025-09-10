<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductLedgerBH extends Model
{
    use HasFactory;

    protected $fillable = [
        'entry_date',
        'narration',
        'type',
        'user_id',
        'branch_id',
        'product_id',
        'consignee_name',
        'quantity',
        'price',
        'requisition_id',
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
