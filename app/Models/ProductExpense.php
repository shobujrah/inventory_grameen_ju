<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'consignee_name',
        'expense_date',
        'user_id',
        'product_id',
        'expense_amount',
        'expense_price',
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
