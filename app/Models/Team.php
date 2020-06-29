<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model {
    
    public function users() {
        return $this->belongsToMany('App\Models\User');
    }

    public function country() {
        return $this->belongsTo('App\Models\Country');
    }

    public function submissions() {
        return $this->hasMany('App\Models\Submission');
    }

    public function contests() {
        return $this->belongsToMany('App\Models\Contest');
    }

}
