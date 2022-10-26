<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use DataTables;
use DB;
use App\States;

class StatesController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
         if($request->ajax()){
            $rows = DB::table('states as s')
                    ->select('s.id', 's.short_name', 's.name', 's.is_deleted', 's.active' )
                    ->where('s.is_deleted', 0)
                    ->get();        
            
            return DataTables::of($rows)
                    ->addColumn('action', function($row) {

                        $routeEdit = route('states.show', $row->id);
                        $routeDelete = route('states.destroy', $row->id);

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

        return view ('states.index');
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
            'name'   => 'required|max:120',
            'short_name'   => 'required|max:20'
        ];

        $attributes = [
            'name'          => 'Nombre',
            'short_name'    => 'Abreviación'
        ];

        //Validate the data
        $validated = Validator::make($request->all(),$rules, [] , $attributes);


        if ($validated->fails()) {
            return response()->json([
                'type' => 'error',
                'message' => 'Error al intentar guardar el Estado!',
                'errors' => $validated->errors()
            ]);
        }


        if (!isset($request->id) || empty($request->id)) {
            $model = new States($request->all());
            
            if ($model->save())
                 return response()->json(['type' => 'success', 
                                 'message' => 'Se ha creado el Estado!']);      
        } else {
            $model = States::find($request->id);
            $model->fill($request->all());

            
            if ($model->update()) 
                return response()->json(['type' => 'success', 
                                 'message' => 'Se ha modificado el Estado!']); 
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar guardar el Estado!'
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\States  $states
     * @return boolean
     */
    public function show( $id)
    {
        //
        if ($id) {
            $model = States::select('id', 'short_name', 'name', 'active' )->find($id);
            
            return $model;
        }
        return false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\States  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        if (isset($id) && $id > 0) {
            States::where('id', $id)->update(['is_deleted' => 1 ]);

            return response()->json([
                'type' => 'success',
                'message' => 'El Estado se ha borrado!'
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar borrar el Estado!'
        ]);
    }
}
