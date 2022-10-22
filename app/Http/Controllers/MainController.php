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
        
        foreach ($tournaments as $t) {
            $prints = $t->prints+1;
            Tournaments::where('is_deleted',0)->where('status','En Progreso')->update(['prints' => $prints ]);
        }

        return view('welcome', [
            'tournaments' => $tournaments
        ]);
    }

}
