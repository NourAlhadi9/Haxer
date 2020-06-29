<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model {
    
    public function problem() {
        return $this->belongsTo('App\Models\Problem');
    }

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function team() {
        return $this->belongsTo('App\Models\Team');
    }
}
