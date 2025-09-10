<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchHeadofficeLog extends Model
{ 
   use HasFactory;

    protected $fillable = [
        'branch_id',
        'requisition_id',
        'product_id',
        'price_quantity',
        'date',
        'user_id',
    ];

    public function requisition()
    {
        return $this->belongsTo(Requisition::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}