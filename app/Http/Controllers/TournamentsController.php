<?php

namespace App\Http\Controllers;

use App\Tournaments;
use App\Teams;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use DataTables;
use DB;
use App\Sports;

class TournamentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if($request->ajax()){
            //send the grid rows
            $rows = DB::table('tournaments as t')
                    ->select('t.id','t.code', 't.title', 't.is_deleted', 't.status', 't.contacts', 't.prints', 't.clicks')
                    ->where('t.is_deleted', 0)
                    ->where('t.user_id',Auth::user()->id)
                    ->orderBy('t.id', 'desc')
                    ->get();        
            
            return DataTables::of($rows)
                    ->addColumn('code', function($row) {
                        return  '<b>' . $row->code . '</b>';
                    })
                    ->addColumn('title', function($row) {
                        return '<h4>' . $row->title . '</h4> Equipos inscritos: <br> Impresiones: '.$row->prints.' - Clicks: '.$row->clicks;
                    })
                    ->addColumn('status', function($row) {
                        switch ($row->status) {
                            case 'Por Empezar':
                                return '<span class="badge badge-info">'. $row->status.' <br> Inscripciones abiertas</span>';
                            case 'En Progreso':
                                return '<span class="badge badge-warning">'. $row->status.'</span>';
                            case 'Terminado':
                                return '<span class="badge badge-danger">'. $row->status.'</span>';
                        }
                        
                    })
                    ->addColumn('action', function($row) {

                        $routeEdit = route('tournaments.show', $row->id);
                        $routeDelete = route('tournaments.destroy', $row->id);

                        $links = '';

                        if ($row->status == 'Por Empezar') {
                            $links .= '<a href="' . $routeEdit . '" data-toggle="tooltip" title="¡Editar Registro!" id="btnUpdate">
                                <span class="fa fa-edit tool">
                                </span>
                                </a><a href="' . $routeDelete . '" title="Borrar Registro" data-name="'.$row->title.'" id="btnDelete"> 
                                <span class="fa fa-trash tool"></span>
                            </a>
                            <a href="javascript:void(0)" title="Iniciar Torneo" data-id="'. $row->id.'"  data-name="'.$row->title.'" data-status="En Progreso" id="btnStatus"> 
                                        <span class="fa fa-play tool"></span>
                                    </a>';
                        }

                        if ($row->status == 'En Progreso') {
                            $links .= '<a href="javascript:void(0)" title="Terminar y archivar Torneo" data-id="'. $row->id.'"  data-name="'.$row->title.'" data-status="Terminado" id="btnStatus"> 
                                        <span class="fa fa-stop tool"></span>
                                    </a>';
                        }

                        $links .= '<a href="javascript:void(0)" title="Ver Estadisticas" data-id="'. $row->id.'"  data-name="'.$row->title.'" id="btnStats"> 
                                <span class="fa fa-bar-chart tool"></span>
                            </a>';

                        return $links;
                    })
                    ->rawColumns(['title','status', 'action', 'code'])
                    ->make(true); 
        }

        return view ('tournaments.index');
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
            'title'     => 'required|max:120',
            'summary'   => 'required|max:240',
            'contacts'   => 'required|max:240',
        ];

        $attributes = [
            'title'     => 'Nombre ',
            'summary'   => 'Descripción',
            'contacts'  => 'Contactos',
        ];

        //Validate the data
        $validated = Validator::make($request->all(),$rules, [] , $attributes);


        if ($validated->fails()) {
            return response()->json([
                'type' => 'error',
                'message' => 'Error al intentar guardar el Torneo!',
                'errors' => $validated->errors()
            ]);
        }


        $path = "";

        if($request->file('image')){
            $path =  date('YmdHis') . '.' .$request->file('image')->extension();

            $request->file('image')->storeAs('public/sports', $path);
        }


        if (!isset($request->id) || empty($request->id)) {
            $model = new Tournaments($request->all());
            $model->code =  strtoupper( str_random(8));
            $model->user_id = Auth::user()->id;
            $model->start_day = date('Y-m-d');
            $model->slug = str_slug($model->name);

            if (!empty($path))
                $model->image = $path;

            if ($model->save())
                 return response()->json(['type' => 'success', 
                                 'message' => 'Se ha creado el Torneo!']);      
        } else {
            $model = Tournaments::find($request->id);

            $lastImage = $model->image;
            $model->fill($request->all());
            $model->slug = str_slug($model->name);


            if (!empty($path)) {
                Storage::delete('public/sports/' . $lastImage);
                $model->image = $path;
            }
            
            if ($model->update()) 
                return response()->json(['type' => 'success', 
                                 'message' => 'Se ha modificado el Torneo!']); 
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar guardar el Torneo!'
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tournaments  $tournaments
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        if ($id) {
            $model = Tournaments::select('id', 'code', 'title', 'summary', 'contacts', 'status','sport_id')->find($id);

            if (!$model->code) {
                $model->code =  strtoupper( str_random(8));
            }

            if ( !$model->sport_id ) {
                $model->sport_id = Auth::user()->league->sport_id;
            }
            $sport = Sports::select('name')->find($model->sport_id);
            $model->sport = $sport->name;
            
        return $model;
        }
        return false;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tournaments  $tournaments
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id )
    {
        //
        if (isset($id) && $id > 0) {
            Tournaments::where('id', $id)->update(['is_deleted' => 1 ]);

            return response()->json([
                'type' => 'success',
                'message' => 'El Torneo se ha borrado!'
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar borrar el Torneo!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tournaments  $tournaments
     * @return \Illuminate\Http\Response
     */
    public function start( Request $request )
    {
        //
        if (isset($request)) {
            Tournaments::where('id', $request->idStart)->update(['status' => $request->status ]);

            return response()->json([
                'type' => 'success',
                'message' => 'El Torneo se ha inciado!'
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar borrar el Torneo!'
        ]);
    }

    public function showTournament ($slug){
        if (!$slug) {
            //lets send this to the tournaments list
        }

        //lets get the tournament info
        $tournament = Tournaments::where('slug', $slug)->get();

        $clicks = $tournament[0]->clicks + 1;

        Tournaments::where('id', $tournament[0]->id)->update(['clicks' => $clicks ]);

        return view ('tournaments.show', [
            'tournament' => $tournament[0]
        ]);



    }
}
