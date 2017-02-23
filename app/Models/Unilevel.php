<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unilevel extends Model
{
    protected $table = 'woh_unilevel';
    protected $primaryKey = 'woh_unilevel';
    public $timestamps = false;
    protected $fillable = [
        'woh_member',
        'product_code',
        "date_encoded"
    ];
}
