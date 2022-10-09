<?php

namespace App\Http\Controllers;

use App\Sport;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use DataTables;
use DB;

class SportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
         if($request->ajax()){
            $rows = DB::table('sports as s')
                    ->select('s.id', 's.code', 's.name', 's.image', 's.is_deleted')
                    ->where('.is_deleted', 0)
                    ->get();        
            
            return DataTables::of($rows)
                    ->addColumn('image', function($row) {
                        $image = '';

                        if(!is_null($row->image) && !empty($row->image))
                            $image = '<img src="' . asset('storage/sports/' . $row->image) . '" style="height:100px">';

                        return $image;
                    })->addColumn('action', function($row) {

                        $routeEdit = route('sports.show', $row->id);
                        $routeDelete = route('sports.destroy', $row->id);

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

        return view ('sports.index');
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
            'name'   => 'required|max:120'
        ];

        $attributes = [
            'name'   => 'Nombre'
        ];

        //Validate the data
        $validated = Validator::make($request->all(),$rules, [] , $attributes);


        if ($validated->fails()) {
            return response()->json([
                'type' => 'error',
                'message' => 'Error al intentar guardar el Deporte!',
                'errors' => $validated->errors()
            ]);
        }


        $path = "";

        if($request->file('image')){
            $path =  date('YmdHis') . '.' .$request->file('image')->extension();

            $request->file('image')->storeAs('public/sports', $path);
        }


        if (!isset($request->id) || empty($request->id)) {
            $model = new Sport($request->all());
            $model->code =  strtoupper( str_random(6));

            if (!empty($path))
                $model->image = $path;

            if ($model->save())
                 return response()->json(['type' => 'success', 
                                 'message' => 'Se ha creado el deporte!']);      
        } else {
            $model = Sport::find($request->id);
            $lastImage = $model->image;
            $model->fill($request->all());

            if (!empty($path)) {
                Storage::delete('public/sports/' . $lastImage);
                $model->image = $path;
            }
            
            if ($model->update()) 
                return response()->json(['type' => 'success', 
                                 'message' => 'Se ha modificado el deporte!']); 
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar guardar el Deporte!'
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Sport  $sport
     * @return boolean
     */
    public function show( $id)
    {
        //
        if ($id) {
            $sport = Sport::select('id', 'code', 'name')->find($id);
        return $sport;
        }
        return false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sport  $sport
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        if (isset($id) && $id > 0) {
            Sport::where('id', $id)->update(['is_deleted' => 1 ]);

            return response()->json([
                'type' => 'success',
                'message' => 'El deporte se ha borrado!'
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar borrar el deporte!'
        ]);
    }
}
