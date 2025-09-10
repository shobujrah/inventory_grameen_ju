<?php

namespace App\Models;

use App\Models\ProductCategory;

use App\Models\RequisitionItem;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; // Import the Carbon class
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    // protected $table='categories'; // A
    protected $fillable = [
        'name',
        'product_type',
        'product_category_id',
        'description',
        'price',
        'batch',
        'image',
        'sku',
        'code',
    ];

    /** auto genarate id */
    // protected static function boot()
    // {
    //     parent::boot();
    //     self::creating(function ($model) {
    //         $getUser = self::orderBy('product_id', 'desc')->first();

    //         if ($getUser) {
    //             $latestID = intval(substr($getUser->product_id, 5));
    //             $nextID = $latestID + 1;
    //         } else {
    //             $nextID = 1;
    //         }
    //         $last_two_digit_of_current_year = Carbon::now()->format('y'); // A
    //         $model->product_id = $last_two_digit_of_current_year . sprintf("%04s", $nextID);
    //         while (self::where('product_id', $model->product_id)->exists()) {
    //             $nextID++;
    //             $model->product_id = $last_two_digit_of_current_year . sprintf("%04s", $nextID);
    //         }
    //     });
    // }



    public function requisitions()
    {
        return $this->hasMany(Requisition::class);
    }


    public function stock()
    {
        return $this->hasMany(RequisitionItem::class, 'product_id');
    }

    public function branchProducts()
    {
        return $this->hasMany(Branch_Product::class, 'product_id');
    }

    public static function productName($id)
    {
        return self::where('id', $id)->value('name');
    }


    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }
    

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }



}
