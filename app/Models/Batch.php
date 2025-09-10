<?php

namespace App\Models;

use Carbon\Carbon; // Import the Carbon class

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;
    protected $table='batches'; // A
    protected $fillable = [
        'batch_id',
        'name',
        'description',
    ];  
    
    /** auto genarate id */
    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $getUser = self::orderBy('batch_id', 'desc')->first();

            if ($getUser) {
                $latestID = intval(substr($getUser->batch_id, 5));
                $nextID = $latestID + 1;
            } else {
                $nextID = 1;
            }
            $last_two_digit_of_current_year = Carbon::now()->format('y'); // A
            $model->batch_id = $last_two_digit_of_current_year . sprintf("%04s", $nextID);
            while (self::where('batch_id', $model->batch_id)->exists()) {
                $nextID++;
                $model->batch_id = $last_two_digit_of_current_year . sprintf("%04s", $nextID);
            }
        });
    }
}
