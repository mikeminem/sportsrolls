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
        
        //we update the value of the prints
        foreach ($tournaments as $t) {
            $prints = 0;
            $prints = $t->prints+1;
            Tournaments::where('id', $t->id)->where('is_deleted',0)->where('status','En Progreso')->update(['prints' => $prints ]);
        }

        return view('welcome', [
            'tournaments' => $tournaments
        ]);
    }

}
