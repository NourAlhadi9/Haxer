<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use Illuminate\Http\Request;

class ContestController extends Controller {
    
    public function index() {
        $contests = Contest::all();

        return view('contests.index',[
            'contests' => $contests,
        ]);
    }

}
