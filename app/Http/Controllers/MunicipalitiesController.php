<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use DataTables;
use DB;
use App\States;
use App\Municipalities;

class MunicipalitiesController extends Controller
{
    //
     //
     public function __construct()
     {
         $this->middleware('auth');
     }

     public function index(Request $request)
    {
         if($request->ajax()){
            $rows = DB::table('municipalities as m')
                    ->select('m.id', 'm.short_name', 'm.name', 'm.is_deleted', 'm.active', 'm.state_id' )
                    ->where('m.is_deleted', 0)
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

        $states = States::where('is_deleted',0)->orderby('name','asc')->get(['id','name']);

        return view ('municipalities.index', compact('states'));
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
            'name'      => 'required|max:120',
            'state_id'  => 'required'
        ];

        $attributes = [
            'name'          => 'Nombre',
            'state_id'      => 'Estado'
        ];

        //Validate the data
        $validated = Validator::make($request->all(),$rules, [] , $attributes);


        if ($validated->fails()) {
            return response()->json([
                'type' => 'error',
                'message' => 'Error al intentar guardar el Municipio/Delegación!',
                'errors' => $validated->errors()
            ]);
        }


        if (!isset($request->id) || empty($request->id)) {
            $model = new Municipalities($request->all());
            
            if ($model->save())
                 return response()->json(['type' => 'success', 
                                 'message' => 'Se ha creado el Municipio/Delegación!']);      
        } else {
            $model = Municipalities::find($request->id);
            $model->fill($request->all());

            
            if ($model->update()) 
                return response()->json(['type' => 'success', 
                                 'message' => 'Se ha modificado el Municipio/Delegación!']); 
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar guardar el Municipio/Delegación!'
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
            $model = Municipalities::select('id', 'short_name', 'name', 'active', 'state_id' )->find($id);
            
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
            Municipalities::where('id', $id)->update(['is_deleted' => 1 ]);

            return response()->json([
                'type' => 'success',
                'message' => 'El Municipio/Delegación se ha borrado!'
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar borrar el Municipio/Delegación!'
        ]);
    }

    public function list ($state_id) {
        
        if ($state_id) {
            $list = Municipalities::where('state_id',$state_id)->get();
            return (string) $list;
        }

        return (string) false;
    }
}
