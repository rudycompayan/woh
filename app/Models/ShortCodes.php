<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShortCodes extends Model
{
    protected $table = 'woh_short_codes';
    protected $primaryKey = 'woh_short_codes';
    public $timestamps = false;
    protected $fillable = [
        'code',
        'type',
        "status"
    ];
}
