<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $table='role_type_users'; 

    protected $fillable = [
        'role_id',
        'role_type',
    ];

    /** auto genarate id */
    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $getUser = self::orderBy('role_id', 'desc')->first();

            if ($getUser) {
                $latestID = intval(substr($getUser->role_id, 5));
                $nextID = $latestID + 1;
            } else {
                $nextID = 1;
            }
            $model->role_id = 'PRE' . sprintf("%03s", $nextID);
            while (self::where('role_id', $model->role_id)->exists()) {
                $nextID++;
                $model->role_id = 'PRE' . sprintf("%03s", $nextID);
            }
        });
    }

    public function roles()
    {
        return $this->hasMany(Role::class);    
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class);
    }



}
