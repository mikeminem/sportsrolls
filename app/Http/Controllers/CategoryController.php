<?php

namespace App\Http\Controllers;

use App\Category;
use App\Sport;
use App\Tournaments;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use DataTables;
use DB;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['list']]);
    }

    public function index(Request $request)
    {
         if($request->ajax()){

            $rows = [];

            if ( $request->tournament ) {
                $rows = DB::table('categories as c')
                    ->join('sports as s', 's.id', '=', 'c.sport_id')
                    ->join('tournaments as t', 't.id', '=', 'c.tournament_id')
                    ->select('c.id', 'c.sport_id', 'c.name', 'c.overview', 's.name as nameSport', 'c.show_stats', 't.id as tournament')
                    ->where('c.is_deleted', 0)
                    ->where('c.user_id',Auth::user()->id)
                    ->where('t.id', $request->tournament)
                    ->get();
            }
            // $rows = DB::table('categories as c')
            //         ->join('sports as s', 's.id', '=', 'c.sport_id')
            //         ->join('tournaments as t', 't.id', '=', 'c.tournament_id')
            //         ->select('c.id', 'c.sport_id', 'c.name', 'c.overview', 's.name as nameSport', 'c.show_stats', 't.id as tournament')
            //         ->where('c.is_deleted', 0)
            //         ->where('c.user_id',Auth::user()->id)
            //         ->get();

            return DataTables::of($rows)
                    ->addColumn('sport_id', function($row) {

                        return $row->nameSport;
                    })->addColumn('action', function($row) {

                        $routeEdit = route('categories.show', $row->id);
                        $routeDelete = route('categories.destroy', $row->id);

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

        //$sports = Sport::where('is_deleted', 0)->get();
        //return view ('categories.index', compact('sports'));
        $tournaments = Tournaments::where('is_deleted','0')->where('status','!=','Terminado')->where('user_id',Auth::user()->id)->get(['id','title']);

        return view ('categories.index', [
            'tournaments' => $tournaments
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
            'name'   => 'required|max:120'
        ];

        $attributes = [
            'name'          => 'Nombre',
            'tournament_id' => 'Torneo',
            'show_stats'    => 'Mostrar tabla de posiciones'
        ];

        //Validate the data
        $validated = Validator::make($request->all(),$rules, [] , $attributes);


        if ($validated->fails()) {
            return response()->json([
                'type' => 'error',
                'message' => 'Error al intentar guardar la categoria!',
                'errors' => $validated->errors()
            ]);
        }


        if (!isset($request->id) || empty($request->id)) {
            $model = new Category($request->all());
            $model->user_id = Auth::user()->id;

            if ($model->save())
                 return response()->json(['type' => 'success',
                                 'message' => 'Se ha creado la categoria!']);
        } else {
            $model = Category::find($request->id);
            $model->fill($request->all());


            if ($model->update())
                return response()->json(['type' => 'success',
                                 'message' => 'Se ha modificado la categoria!']);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar guardar la categoria!'
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int
     * @return boolean
     */
    public function show( $id)
    {
        //
        if ($id) {
            $category = Category::select('id', 'sport_id', 'name', 'age_initial', 'age_final', 'overview', 'tournament_id')->find($id);
            return $category;
        }
        return false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        //
        if (isset($id) && $id > 0) {
            Category::where('id', $id)->update(['is_deleted' => 1 ]);

            return response()->json([
                'type' => 'success',
                'message' => 'La categoria se ha borrado!'
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar borrar la categoria!'
        ]);
    }

    public function list ($tournament_id) {

        if ($tournament_id) {
            $list = Category::where('tournament_id',$tournament_id)->where('is_deleted',0)->get();
            return (string) $list;
        }

        return (string) false;
    }
}
