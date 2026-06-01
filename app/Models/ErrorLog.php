<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    public const STATUS_PENDING = 'en_attente';

    public const STATUS_RESOLVED = 'resolu';

    protected $fillable = [
        'message',
        'stack_trace',
        'url',
        'method',
        'ip_address',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
