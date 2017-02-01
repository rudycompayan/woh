<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DownlineLevel extends Model
{
    protected $table = 'woh_downline_level';
    protected $primaryKey = 'woh_downline_level';
    public $timestamps = false;
    protected $fillable = [
        'parent_member',
        'downline_member',
        'main_position',
        'sub_position',
        "level",
        "status"
    ];
}
