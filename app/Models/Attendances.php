<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendances extends Model
{
    protected $table = 'attendances';
    public $timestamps = true;
    const CREATED_AT = 'uploaded_at';
    const UPDATED_AT = 'uploaded_at';

    protected $fillable = [
        'registration_id',
        'scan_time',
        'scanned_by',
        'certificate_file',
        'uploaded_by',
        'uploaded_at',
    ];

    public function registration()
    {
        return $this->belongsTo(Event_Registration::class, 'registration_id');
    }

    public function scanner()
    {
        return $this->belongsTo(User::class, 'scanned_by');
    }

    public function scannedBy()
    {
        return $this->belongsTo(User::class, 'scanned_by');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
