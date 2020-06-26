<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Team;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller {

    private function getOrdered(Request $request, $filtered) {
        $order = $request->get('filter-order');
        if (!$order) $order = 'score';

        $validOrders = [
            'score',
            'name',
            'country',
            'institution',
        ];

        if (in_array($order, $validOrders) === FALSE) {
            return back()->withErrors(['Invalid order!!']);
        }

        if ($order === 'country') {
            $teams = Team::join('countries', 'countries.id', '=', 'teams.country_id')
            ->orderBy('countries.name', 'asc')->whereIn('teams.id',$filtered)->get(['teams.*']);
        }else if ($order === 'score') {
            $teams = Team::orderBy('overall_score','desc')->whereIn('teams.id',$filtered)->get();
        }else {
            $teams = Team::orderBy($order,'asc')->whereIn('teams.id',$filtered)->get();
        }

        return $teams;
    }

    public function index(Request $request) {
        
        // ->pluck('id')->toArray();
        $filteredTeams = $this->getFiltered($request)->pluck('id')->toArray();
        $teams = $this->getOrdered($request, $filteredTeams);
        
        $countries = Country::orderBy('name','asc')->get();
        
        return view('teams.index', [
            'teams' => $teams,
            'countries' => $countries,
            'needContainer' => true,
        ]);
    }


    public function getFiltered(Request $request) {
        $name = $request->get('filter-name');
        $country = $request->get('filter-country');
        $institution = $request->get('filter-institution');
        $captain = $request->get('filter-captain');
        $minScore = $request->get('filter-min-score');
        $maxScore = $request->get('filter-max-score');

        $query = DB::table('teams');
        if ($name !== null) {
            $name = '%' . strtolower($name) . '%';
            $query = $query->whereRaw('LOWER(`name`) LIKE ? ', $name);
        }

        if ($country !== null) {
            $query = $query->where('country_id', '=', $country);
        }

        if ($institution !== null) {
            $query = $query->where('institution', '=', $institution);
        }

        if ($captain !== null) {
            $captain = '%' . strtolower($captain) . '%';
            $query = $query->whereRaw('LOWER(`captain`) LIKE ? ', $captain);
        }
        
        if ($maxScore !== null && ctype_digit($maxScore) && ($maxScore >= 0) ) {
            $query = $query->where('overall_score', '<=', $maxScore);
        }

        if ($minScore !== null && ctype_digit($minScore) && ($minScore >= 0)) {
            $query = $query->where('overall_score', '>=', $minScore);
        }
        
        $teams = $query->get();
        return $teams;
    }

    public function join($teamID, $userID) {
        if ($userID != Auth::user()->id) {
            return response()->json([
                'result' => 'error',
                'message' => 'Can not force other users to leave team!!'
            ]);
        }
        
        $team = Team::find($teamID);

        if (!$team) {
            return response()->json([
                'result' => 'error',
                'message' => 'Invalid team!!'
            ]);
        }

        if ($team->users->contains($userID)) {
            return response()->json([
                'result' => 'error',
                'message' => 'You are already in the team!!'
            ]);
        }

        if (count($team->users) >= 4) {
            return response()->json([
                'result' => 'error',
                'message' => 'This team has a maximum of 4 members!!'
            ]);
        }

        $team->users()->attach(Auth::user());
        return response()->json([
            'result' => 'success',
            'message' => 'You joined the team successfully!!'
        ]);
    }
}
