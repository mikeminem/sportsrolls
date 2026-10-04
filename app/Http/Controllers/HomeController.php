<?php

namespace App\Http\Controllers;

use App\Games;
use App\Journeys;
use App\Leagues;
use App\Profiles;
use App\Tournaments;
use App\Teams;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $hasLeague   = Leagues::where('user_id', Auth::user()->id);
        $hasProfile  = Profiles::where('user_id',Auth::user()->id);

        $tournaments = Tournaments::where('is_deleted',0)->where('status','En Progreso')->where('user_id', Auth::user()->id)->get(['id','title']);
        $teams       = Teams::whereIn('tournament_id', $tournaments)->get();
        $journeys    = Journeys::whereIn('tournament_id', $tournaments)->get();
        $games       = Games::whereIn('tournament_id', $tournaments)->get();

       // dd($teams);
    
        return view('home', [
           'hasProfile'     => $hasProfile,
           'hasLeague'      => $hasLeague,
           'tournaments'    => $tournaments,
           'teams'          => $teams,
           'journeys'       => $journeys,
           'games'          => $games
        ]);
    }
}
