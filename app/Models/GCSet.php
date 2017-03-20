<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GCSet extends Model
{
    protected $table = 'woh_gc_set';
    protected $primaryKey = 'woh_gc_set';
    public $timestamps = false;
    protected $fillable = [
        'woh_gc_set',
        'entry_code',
        'cd_code',
        'product_code'
    ];
}
