<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;


class Requisition extends Model
{
    use HasFactory;
    protected $fillable = [
        'branch_id',
        'project_id',
        'date_from',
        'user_id',
        'status',
        'alldone_status',
        'partial_delivery',
        'partial_reject',
        'partial_stock',
        'partial_purchase',
        'document',
        'reject_note',
        'pending_purchase_status',
        'purchase_approve',
        'purchase_reject',
        'purchaseteam_reject_note',
        'headoffice_approve',
        'headoffice_reject',
        'headoffice_reject_note',
        'pending_approval_status_headoffice'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function product()
    {
        return $this->belongsTo(product::class, 'product_id');
    }
    

    public function products()
    {
        return $this->belongsTo(Product::class);
    }



    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function items()
    {
        return $this->hasMany(RequisitionItem::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    
}
