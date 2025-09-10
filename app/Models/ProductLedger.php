<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductLedger extends Model
{
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
        'batch',
        'requisition_id',
        'payment_method',
        'invoice_no',
        'chart_of_account_id',
        'chart_of_account_code',

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
