<?php

namespace App\Http\Controllers;

use App\Tournaments;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use DataTables;
use DB;

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
                    // ->select('t.id', 't.code', 't.name', 't.image', 't.is_deleted')
                    ->select('t.*')
                    ->where('t.is_deleted', 0)
                    ->get();        
            
            return DataTables::of($rows)
                    // ->addColumn('image', function($row) {
                    //     $image = '';

                    //     if(!is_null($row->image) && !empty($row->image))
                    //         $image = '<img src="' . asset('storage/sports/' . $row->image) . '" style="height:100px">';

                    //     return $image;
                    // })
                    ->addColumn('action', function($row) {

                        $routeEdit = route('tournaments.show', $row->id);
                        $routeDelete = route('tournaments.destroy', $row->id);

                        $links = '<a href="' . $routeEdit . '" data-toggle="tooltip" title="¡Editar Registro!" id="btnUpdate">
                                        <span class="fa fa-edit tool">
                                        </span>
                                    </a>
                                    <a href="' . $routeDelete . '" title="Borrar Registro" data-name="'.$row->title.'" id="btnDelete"> 
                                        <span class="fa fa-trash tool"></span>
                                    </a>';

                        return $links;
                    })
                    ->rawColumns(['image', 'action'])
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
            'title'   => 'required|max:120'
        ];

        $attributes = [
            'title'   => 'Nombre del torneo'
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

            if (!empty($path))
                $model->image = $path;

            if ($model->save())
                 return response()->json(['type' => 'success', 
                                 'message' => 'Se ha creado el Torneo!']);      
        } else {
            $model = Tournaments::find($request->id);
            $lastImage = $model->image;
            $model->fill($request->all());

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
            $sport = Tournaments::select('id', 'code', 'title')->find($id);
        return $sport;
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
}
