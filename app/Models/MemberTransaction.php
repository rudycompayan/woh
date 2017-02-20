<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberTransaction extends Model
{
    protected $table = 'woh_member_transaction';
    protected $primaryKey = 'woh_member_transaction';
    public $timestamps = false;
    protected $fillable = [
        'woh_member',
        'woh_transaction_type',
        'transaction_date',
        'tran_amount',
        "transaction_referred",
        "transaction_no",
        "check_number",
        "issuance_date",
        "notes",
        "tax",
        "cd_payment",
        "status",
        "admin_notes",
        "change",
        "level"
    ];
}
