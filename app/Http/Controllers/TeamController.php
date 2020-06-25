<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Team;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() { }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        $user = Auth::user();
        $teams = $user->teams;
        $country = Country::all();
        
        return view('teams.myteams', [
            'user' => $user,
            'teams' => $teams,
            'countries' => $country
        ]);
    }

    public function store(Request $request) {
        $name = $request->get('team');
        $countryID = $request->get('country');
        $institution = $request->get('institution');

        $country = Country::find($countryID);

        if (strlen($name) < 3) {
            return back()->withErrors(['Invalid name!!']);
        }

        if (!$country) {
            return back()->withErrors(['Invalid country!!']);
        }

        if ($institution !== "free" && $institution !== "school" && $institution !== "college") {
            return back()->withErrors(['Invalid institution!!']);
        }

        $team = new Team();
        $team->name = $name;
        $team->country_id = $countryID;
        $team->institution = $institution;
        $team->captain = Auth::user()->username;
        $team->save();

        $team->users()->attach(Auth::user());

        return redirect()->route('myteams.index');
    }


    public function update($teamID, Request $request) {
        $name = $request->get('team');
        $institution = $request->get('institution');
        $countryID = $request->get('country');
        $country = Country::find($countryID);
        $team = Team::find($teamID);

        if ($team->captain != Auth::user()->username) {
            return back()->withErrors(['Hold on, you\'re not the team Captain!!']);
        }

        if ($name != $team->name) {
            $existingName = Team::where('name',$name)->first();
            if ($existingName) return back()->withErrors(['Another team with the same name already exists.']);
        }

        if (strlen($name) < 3) {
            return back()->withErrors(['Invalid name!!']);
        }

        if (!$country) {
            return back()->withErrors(['Invalid country!!']);
        }

        if ($institution !== "free" && $institution !== "school" && $institution !== "college") {
            return back()->withErrors(['Invalid institution!!']);
        }

        $team->name = $name;
        $team->country_id = $countryID;
        $team->institution = $institution;
        $team->save();

        return redirect()->route('myteams.index');
    }

    public function kick($teamID, $userID) {
        $team = Team::find($teamID);
        $user = User::find($userID);

        if (!$team) {
            return response()->json([
                'result' => 'error',
                'message' => 'Invalid team!!'
            ]);
        }

        if (!$user) {
            return response()->json([
                'result' => 'error',
                'message' => 'Invalid user!!'
            ]);
        }

        if (!$team->users->contains($user)) {
            return response()->json([
                'result' => 'error',
                'message' => 'User is not in the team!!'
            ]);
        }

        if ($team->captain !== Auth::user()->username) {
            return response()->json([
                'result' => 'error',
                'message' => 'Hold on, you\'re not the team Captain!!'
            ]);
        }

        if ($user->username == $team->captain) {
            return response()->json([
                'result' => 'error',
                'message' => 'Sorry captains is not removable!!'
            ]);
        }

        $team->users()->detach($user);
        return response()->json([
            'result' => 'success',
            'message' => 'User successfully kicked from the team.'
        ]);
    }


    public function delete($teamID){
        $team = Team::find($teamID);
        
        if (!$team) {
            return back()->withErrors(['Invalid team to delete!!']);
        }

        if ($team->captain !== Auth::user()->username) {
            return back()->withErrors(['Hold on, you\'re not the team Captain!!']);
        }

        foreach ($team->users as $user) {
            $team->users()->detach($user);
        }

        $team->delete();
        return redirect()->route('myteams.index');
    }
}
