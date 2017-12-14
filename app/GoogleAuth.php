<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoogleAuth extends Model
{
    protected $fillable = [
        'google_id', 'token', 'refresh_token', 'name', 'email', 'expiry_time'
    ];

    protected $hidden = [
        'token', 'refresh_token', 'expiry_time'
    ];

    protected $dates = [ 'expiry_time' ];
}
