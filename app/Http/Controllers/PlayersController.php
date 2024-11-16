<?php

namespace App\Http\Controllers;

use App\Players;
use App\Sport;
use App\Tournaments;


//use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use DataTables;
use DB;

class PlayersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
         if($request->ajax()){

            $rows = [];

            
                
            $rows = DB::table('players as p')
                ->select(
                    'p.id', 
                    'p.names', 
                    'p.last_name1', 
                    'p.last_name2', 
                    'p.phone', 
                    'p.status', 
                    'p.emergency_name',
                    'p.emergency_phone',
                    'p.dob',
                    'p.photo'
                )
                ->where('p.is_deleted', 0)
                //->where('p.user_id',Auth::user()->id)
                ->get();    
                
            
            
            return DataTables::of($rows)
                    ->addColumn('photo', function($row) {

                        $image = '<img class="img" src="'. asset('images/No-photo-m.png') . '" style="width:100px">';
                        if(!is_null($row->photo) && !empty($row->photo))
                             $image = '<img src="' . asset('players_photos/' . $row->photo) . '" style="height:100px">';
                    
                        return $image;
                    })
                    ->addColumn('name', function($row) {

                        return  $row->last_name1 . ', ' . $row->names;
                    })
                    ->addColumn('emergency_name', function($row) {
                        return  $row->emergency_name . '<br> ' . $row->emergency_phone;
                    })
                    
                    ->addColumn('action', function($row) {

                        $routeEdit = route('players.show', $row->id);
                        $routeDelete = route('players.destroy', $row->id);

                        $links = '<a href="' . $routeEdit . '" data-toggle="tooltip" title="¡Editar Registro!" id="btnUpdate">
                                        <span class="fa fa-edit tool">
                                        </span>
                                    </a>
                                    <a href="' . $routeDelete . '" title="Borrar Registro" data-name="'.$row->names.'" id="btnDelete"> 
                                        <span class="fa fa-trash tool"></span>
                                    </a>';

                        return $links;
                    })
                    ->rawColumns(['photo', 'emergency_name', 'name', 'action'])
                    ->make(true);
        }

        //$sports = Sport::where('is_deleted', 0)->get();
        //return view ('categories.index', compact('sports'));
        //$tournaments = Tournaments::where('is_deleted','0')->where('status','!=','Terminado')->where('user_id',Auth::user()->id)->get(['id','title']);

        return view ('players.index', [
            // 'tournaments' => $tournaments
        ]);
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
            'names'         => 'required|max:45',
            'last_name1'    => 'required|max:45',
            //'last_name2'    => 'required|max:45',
            'email'         => 'required',
            'phone'         => 'required|max:10',
            'dob'           => 'required',
            'access_pin'    => 'required|max:10',
            'gender'        => 'required'
        ];

        $attributes = [
            'names'         => 'Nombres',
            'last_name1'    => 'Apellido Paterno',
            'last_name2'    => 'Apellido Materno',
            'email'         => 'required',
            'phone'         => 'Teléfono',
            'dob'           => 'Día de Nacimiento',
            'access_pin'    => 'Pin de Acceso',
            'gender'        => 'Genero'
        ];

        //Validate the data
        $validated = Validator::make($request->all(),$rules, [] , $attributes);

        if ($validated->fails()) {
            return response()->json([
                'type' => 'error',
                'message' => 'There is an error trying to save the player information!',
                'errors' => $validated->errors()
            ]);
        }

        $pathphoto = '';
        if($request->file('photo')) {
            $pathphoto =  date('YmdHis') . '.' .$request->file('photo')->extension();
            $request->file('photo')->storeAs('public/players_photos', $pathphoto);
        }

        if (!isset($request->id) || empty($request->id)) {
            //insert
            $model          = new Players($request->all());
            //$model->user_id = Auth::user()->id; <--- is not needed, the players is global

            if (!empty($pathphoto)) {
                $model->image = $pathphoto;
            }
            // try {
            //     $model->save();
            // } catch (Exception $e) {
            //     echo $e->getMessage();
            //     die();
            // }
                
            if ($model->save())
                 return response()->json(['type' => 'success', 
                                 'message' => 'Player saved!']);      
        } else {
            //update
            $model = Players::find($request->id);
            $lastphoto = $model->image;
            $model->fill($request->all());

            if (!empty($pathphoto)) {
                Storage::delete('public/players_photos/' . $lastphoto);
                $model->image = $pathphoto;
            }
            
            if ($model->update()) 
                return response()->json(['type' => 'success', 
                                 'message' => 'Player updated!']); 
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error trying to save player information!'
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int 
     * @return boolean
     */
    public function show( $id)
    {
        //
        if ($id) {
            $row = Players::select(
                'id',
                'curp',
                'status', 
                'is_deleted', 
                'names', 
                'last_name1',
                'last_name2',
                'phone',
                'emergency_name',
                'emergency_phone',
                'dob',
                'photo',
                'state_id',
                'municipality_id',
                'email',
                'gender',
                'blod_type'
            )->find($id);
            return $row;
        }
        return false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\JsonResponse 
     */
    public function destroy($id)
    {
        //
        if (
                isset($id) 
                && $id !== 0
        ) {
            Players::where('id', $id)->update(['is_deleted' => 1 ]);

            return response()->json([
                'type' => 'success',
                'message' => 'Jugador borrado!'
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Hubo un error al intentar borrar el jugador!'
        ]);
    }

}
