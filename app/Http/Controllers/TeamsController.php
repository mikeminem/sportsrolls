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
                    ->select('t.id','t.logo', 't.name', 't.photo', 't.description', 't.branch','user_id','tournament_id','sport_id', 'is_deleted', 'clicks', 'prints')
                    ->where('t.is_deleted', 0)
                    ->where('t.user_id',Auth::user()->id)
                    ->orderBy('t.id', 'desc')
                    ->get();        
            
            return DataTables::of($rows)
                    ->addColumn('action', function($row) {

                        $routeEdit = route('teams.show', $row->id);
                        $routeDelete = route('teams.destroy', $row->id);

                            $links = '<a href="' . $routeEdit . '" data-toggle="tooltip" title="¡Editar Registro!" id="btnUpdate">
                                <span class="fa fa-edit tool">
                                </span>
                                </a><a href="' . $routeDelete . '" title="Borrar Registro" data-name="'.$row->title.'" id="btnDelete"> 
                                <span class="fa fa-trash tool"></span>
                            </a>
                            <a href="javascript:void(0)" title="Iniciar Torneo" data-id="'. $row->id.'"  data-name="'.$row->title.'" data-status="En Progreso" id="btnStatus"> 
                                        <span class="fa fa-play tool"></span>
                                    </a>';

                        return $links;
                    })
                    ->rawColumns([ 'action' ])
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Teams  $teams
     * @return \Illuminate\Http\Response
     */
    public function show(Teams $teams)
    {
        //
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
    public function destroy(Teams $teams)
    {
        //
    }
}
