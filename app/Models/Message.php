<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'is_admin',
        'nom',
        'email',
        'message',
        'status',
        'reply',
        'client_read'
    ];

    protected $casts = [
        'client_read' => 'boolean',
    ];
}