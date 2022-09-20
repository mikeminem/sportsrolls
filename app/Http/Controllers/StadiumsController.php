<?php

namespace App\Http\Controllers;

use App\Stadiums;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use DB;
use DataTables;
use PDF;



class StadiumsController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //
        return view ('stadiums.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {

        $rules = [
            'name'      => 'required|max:120',
            'address'   => 'required|max:120',
            'phone'     => 'required|max:10|min:10',
            'manager'   => 'required|max:120'
        ];

        $attributes = [
            'name'      => 'Nombre',
            'manager'   => 'Encargado',
            'phone'     => 'Teléfono / Móvil',
            'address'   => 'Dirección'
        ];

        //example
        //$validator = Validator::make($request->all(), $rules, $messages, $attributes);

        //Validate the data
        $validated = Validator::make($request->all(),$rules, [] , $attributes);


        if ($validated->fails()) {
            return response()->json([
                'type' => 'error',
                'message' => 'Error al intentar guardar el Estadio!',
                'errors' => $validated->errors()
            ]);
        }


        if (strlen($request->id) ==0 ) {
            if ($this->store($request)) {
                 return response()->json(['type' => 'success', 
                                 'message' => 'Se ha creado el estadio estadio!']);      
            }
        } else {
            if ($this->update($request)) {
                return response()->json(['type' => 'success', 
                                 'message' => 'Se ha modificado el estadio!']); 
            }   
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar guardar el Estadio!'
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
        //
        $row = new Stadiums($request->all());
        $row->code =  strtoupper( str_random(6));
        $row->user_id = Auth::user()->id;
        if ($row->save()) {
            return true;
        }
        return false;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Stadiums  $stadiums
     * @return boolean
     */
    public function show( $id)
    {
        //
        if ($id) {
            $stadium = Stadiums::find($id);
        return $stadium;
        }
        return false;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return boolean
     */
    public function update(Request $request)
    {
        //
        if (!$request) {
            return false;
        }
        $row = Stadiums::find($request->id);
        $row->fill($request->all());

        if ($row->update()) {
            return true;
        }
        return false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Stadiums  $stadiums
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        if ($request) {
            Stadiums::where('id',$request->idDelete)->update(['is_deleted' => 1 ]);
            return response()->json([
                'type' => 'success',
                'message' => 'El estadio se ha borrado!'
            ]);
        }
        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar borrar el Estadio!'
        ]);
    }

    public function allStadiums() {
        
        if (Auth::user()->role == "Admin") {
            $rows = DB::table('stadiums as s')
                ->select('s.id', 's.code', 's.name', 's.address', 's.manager', 's.phone', 's.notes')
                //->leftjoin('taxes as t', 't.id', '=', 'p.tax_id')
                //->leftjoin('units as u', 'u.id', '=', 'p.unit')
                ->where('s.is_deleted',0)
                ->get();
        } else {
                $rows = DB::table('stadiums as s')
                ->select('s.id', 's.code', 's.name', 's.address', 's.manager', 's.phone', 's.notes')
                ->where('s.is_deleted',0)
                ->where('s.user_id', Auth::user()->id)
                ->get();        
        }
        
        return DataTables::of($rows)
                // ->addColumn('buy_price', function($rows){
                //     return "$".$rows->buy_price;
                // })
                
                ->addColumn('action', function($rows) {
                    $links = '';
                    if ( (Auth::user()->role == 'Admin' || Auth::user()->role == 'Promoter'))
                        {
                            $links .= '<a href="javascript:void(0);" data-toggle="tooltip" title="¡Editar Registro!" id="btnUpdate" data-id="'.$rows->id.'" ><span class="fa fa-edit tool"></a>
                                     <a href="javascript:void(0);" title="Borrar Registro" data-id="'. $rows->id.'" data-name="'.$rows->name.'" id="btnDelete"> <span class="fa fa-trash tool"></span></a>';
                        }

                    return $links;
                })
                ->rawColumns(['action'])
                ->make(true);
    }

    public function Print($id) {
        echo "Print";
    }
}
