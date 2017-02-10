<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = 'woh_member';
    protected $primaryKey = 'woh_member';
    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'address',
        "bday",
        "gender",
        "tree_position",
        "sponsor",
        "downline_of",
        "picture" ,
        "username",
        "password",
        "status",
        "cd_code"
    ];
}
