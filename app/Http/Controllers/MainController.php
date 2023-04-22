<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tournaments;

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
        foreach ($tournaments as $t) {
            $prints = 0;
            $prints = $t->prints+1;
            Tournaments::where('id', $t->id)->where('is_deleted',0)->where('status','En Progreso')->update(['prints' => $prints ]);
        }

        $imageNumber = rand(1, 3);

        return view('welcome', [
            'tournaments' => $tournaments,
            'tournaments2' => $tournaments2,
            'imageNumber' => $imageNumber
        ]);
    }

}
