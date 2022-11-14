<?php

namespace App\Http\Controllers;

use App\Teams;
use App\Tournaments;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use DataTables;
use DB;

class TeamsController extends Controller
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
            $rows = DB::table('teams as t')
                    ->join('categories as c', 'c.id', '=', 't.category_id')
                    ->select('t.id','t.logo', 't.name', 't.photo', 't.description', 't.branch','t.user_id','t.tournament_id','t.sport_id', 't.is_deleted', 't.clicks', 't.prints', 't.category_id', 'c.name as category', 't.contact', 't.contact_phone' )
                    ->where('t.is_deleted', 0)
                    ->where('t.user_id',Auth::user()->id)
                    ->orderBy('t.id', 'desc')
                    ->get();        
            
            return DataTables::of($rows)
                    ->addColumn('contact', function($row) {
                        return "<b>" . $row->contact . "</b><br>" . $row->contact_phone;
                    })
                    ->addColumn('branch', function($row) {
                        return $row->branch . " / " . $row->category;
                    })
                    ->addColumn('action', function($row) {

                        $routeEdit = route('teams.show', $row->id);
                        $routeDelete = route('teams.destroy', $row->id);

                            $links = '<a href="' . $routeEdit . '" data-toggle="tooltip" title="¡Editar Registro!" id="btnUpdate">
                                <span class="fa fa-edit tool">
                                </span>
                                </a><a href="' . $routeDelete . '" title="Borrar Registro" data-name="'.$row->name.'" id="btnDelete"> 
                                <span class="fa fa-trash tool"></span>
                            </a>';

                        return $links;
                    })
                    ->rawColumns([ 'action', 'contact' ])
                    ->make(true); 
        }

        $tournaments = Tournaments::where('is_deleted','0')->where('status','!=','Terminado')->where('user_id',Auth::user()->id)->get(['id','title']);

        return view ('teams.index', [
            'tournaments' => $tournaments
        ]);
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
            'name'              => 'required|max:120',
            'branch'            => 'required|max:240',
            'tournament_id'     => 'required',
            'sport_id'          => 'required',
            'category_id'       => 'required',
            'contact'           => 'required|max:80',
            'contact_phone'     => 'required|min:10',
        ];

        $attributes = [
            'name'          => 'Nombre',
            'branch'        => 'Rama',
            'tournament_id' => 'Torneo',
            'sport_id'      => 'Deporte',
            'category_id'   => 'Categoria',
            'contact'       => 'Mombre del Contacto',
            'contact_phone' => 'Teléfono / Móvil del Contacto',
        ];

        //Validate the data
        $validated = Validator::make($request->all(),$rules, [] , $attributes);


        if ($validated->fails()) {
            return response()->json([
                'type' => 'error',
                'message' => 'Error al intentar guardar el Equipo!',
                'errors' => $validated->errors()
            ]);
        }


        if (!isset($request->id) || empty($request->id)) {
            $model = new Teams($request->all());
            $model->user_id = Auth::user()->id;
            
            if ($model->save())
                 return response()->json(['type' => 'success', 
                                 'message' => 'Se ha creado el Equipo!']);      
        } else {
            $model = Teams::find($request->id);
            $lastImage = $model->image;
            $model->fill($request->all());

            if ($model->update()) 
                return response()->json(['type' => 'success', 
                                 'message' => 'Se ha modificado el Equipo!']); 
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar guardar el Equipo!'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Teams  $teams
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        if ($id) {
            $model = Teams::find($id);
            return $model;
        }
        return false;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Teams  $teams
     * @return \Illuminate\Http\Response
     */
    public function edit(Teams $teams)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Teams  $teams
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Teams $teams)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Teams  $teams
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        if (isset($id) && $id > 0) {
            Teams::where('id', $id)->update(['is_deleted' => 1 ]);

            return response()->json([
                'type' => 'success',
                'message' => 'El equipo se ha borrado!'
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar borrar el equipo!'
        ]);
    }

    public function list ($category_id) {
        
        if ($category_id) {
            $list = Teams::where('category_id',$category_id)->where('is_deleted',0)->get();
            return (string) $list;
        }

        return (string) false;
    }
}
