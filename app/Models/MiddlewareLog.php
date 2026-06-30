<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MiddlewareLog extends Model
{

    protected $fillable = [
        'user_id',
        'middleware_name',
        'route',
        'method',
        'ip_address',
        'user_agent'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

}