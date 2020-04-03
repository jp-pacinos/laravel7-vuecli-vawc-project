<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VawcRecord extends Model
{
    protected $fillable = [
        'title', 'datetime_happened', 'body'
    ];

    protected $dates = [
        'datetime_happened'
    ];

    public function involved()
    {
        return $this
            ->belongsToMany('App\VawcUser', 'vawc_user_records', 'record_id', 'user_id')
            ->withPivot('position', 'position_status')
            ->as('relation');
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleting(function ($record) {
            $record->involved()->delete();
        });

        // any update to records will also delete its relation from db
        // static::updating(function ($record) {
        //     $record->involved()->delete();
        // });
    }
}
