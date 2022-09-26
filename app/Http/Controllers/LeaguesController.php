<?php

namespace App\Http\Controllers;

use App\Leagues;
use App\User;
use App\Sport;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LeaguesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        $rules = [
            'name'      => 'required|max:120|min:10',
            'sport_id' => 'required'
        ];

        $attributes = [
            'name'      => 'Nombre de la liga',
            'sport_id'  => 'Deporte'
        ];

        //Validate the data
        $validated = Validator::make($request->all(),$rules, [] , $attributes);


        if ($validated->fails()) {
            return response()->json([
                'type' => 'error',
                'message' => 'Error al intentar guardar la información de la liga!',
                'errors' => $validated->errors()
            ]);
        }

        if ($request) {
            $user_id = Auth::user()->id;
            $league = Leagues::find(Auth::user()->id);
            if ($league) {
                //update info
                $model = User::find(Auth::user()->id)->league;
                $model->fill($request->all());

                if ($model->update()) 
                    return response()->json([
                        'type' => 'success',
                        'message' => 'Información de la liga guardada!'
                    ]);
            } else {
                //New entry
                $model = new Leagues($request->all());
                $model->user_id = Auth::user()->id;
                if ($model->save()) 
                    return response()->json([
                        'type' => 'success',
                        'message' => 'Información de la liga guardada!'
                    ]);
            }
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar guardar la información de la liga!'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Leagues  $leagues
     * @return \Illuminate\Http\Response
     */
    public function show(Leagues $leagues)
    {
        //
        $sports = Sport::where('is_deleted',0)->get(['id', 'name']);
        $league = User::find(Auth::user()->id)->league;
        
        return view("leagues.league_info", [ 
            'league' => $league,
            'sports'=>$sports
        ]);
    }

}
