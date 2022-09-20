<?php

namespace App\Http\Controllers;

use App\Leagues;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LeaguesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'name'      => 'required|max:120|min:10'
        ];

        $attributes = [
            'name'      => 'Nombre de la liga'
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
                $row = User::find(Auth::user()->id)->league;

                $row->fill($request->all());

                if ($row->update()) {
                    return response()->json([
                        'type' => 'success',
                        'message' => 'Información de la liga guardada!'
                    ]);
                } 
            } else {
                //New entry
                $row = new Leagues($request->all());
                $row->user_id = Auth::user()->id;
                if ($row->save()) {
                    return response()->json([
                        'type' => 'success',
                        'message' => 'Información de la liga guardada!'
                    ]);
                }
            }
        }

        //send an error
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
        $league = User::find(Auth::user()->id)->league;
        return view("leagues.league_info", [ 'league' => $league]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Leagues  $leagues
     * @return \Illuminate\Http\Response
     */
    public function edit(Leagues $leagues)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Leagues  $leagues
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Leagues $leagues)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Leagues  $leagues
     * @return \Illuminate\Http\Response
     */
    public function destroy(Leagues $leagues)
    {
        //
    }
}
