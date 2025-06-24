<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event_Registration extends Model
{
    public $incrementing = true;
    protected $keytype = 'int';
    protected $table = 'event_registrations';

    protected $fillable = [
        'user_id',
        'sub_event_id',
        'payment_proof',
        'payment_status',
        'qr_code',
    ];

    public $timestamps = true;

    public function subEvent()
    {
        return $this->belongsTo(Sub_Events::class, 'sub_event_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function attendance()
    {
        return $this->hasOne(Attendances::class, 'registration_id');
    }
}
