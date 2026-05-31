<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    protected $fillable = [
        'message',
        'stack_trace',
        'url',
        'method',
        'ip_address',
        'status',
    ];
}
