<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAccountMap extends Model
{
    use HasFactory; 

    protected $fillable = [
        'product_id',
        'product_category_id',
        'product_code',
        'product_name',
        'account_asset_inventory_code',
        'account_expense_code',
        'account_income_code',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id', 'id');
    }  



    public function incomeAccount()
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_income_code', 'code');
    }
    
    public function expenseAccount()
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_expense_code', 'code');
    }   




}
