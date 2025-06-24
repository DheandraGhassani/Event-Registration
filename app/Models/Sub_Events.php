<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sub_Events extends Model
{    
    protected $table = 'sub_events';

    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'speaker',
        'location',
        'registration_fee'
    ];

    public $timestamps = false;

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
