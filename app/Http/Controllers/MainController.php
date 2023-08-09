<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tournaments;
use App\Teams;
use App\Journeys;
use App\Games;

class MainController extends Controller
{
    //
    public function index() {

        $tournaments = Tournaments::where('is_deleted',0)
        ->where('status','En Progreso')
        ->get();

        $tournaments2 = Tournaments::where('is_deleted',0)
        ->where('status','Por Empezar')
        ->get();

        //we update the value of the prints
        $tournaments_arr = array();
        foreach ($tournaments as $t) {
            $prints = 0;
            $prints = $t->prints+1;
            Tournaments::where('id', $t->id)->where('is_deleted',0)->where('status','En Progreso')->update(['prints' => $prints ]);
            $tournaments_arr[] = $t->id;

        }

        foreach ($tournaments2 as $t) {
            $prints = 0;
            $prints = $t->prints+1;
            Tournaments::where('id', $t->id)->where('is_deleted',0)->where('status','En Progreso')->update(['prints' => $prints ]);
        }

        $imageNumber = rand(1, 3);

        //$teams       = Teams::whereIn('tournament_id', $tournaments)->get();
        $teams       = Teams::where('is_deleted', 0)->whereIn('tournament_id', $tournaments_arr)->get();
        $journeys    = Journeys::whereIn('tournament_id', $tournaments_arr)->get();
        $games       = Games::whereIn('tournament_id', $tournaments_arr)->get();


        return view('welcome', [
            'tournaments'   => $tournaments,
            'tournaments2'  => $tournaments2,
            'imageNumber'   => $imageNumber,
            'teams'         => $teams,
            'journeys'      => $journeys,
            'games'         => $games
        ]);
    }

}
