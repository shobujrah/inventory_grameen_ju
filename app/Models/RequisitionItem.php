<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisitionItem extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'requisition_id',
        'product_id',
        'product_description',
        'single_product_name',
        'price',
        'newprice_qty',
        'demand_amount',
        'total_price',
        'reject_note',
        'comment',
        'delivery',
        'reject',
        'purchase',
        'stock_status',
        'purchase_team_reject',
        'headoffice_approval',
        'headoffice_rejected',
        
    ];


    public function requisitions()
    {
       return $this->belongsTo(Requisition::class);    
   }

   // In RequisitionItem.php
    public function requisition()
    {
        return $this->belongsTo(Requisition::class, 'requisition_id');
    } 

    //new code 
    public function productnamedemand()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }



}
