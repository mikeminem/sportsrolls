<?php

namespace App\Http\Controllers;

use App\Sponsor;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


use DataTables;
use DB;

class PagesController extends Controller
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

            
                $rows = DB::table('sponsors as s')                
                    ->select('s.id', 's.name', 's.image', 's.reference', 's.show', 's.is_deleted')
                    ->where('s.is_deleted', 0)
                    //->where('s.user_id',Auth::user()->id)
                    ->get();    
            
            
            return DataTables::of($rows)
                   ->addColumn('show', function($row) {
                        return $row->show === 'Yes' ? 'Si': 'No';
                   })
                   ->addColumn('image', function($row) {
                        $imagePath = asset('sponsor/'. $row->image);
                            return '<img src='. $imagePath .' alt="'. $row->name .'" title="'. $row->name .'" width="200px">';
                        //}

                        //return '<no logo>';
                    })
                   ->addColumn('action', function($row) {

                        $routeEdit = route('sponsors.show', $row->id);
                        $routeDelete = route('sponsors.destroy', $row->id);

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

        
        return view ('pages.index');
        
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
            'show'   => 'required',
            // 'image'   => 'required',
        ];

        $attributes = [
            'name'       => 'Nombre',
            // 'image'      => 'Logo de la empresa',
            'show'       => 'Mostrar patrocinador'
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

        $imagePath = "";
        if($request->file('image')){
            $imagePath =  date('YmdHis') . '.' .$request->file('image')->extension();
            $request->file('image')->storeAs('public/sponsors', $imagePath);
        }


        if (!isset($request->id) || empty($request->id)) {
            $model = new Sponsor($request->all());
            $model->user_id = Auth::user()->id;

            if (!empty($imagePath))
                $model->image = $imagePath;
            
            if ($model->save())
                 return response()->json(['type' => 'success', 
                                 'message' => 'Se ha creado el patrocinador!']);      
        } else {
            $model = Sponsor::find($request->id);
            $lastImage = $model->image;
            $model->fill($request->all());

            if (!empty($imagePath)) {
                Storage::delete('public/sponsors/' . $lastImage);
                $model->image = $imagePath;
            }

            if ($model->update()) 
                return response()->json(['type' => 'success', 
                                 'message' => 'Se ha modificado el patrocinador!']); 
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar guardar la categoria!'
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
            return  Sponsor::select('id', 'user_id', 'name', 'reference', 'show', 'image')->find($id);
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
            Sponsor::where('id', $id)->update(['is_deleted' => 1 ]);

            return response()->json([
                'type' => 'success',
                'message' => 'El patronciador se ha borrado!'
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar borrar El patronciador!'
        ]);
    }
}
