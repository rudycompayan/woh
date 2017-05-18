<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberGC extends Model
{
    protected $table = 'woh_member_gc';
    protected $primaryKey = 'woh_member_gc';
    public $timestamps = false;
    protected $fillable = [
        'woh_member',
        'woh_gc',
        'date_claim',
        'notes',
        'gc_qty'
    ];
}
