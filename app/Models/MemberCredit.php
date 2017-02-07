<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberCredit extends Model
{
    protected $table = 'woh_member_credit';
    protected $primaryKey = 'woh_member_credit';
    public $timestamps = false;
    protected $fillable = [
        'woh_member',
        'credit_amount'
    ];
}
