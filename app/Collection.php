<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $fillable = ['name', 'google_auth_id'];

    public function urls()
    {
        return $this->hasMany('App\URL');
    }

    public function auth()
    {
        return $this->belongsTo('App\GoogleAuth', 'google_auth_id', 'id');
    }
}
