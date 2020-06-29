<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contest extends Model {
    
    public function problems() {
        return $this->hasMany('App\Models\Problem');
    }

    public function teams() {
        return $this->belongsToMany('App\Models\Team');
    }

}
