<?php

namespace App\Http\Controllers;

use App\Profiles;
use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfilesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
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
            //'name'      => 'required|max:120|min:10',
            'dob'                   => 'required',
            'tshirt_size'           => 'required',
            'favorite_number'       => 'required',
            'footware_meansure'     => 'required',
            'sex'                   => 'required',
        ];

        $attributes = [
            'dob'                   => 'Fecha de nacimiento',
            'tshirt_size'           => 'Estatura',
            'favorite_number'       => "Peso",
            'footware_meansure'     => 'Medida de calzado',
            'sex'                   => 'Sexo',
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
            //$profile = Profiles::find(Auth::user()->id);
            $profile = User::find(Auth::user()->id)->profile;
            if ($profile) {
                //update info
                $model = User::find(Auth::user()->id)->profile;
                $model->fill($request->all());

                if ($model->update()) 
                    return response()->json([
                        'type' => 'success',
                        'message' => 'Información del perfil guardado!'
                    ]);
            } else {
                //New entry
                $model = new Profiles($request->all());
                $model->user_id = Auth::user()->id;
                if ($model->save()) 
                    return response()->json([
                        'type' => 'success',
                        'message' => 'Información del perfil guardado!'
                    ]);
            }
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar guardar la información del perfil!'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Profiles  $profiles
     * @return \Illuminate\Http\Response
     */
    public function show(Profiles $profiles)
    {
        //
        $profile = User::find(Auth::user()->id)->profile;
        return view("profiles.myprofile", [ 'profile' => $profile]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Profiles  $profiles
     * @return \Illuminate\Http\Response
     */
    public function edit(Profiles $profiles)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Profiles  $profiles
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profiles $profiles)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Profiles  $profiles
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profiles $profiles)
    {
        //
    }
}
