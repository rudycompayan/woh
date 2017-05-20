<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberUnilevelEarning extends Model
{
    protected $table = 'woh_member_unilevel_earning';
    protected $primaryKey = 'woh_member_unilevel_earning';
    public $timestamps = false;
    protected $fillable = [
        'woh_member',
        'period_cover_start',
        'period_cover_end',
        'level',
        'amount_earn',
        'status'
    ];
}
