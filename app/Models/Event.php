<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';
    protected $primaryKey = 'id';
    public $timestamps = false;    

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'location',
        'created_by',
        'is_active',
        'created_at'
    ];

    public function subEvents()
    {
        return $this->hasMany(Sub_Events::class, 'event_id');
    }
}
