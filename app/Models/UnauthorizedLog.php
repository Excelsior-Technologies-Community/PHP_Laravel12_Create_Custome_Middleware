<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnauthorizedLog extends Model
{

    protected $fillable = [
        'user_id',
        'route',
        'required_role',
        'user_role',
        'ip_address'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
