<?php

namespace App\Http\Controllers;

use App\Actions;
use App\Sport;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


use DataTables;
use DB;

class ActionsController extends Controller
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
    public function index(Request $request)
    {
        if($request->ajax()){

            $rows = [];
                $rows = DB::table('actions as a')                
                    ->select('a.id', 'a.name', 'a.value', 'a.scoring', 'a.label', 'a.is_deleted', 'a.sport_id')
                    ->where('a.is_deleted', 0)
                    ->get();    
            
            
            return DataTables::of($rows)
                   ->addColumn('name', function ($row) { 
                        return $row->name . ' (' . $row->label . ')';
                   })
                   ->addColumn('action', function($row) {

                        $routeEdit = route('actions.show', $row->id);
                        $routeDelete = route('actions.destroy', $row->id);

                        $links = '<a href="' . $routeEdit . '" data-toggle="tooltip" title="¡Editar Registro!" id="btnUpdate">
                                        <span class="fa fa-edit tool">
                                        </span>
                                    </a>
                                    <a href="' . $routeDelete . '" title="Borrar Registro" data-name="'.$row->name.'" id="btnDelete"> 
                                        <span class="fa fa-trash tool"></span>
                                    </a>';

                        return $links;
                    })
                    ->rawColumns(['image', 'action'])
                    ->make(true);
        }

        $sports = Sport::orderBy('name', 'asc')
            ->where('is_deleted',0)
            ->get();

        return view ('actions.index', [
            'sports'    => $sports
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
            'name'          => 'required|max:80',
            'label'         => 'required|max:10',
            'sport_id'      => 'required',
            'scoring'       => 'required',
            'value'         => 'required',

        ];

        $attributes = [
            'name'          => 'Nombre',
            'label'         => 'Etiqueta',
            'sport_id'      => 'Deporte',
            'scoring'       => 'Afecta el marcador',
            'value'         => 'Valor de la acción',
          
        ];

        //Validate the data
        $validated = Validator::make($request->all(),$rules, [] , $attributes);

        if ($validated->fails()) {
            return response()->json([
                'type' => 'error',
                'message' => 'Error al intentar guardar el registro!',
                'errors' => $validated->errors()
            ]);
        }

        if (!isset($request->id) || empty($request->id)) {
            $model = new Actions($request->all());
            
            if ($model->save())
                 return response()->json(['type' => 'success', 
                                 'message' => 'Se ha creado la acción del deporte!']);      
        } else {
            $model = Actions::find($request->id);
            $model->fill($request->all());

            if ($model->update()) 
                return response()->json(['type' => 'success', 
                                 'message' => 'Se ha modificado la acción del deporte!']); 
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar guardar la acción del deporte!'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Sponsor  $sponsor
     * @return \Illuminate\Http\Response|bool
     */
    public function show($id)
    {
        if ($id) {
            return  Actions::select('id', 'name', 'sport_id', 'label', 'scoring', 'value', 'is_deleted')->find($id);
        }
        return false;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sponsor  $sponsor
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (isset($id) && $id > 0) {
            Actions::where('id', $id)->update(['is_deleted' => 1 ]);

            return response()->json([
                'type' => 'success',
                'message' => 'La acción se ha borrado!'
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar borrar la acción!'
        ]);
    }
}
