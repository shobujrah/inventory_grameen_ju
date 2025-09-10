<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'created_by',
    ];    

    public static function projectName($id)
    {
        return self::where('id', $id)->value('name');
    }


}
