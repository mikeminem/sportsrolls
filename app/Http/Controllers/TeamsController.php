<?php

namespace App\Http\Controllers;

use App\Groups;
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

            $rows = [];

            if ( $request->tournament ) {
                $rows = DB::table('teams as t')
                        ->join('categories as c', 'c.id', '=', 't.category_id')
                        ->leftjoin('groups as g', 'g.id', '=', 't.group_id')
                        ->select('t.id','t.logo', 't.name', 't.photo', 't.description', 't.branch','t.user_id','t.tournament_id','t.sport_id', 't.is_deleted', 
                        't.clicks', 't.prints', 't.category_id', 'c.name as category', 't.contact', 't.contact_phone', 't.status', 't.group_id', 'g.name as group', 't.photo' )
                        ->where('t.is_deleted', 0)
                        ->where('t.user_id',Auth::user()->id)
                        ->where('t.tournament_id', $request->tournament)
                        ->orderBy('t.id', 'desc')
                        ->get();
            }

            return DataTables::of($rows)
                    ->addColumn('contact', function ($row) {
                        return "<b>" . $row->contact . "</b><br>" . $row->contact_phone;
                    })
                    ->addColumn('group', function ($row) {
                        return ($row->group) ?? '-';
                    })
                    ->addColumn('name', function($row) {

                        $photo = '';

                        if(!is_null($row->photo) && !empty($row->photo))
                            $photo = '<img src="' . asset('team/' . $row->photo) . '" style="height:200px">';

                        return $row->name . '<br />'  . $photo;
                        
                    })
                    ->addColumn('status', function ($row) {
                        $combo = '<select id="cboStatus" class="form-control" data-id="'.$row->id.'">';

                        if ($row->status=='Activo') {
                            $combo .= '<option value="Activo" selected>Activo</opction>';
                        } else {
                            $combo .= '<option value="Activo">Activo</opction>';
                        }

                        if ($row->status=='Baja') {
                            $combo .= '<option value="Baja" selected>Baja</opction>';
                        } else {
                            $combo .= '<option value="Baja">Baja</opction>';
                        }

                        $combo .= '</select>';
                        return  $combo;
                    })
                    ->addColumn('action', function($row) {

                        $routeEdit = route('teams.show', $row->id);
                        $routeDelete = route('teams.destroy', $row->id);

                        //<a href="javascript:void(0)" data-toggle="tooltip" title="¡Agregar foto!" id="btnPicture" data-id="'.$row->id.'" data-name="'.$row->name.'">
                        //<span class="fa fa-image tool"></span> </a>

                            $links = '<a href="' . $routeEdit . '" data-toggle="tooltip" title="¡Editar Registro!" id="btnUpdate">
                                            <span class="fa fa-edit tool"></span>
                                        </a>
                                        <a href="' . $routeDelete . '" title="Borrar Registro" data-name="'.$row->name.'" id="btnDelete"> 
                                <span class="fa fa-trash tool"></span>
                            </a>';

                        return $links;
                    })
                    ->rawColumns([ 'action', 'contact', 'status', 'name' ])
                    ->make(true); 
        }

        $tournaments = Tournaments::where('is_deleted','0')->where('status','!=','Terminado')->where('user_id',Auth::user()->id)->get(['id','title']);

        return view ('teams.index', [
            'tournaments' => $tournaments
        ]);
    }



    public function store(Request $request)
    {
        //
        $rules = [
            'name'              => 'required|max:120',
            'branch'            => 'required|max:240',
            'tournament_id'     => 'required',
            'sport_id'          => 'required',
            'category_id'       => 'required',
            //'contact'           => 'required|max:80',
            //'contact_phone'     => 'required|min:10',
        ];

        $attributes = [
            'name'          => 'Nombre',
            'branch'        => 'Rama',
            'tournament_id' => 'Torneo',
            'sport_id'      => 'Deporte',
            'category_id'   => 'Categoria',
            //'contact'       => 'Mombre del Contacto',
            //'contact_phone' => 'Teléfono / Móvil del Contacto',
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

        $path = "";
        if($request->file('photo')){
            $path =  date('YmdHis') . '.' .$request->file('photo')->extension();
            $request->file('photo')->storeAs('public/teams', $path);
        }


        if (!isset($request->id) || empty($request->id)) {
            $model = new Teams($request->all());
            $model->user_id = Auth::user()->id;

            if (!empty($path))
                $model->photo = $path;
            
            if ($model->save())
                 return response()->json(['type' => 'success', 
                                 'message' => 'Se ha creado el Equipo!']);      
        } else {
            $model = Teams::find($request->id);
            $lastImage = $model->photo;
            $model->fill($request->all());

            if (!empty($path)) {
                Storage::delete('public/teams/' . $lastImage);
                $model->photo = $path;
            }

            if ($model->update()) 
                return response()->json(['type' => 'success', 
                                 'message' => 'Se ha modificado el Equipo!']); 
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar guardar el Equipo!'
        ]);
    }

    public function show($id) {
        if ($id) {
            $model = Teams::select('id',  'name', 'description', 'branch', 'user_id', 'tournament_id', 'sport_id', 'category_id', 'is_deleted', 'clicks', 'prints', 
            'contact', 'contact_phone', 'status', 'group_id')
                ->find($id);
            
                return $model;
        }
        return false;
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

    public function list($category_id) {
        
        if ($category_id) {
            $list = Teams::where('category_id',$category_id)
                ->where('status','Activo')
                ->where('is_deleted',0)
                ->orderby('name')->get();
            return (string) $list;
        }
        return (string) false;
    }

    /**
     * Get the list of Teams by branch id
     *
     * @param  int      $tournament
     * @param  string   $branch
     * @return string
     */
    public function listByBranch($tournament, $branch) {
        
        if ($branch) {
            $list = Teams::where('branch',$branch)
                ->where('tournament_id', $tournament)
                ->where('status','Activo')
                ->where('is_deleted',0)
                ->orderby('name')->get(['id','name']);
            return (string) $list;
        }
        return (string) false;
    }

    public function updateStatus( $id,  $status) {
        Teams::where('id', $id)->update(['status' => $status]);
        return response()->json(['results' => 'success', 'response' => ['¡El equipo se a modificado!']]);
    }
}
