<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiftCertificate extends Model
{
    protected $table = 'woh_gc';
    protected $primaryKey = 'woh_gc';
    public $timestamps = false;
    protected $fillable = [
        'woh_gc',
        'bar_code',
        'gc_name',
        'description',
        'amount',
        'to',
        'pin_code',
        'entry_code',
        'cd_code',
        'status',
        'date_created'
    ];
}
