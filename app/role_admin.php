<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class role_admin extends Model
{
    public function role()
    {
        return $this->belongsTo('App\role');
    }
}
