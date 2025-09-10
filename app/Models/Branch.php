<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    protected $table='branch'; // A
    protected $fillable = [
        'name',
        'address',
        'type',
        'mobile_no',
        'email'

    ];


    public function requisitions()
    {
        return $this->hasMany(Requisition::class, 'branch_id');
    }

    public function branchProducts()
    {
        return $this->hasMany(Branch_Product::class, 'branch_id');
    }


}
