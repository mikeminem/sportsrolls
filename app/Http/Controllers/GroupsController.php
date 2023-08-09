<?php

namespace App\Http\Controllers;

use App\Groups;
use App\Tournaments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use DataTables;
use DB;

class GroupsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
    //  * @return \Illuminate\Http\Response
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index( Request $request )
    {
        //
        if($request->ajax()){

            $rows = [];

            if ( $request->tournament ) {

                $rows = DB::table('groups as g')
                        ->join('tournaments as t', 't.id', '=', 'g.tournament_id')
                        ->select('g.id', 'g.name', 'g.notes',  't.id as tournament', 'g.is_deleted')
                        ->where('g.is_deleted', 0)
                        ->where('g.user_id',Auth::user()->id)
                        ->where('t.id', $request->tournament)
                        ->get();      
            }  
            
            return DataTables::of($rows)
                    ->addColumn('action', function($row) {

                        $routeEdit = route('groups.show', $row->id);
                        $routeDelete = route('groups.destroy', $row->id);

                        $links = '<a href="' . $routeEdit . '" data-toggle="tooltip" title="¡Editar Registro!" id="btnUpdate">
                                        <span class="fa fa-edit tool">
                                        </span>
                                    </a>
                                    <a href="' . $routeDelete . '" title="Borrar Registro" data-name="'.$row->name.'" id="btnDelete"> 
                                        <span class="fa fa-trash tool"></span>
                                    </a>';

                        return $links;
                    })
                    ->rawColumns([ 'action' ])
                    ->make(true);
        }

        //$sports = Sport::where('is_deleted', 0)->get();
        //return view ('categories.index', compact('sports'));
        $tournaments = Tournaments::where('is_deleted','0')->where('status','!=','Terminado')->where('user_id',Auth::user()->id)->get(['id','title']);

        return view ('groups.index', [
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
     * @return \Illuminate\Http\JsonResponse 
     * 
     */
    public function store(Request $request)
    {
        //
        $rules = [
            'name'              => 'required|max:60',
            'tournament_id'     => 'required'
        ];

        $attributes = [
            'name'          => 'Nombre',
            'tournament_id' => 'Torneo',
        ];

        //Validate the data
        $validated = Validator::make($request->all(),$rules, [] , $attributes);


        if ($validated->fails()) {
            return response()->json([
                'type' => 'error',
                'message' => 'Error al intentar guardar el grupo!',
                'errors' => $validated->errors()
            ]);
        }


        if (!isset($request->id) || empty($request->id)) {
            $model = new Groups($request->all());
            $model->user_id = Auth::user()->id;
            
            if ($model->save())
                 return response()->json(['type' => 'success', 
                                 'message' => 'Se ha creado la grupo!']);      
        } else {
            $model = Groups::find($request->id);
            $model->fill($request->all());

            
            if ($model->update()) 
                return response()->json(['type' => 'success', 
                                 'message' => 'Se ha modificado la grupo!']); 
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar guardar la grupo!'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int
     * @return bool
     */
    public function show(int $id)
    {
        //
        if ($id) {
            $model = Groups::select('id', 'notes', 'name',  'tournament_id')
                ->find($id);
            return $model;
        }
        return false;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Groups  $groups
     * @return \Illuminate\Http\Response
     */
    public function edit(Groups $groups)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Groups  $groups
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Groups $groups)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Groups  $groups
     * @return \Illuminate\Http\JsonResponse 
     */
    public function destroy( $id ) {
        //
        if (isset($id) && $id > 0) {
            Groups::where('id', $id)->update(['is_deleted' => 1 ]);

            return response()->json([
                'type' => 'success',
                'message' => 'La grupo se ha borrado!'
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar borrar la grupo!'
        ]);
    }

    public function getGroups(int $tournament_id) {
        if ($tournament_id) {
            $list = Groups::where('tournament_id',$tournament_id)->where('is_deleted',0)->get();
            return (string) $list;
        }

        return (string) false;
    }
}
