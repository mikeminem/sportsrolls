<?php

namespace App\Http\Controllers;

use App\Profiles;
use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\States;
use App\Municipalities;

class ProfilesController extends Controller
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
            //'name'      => 'required|max:120|min:10',
            'dob'                   => 'required',
            'tshirt_size'           => 'required',
            'favorite_number'       => 'required',
            'footware_meansure'     => 'required',
            'sex'                   => 'required',
            'state_id'              => 'required',
            'municipality_id'       => 'required',
        ];

        $attributes = [
            'dob'                   => 'Fecha de nacimiento',
            'tshirt_size'           => 'Estatura',
            'favorite_number'       => "Peso",
            'footware_meansure'     => 'Medida de calzado',
            'sex'                   => 'Sexo',
            'state_id'              => 'Estado',
            'municipality_id'       => 'Municipio/delegación',
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

        $path = "";
        if($request->file('photo')){
            $path =  date('Ymd-His') . '.' .$request->file('photo')->extension();
            $request->file('photo')->storeAs('public/profiles', $path);
        }

        if ($request) {
            //$user_id = Auth::user()->id;
            //$profile = Profiles::find(Auth::user()->id);
            $profile = User::find(Auth::user()->id)->profile;
            if ($profile) {
                //update info
                $model = User::find(Auth::user()->id)->profile;
                $lastImage = $model->photo;
                $model->fill($request->all());

                if (!empty($path)) {
                    Storage::delete('public/sports/' . $lastImage);
                    $model->photo = $path;
                }

                if ($model->update()) 
                    return response()->json([
                        'type' => 'success',
                        'message' => 'Información del perfil guardado!'
                    ]);
            } else {
                //New entry
                $model = new Profiles($request->all());
                $model->user_id = Auth::user()->id;
                
                if (!empty($path))
                    $model->photo = $path;  

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
        $states = States::where('is_deleted',0)->orderby('name','asc')->get(['id','name']);


        if ($profile) {
            $municipalities = Municipalities::where('state_id',$profile->state_id)->where('is_deleted',0)->orderby('name','asc')->get(['id','name']);
        } else {
            $profile = new User();
            $municipalities = Municipalities::where('is_deleted',0)->orderby('name','asc')->get(['id','name']);

        } 
        

        return view("profiles.myprofile", [ 
            'profile'   => $profile,
            'states'    => $states,
            'municipalities' => $municipalities
        ]);
    }

   


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Profiles  $profiles
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        if ($request) {
            $profile = User::find(Auth::user()->id)->profile;

            $profile->photo = "";

            if ($profile->save()) {
                return response()->json([
                    'type' => 'success',
                    'message' => 'La foto ha sido borrada!'
                ]);
            }
            
        }
        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar borrar la foto de perfil!'
        ]);
    }

    
}
