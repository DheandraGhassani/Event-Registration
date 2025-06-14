<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    public $timestamps = false;
    public $incrementing = false;
    protected $keytype = 'int';

    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'created_at'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'id' => 'integer',
    ];
}
