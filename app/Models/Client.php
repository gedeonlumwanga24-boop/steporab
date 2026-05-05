<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'user_id',
        'telephone',
        'adresse',
        'ville',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
