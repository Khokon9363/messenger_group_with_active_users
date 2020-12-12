<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Messenger extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User', 'sender', 'id');
    }
}
