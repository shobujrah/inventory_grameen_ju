<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagePermission extends Model
{
    use HasFactory;
    // protected $table='role_type_users'; A

    protected $fillable = [
        'user_id',
        'role_type',
        'route',
    ];

    /** auto genarate id */
    // protected static function boot()
    // {
    //     parent::boot();
    //     self::creating(function ($model) {
    //         $getUser = self::orderBy('role_id', 'desc')->first();

    //         if ($getUser) {
    //             $latestID = intval(substr($getUser->role_id, 5));
    //             $nextID = $latestID + 1;
    //         } else {
    //             $nextID = 1;
    //         }
    //         $model->role_id = 'PRE' . sprintf("%03s", $nextID);
    //         while (self::where('role_id', $model->role_id)->exists()) {
    //             $nextID++;
    //             $model->role_id = 'PRE' . sprintf("%03s", $nextID);
    //         }
    //     });
    // }
}
