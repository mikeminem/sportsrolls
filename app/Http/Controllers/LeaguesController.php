<?php

namespace App\Http\Controllers;

use App\Leagues;
use App\User;
use App\Sport;
use App\States;
use App\Municipalities;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


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
        $rules = [
            'name'              => 'required|max:120|min:10',
            'sport_id'          => 'required',
            'state_id'          => 'required', 
            'municipality_id'   => 'required',
            'summary'           => 'required',
            'slogan'            => 'required'
        ];

        $attributes = [
            'name'              => 'Nombre de la liga',
            'sport_id'          => 'Deporte',
            'state_id'          => 'Estado', 
            'municipality_id'   => 'Municipio',
            'summary'           => 'Acerca de',
            'slogan'            => 'Subtitulo'
        ];

        $validated = Validator::make($request->all(),$rules, [] , $attributes);

        if ($validated->fails()) {
            return response()->json([
                'type' => 'error',
                'message' => 'Error al intentar guardar la información de la liga!',
                'errors' => $validated->errors()
            ]);
        }

        $imagePath = '';
        if($request->file('logo1')){
            $imagePath =  date('Ymd-His') . '.' .$request->file('logo1')->extension();
            $request->file('logo1')->storeAs('public/league_logo', $imagePath);
        }

        if ($request) {
            $league = Leagues::find(Auth::user()->id);

            if ($league) {
                $lastImage = $league->logo1;
                $league->fill($request->all());

                if (!empty($imagePath)) {
                    Storage::delete('public/league_logo/' . $lastImage);
                    $league->logo1 = $imagePath;
                }
                
                if ($league->update()) {
                    return response()->json([
                        'type' => 'success',
                        'message' => 'Información de la liga ha sido actualizada!'
                    ]);
                }
            } else {
                $model = new Leagues($request->all());
                $model->user_id = Auth::user()->id;

                if (!empty($imagePath))
                    $model->logo1 = $imagePath;  
                
                if ($model->save()) {
                    return response()->json([
                        'type' => 'success',
                        'message' => 'Información de la liga guardada!'
                    ]);
                }
                    
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
        $league = User::find(Auth::user()->id)->league;
        $sports = Sport::where('is_deleted',0)->get(['id', 'name']);
        $states = States::where('is_deleted',0)->orderby('name','asc')->get(['id','name']);

        if ($league) {
            $municipalities = Municipalities::where('state_id',$league->state_id)->where('is_deleted',0)->orderby('name','asc')->get(['id','name']);
        } else {
            $league = new User();
            $municipalities = Municipalities::where('is_deleted',0)->orderby('name','asc')->get(['id','name']);
        }
        
        return view("leagues.league_info", [ 
            'league'            => $league,
            'sports'            => $sports,
            'states'            => $states,
            'municipalities'    => $municipalities
        ]);
    }

}
