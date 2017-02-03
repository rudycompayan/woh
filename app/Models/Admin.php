<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'woh_admin';
    protected $primaryKey = 'woh_admin';
    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        "bday",
        "gender",
        "user_type",
        "status",
        "date_created",
        "username",
        "password",
    ];
}
