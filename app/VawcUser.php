<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VawcUser extends Model
{
    protected $fillable = [
        'lastname', 'firstname', 'middlename',
        'suffix', 'birthdate', 'sex',
        'contact_number', 'profile_image'
    ];

    public function records()
    {
        return $this->belongsToMany('App\VawcRecord', 'vawc_user_records', 'user_id', 'record_id')
            ->withPivot('position', 'position_status')->as('relation');
    }
}
