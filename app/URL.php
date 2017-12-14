<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;

class URL extends Model
{
    use HasTags;

    protected $table ='u_r_ls';

    protected $fillable = [
        'short_url', 'long_url'
    ];
}
