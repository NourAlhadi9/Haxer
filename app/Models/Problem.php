<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Problem extends Model {
    public function category() {
        return $this->belongsTo('App\Models\Category');
    }

    public function contest() {
        return $this->belongsTo('App\Models\Contest');
    }

    public function submissions() {
        return $this->hasMany('App\Models\Submission');
    }
    
}
