<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VawcUserRecord extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'position', 'position_status',
        'record_id', 'user_id'
    ];
}
