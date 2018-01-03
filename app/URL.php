<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class URL extends Model
{
    protected $table = 'u_r_ls';

    protected $fillable = ['collection_id', 'short_url', 'long_url', 'all_time', 'month', 'week', 'day', 'two_hours'];

    public function toArray() {
        $this->all_time = (int) $this->all_time;
        $this->month = (int) $this->month;
        $this->week = (int) $this->week;
        $this->day = (int) $this->day;
        $this->two_hours = (int) $this->two_hours;
        return parent::toArray();
    }
}
