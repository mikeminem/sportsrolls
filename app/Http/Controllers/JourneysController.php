<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use DataTables;
use DB;

use Illuminate\Http\Request;
use App\Tournaments;
use App\Journeys;

class JourneysController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
         if($request->ajax()){
            $rows = DB::table('journeys as j')
                    ->select('j.id', 'j.name', 'j.start_at', 'j.finish_at', 'j.tournament_id', 'j.sport_id', 'j.is_deleted', 'j.status')
                    ->where('j.is_deleted', 0)
                    ->where('j.user_id',Auth::user()->id)
                    ->orderBy('j.id', 'desc')
                    ->get();        
            
            return DataTables::of($rows)
                    ->addColumn('start_at', function($row) {
                        return substr($row->start_at,8,2) . "/" . substr($row->start_at,5,2) . "/" . substr($row->start_at,0,4); 
                    })
                    ->addColumn('finish_at', function($row) {
                        return substr($row->finish_at,8,2) . "/" . substr($row->finish_at,5,2) . "/" . substr($row->finish_at,0,4); 
                    })
                    ->addColumn('action', function($row) {

                        $routeEdit = route('journeys.show', $row->id);
                        $routeDelete = route('journeys.destroy', $row->id);

                        $links = '<a href="' . $routeEdit . '" data-toggle="tooltip" title="¡Editar Registro!" id="btnUpdate">
                                        <span class="fa fa-edit tool">
                                        </span>
                                    </a>
                                    <a href="' . $routeDelete . '" title="Borrar Registro" data-name="'.$row->name.'" id="btnDelete"> 
                                        <span class="fa fa-trash tool"></span>
                                    </a>';

                        return $links;
                    })
                    ->rawColumns(['sport_id', 'action'])
                    ->make(true);
        }

        $tournaments = Tournaments::where('is_deleted','0')->where('status','!=','Terminado')->where('user_id',Auth::user()->id)->get(['id','title']);

        return view ('journeys.index', compact('tournaments'));
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
            'name'          => 'required|max:120',
            'start_at'      => 'required',
            'finish_at'     => 'required',
        ];

        $attributes = [
            'name'          => 'Nombre',
            'start_at'      => 'Fecha Inicio',
            'finish_at'     => 'Fecha Cierre',
        ];

        //Validate the data
        $validated = Validator::make($request->all(),$rules, [] , $attributes);


        if ($validated->fails()) {
            return response()->json([
                'type' => 'error',
                'message' => 'Error al intentar guardar la jornada de juego!',
                'errors' => $validated->errors()
            ]);
        }


        if (!isset($request->id) || empty($request->id)) {
            $model = new Journeys($request->all());
            $model->user_id = Auth::user()->id;
            $model->status ="Abierto";
            
            if ($model->save())
                //set the new toruament to is current and the oldest to not current
                Journeys::where('user_id', Auth::user()->id)
                    ->where('id',"<>", $model->id)
                    ->where('is_deleted',0)
                    ->where('tournament_id',$model->tournament_id)
                    ->where('status','Abierto')->update(['status' => "Cerrado" ]);

                 return response()->json(['type' => 'success', 
                                 'message' => 'Se ha creado la jornada!']);      
        } else {
            $model = Journeys::find($request->id);
            $model->fill($request->all());

            
            if ($model->update()) 
                return response()->json(['type' => 'success', 
                                 'message' => 'Se ha modificado la jornada!']); 
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar guardar la jornada!'
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Journeys  $category
     * @return boolean
     */
    public function show( $id)
    {
        //
        if ($id) {
            $category = Journeys::select('id', 'name', 'notes', 'start_at', 'finish_at', 'tournament_id', 'sport_id', 'is_deleted', 'status')->find($id);
            
            return $category;
        }
        return false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        if (isset($id) && $id > 0) {
            Journeys::where('id', $id)->update(['is_deleted' => 1 ]);

            return response()->json([
                'type' => 'success',
                'message' => 'La jornada se ha borrado!'
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar borrar la jornada!'
        ]);
    }

    public function list ($tournament_id) {
        
        if ($tournament_id) {
            $list = Journeys::where('tournament_id',$tournament_id)->where('is_deleted',0)->orderby('id','desc')->get();
            return (string) $list;
        }

        return (string) false;
    }
}
