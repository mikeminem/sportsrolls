<?php

namespace App\Http\Controllers;

use App\Roster;
use App\User;
use Illuminate\Validation\Rule;
use App\Sport;
use App\Tournaments;


//use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use DataTables;
use DB;

class RosterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function players()
    {
        return response()->json(User::where('active', 1)->where('is_deleted', 0)
            ->orderBy('name')->orderBy('last_name')->get(['id', 'name', 'last_name']));
    }

    public function index(Request $request)
    {
         if($request->ajax()){

            $rows = [];

            if ( $request->tournament ) {

                $rows = DB::table('rosters as r')
                    ->leftjoin('teams as t', 't.id', '=', 'r.team_id')
                    ->select(
                        'r.id',
                        'r.number',
                        'r.name',
                        'r.last_name',
                        'r.photo',
                        't.name as team',
                        'r.tournament_id as tournament',
                        'r.team_id',
                        'r.category_id',
                        'r.verified',
                        'r.license'
                    )
                    ->where('r.is_deleted', 0)
                    //->where('r.user_id',Auth::user()->id)
                    ->where('r.tournament_id', $request->tournament)
                    ->get();

            }

            return DataTables::of($rows)
                    ->addColumn('photo', function($row) {

                        $image = '<img class="img" src="'. asset('images/No-photo-m.png') . '" style="width:100px">';
                        if(!is_null($row->photo) && !empty($row->photo))
                             $image = '<img src="' . asset('players_photos/' . $row->photo) . '" style="height:100px">';

                        return $image;
                    })
                    ->addColumn('player', function($row) {
                        return  $row->last_name . ', ' . $row->name . ' <b>player #' . $row->number . '</b>  ';
                    })
                    ->addColumn('action', function($row) {

                        $routeEdit = route('rosters.show', $row->id);
                        $routeDelete = route('rosters.destroy', $row->id);

                        $links = '<a href="' . $routeEdit . '" data-toggle="tooltip" title="¡Editar Registro!" id="btnUpdate">
                                        <span class="fa fa-edit tool">
                                        </span>
                                    </a>
                                    <a href="' . $routeDelete . '" title="Borrar Registro" data-name="'.$row->name.'" id="btnDelete">
                                        <span class="fa fa-trash tool"></span>
                                    </a>';

                        return $links;
                    })
                    ->rawColumns(['photo', 'player', 'emergency_call', 'action'])
                    ->make(true);
        }

        //$sports = Sport::where('is_deleted', 0)->get();
        //return view ('categories.index', compact('sports'));
        $tournaments = Tournaments::where('is_deleted','0')->where('status','!=','Terminado')->where('user_id',Auth::user()->id)->get(['id','title']);

        return view ('rosters.index', [
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
        $player = User::where('id', $request->player_id)->where('active', 1)
            ->where('is_deleted', 0)->first(['id', 'name', 'last_name']);
        // Always copy identity from the selected account, never from submitted text.
        $request->merge([
            'name' => $player ? $player->name : null,
            'last_name' => $player ? $player->last_name : null,
        ]);

        $rules = [
            'player_id' => ['required', 'integer', Rule::exists('users', 'id')
                ->where('active', 1)->where('is_deleted', 0)],
            'name'          => 'required|max:45',
            'last_name'     => 'required|max:45',
            'tournament_id' => 'required',
            'category_id'   => 'required',
            'team_id'       => 'required',
            'number'        => 'required|max:10',
            'verified'      => 'required'
        ];

        $attributes = [
            'player_id' => 'Jugador',
            'name'          => 'Nombre',
            'tournament_id' => 'Torneo',
            'last_name'     => 'Apellidos',
            'category_id'   => 'Categiría',
            'team_id'       => 'Equipo',
            'number'        => 'Número en playera',
            'verified'      => 'Verificado'
        ];

        //Validate the data
        $validated = Validator::make($request->all(),$rules, [] , $attributes);

        if ($validated->fails()) {
            return response()->json([
                'type' => 'error',
                'message' => 'Hubo un error al intentar guardar los datos del jugador!',
                'errors' => $validated->errors()
            ]);
        }

        $pathphoto = '';

        if($request->file('photo')) {
            $pathphoto =  date('YmdHis') . '.' .$request->file('photo')->extension();
            $request->file('photo')->storeAs('public/players_photos', $pathphoto);
        }

        if (!isset($request->id) || empty($request->id)) {
            //insert
            $model          = new Roster($request->except(['user_id', 'is_deleted']));

            if (isset(Auth::user()->id)) {
                $model->user_id = Auth::user()->id;
            }

            if (!empty($pathphoto)) {
                $model->photo = $pathphoto;
            }
            // try {
            //     $model->save();
            // } catch (Exception $e) {
            //     echo $e->getMessage();
            //     die();
            // }

            if ($model->save())
                 return response()->json(['type' => 'success',
                                 'message' => 'Datos del jugador guardados!']);
        } else {
            //update
            $model = Roster::find($request->id);
            $lastphoto = $model->photo;
            $model->fill($request->except(['user_id', 'is_deleted']));

            if (!empty($pathphoto)) {
                Storage::delete('public/players_photos/' . $lastphoto);
                $model->photo = $pathphoto;
            }

            if ($model->update())
                return response()->json(['type' => 'success',
                                 'message' => 'Datos del jugador actualizado!']);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar guardar la información del jugador!'
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
            $row = Roster::select(
                'id',
                'team_id',
                'user_id',
                'player_id',
                'number',
                'category_id',
                'is_deleted',
                'tournament_id',
                'name',
                'last_name',
                'photo',
                'number',
                'verified',
                'license'
            )->find($id);
            return $row;
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
            Roster::where('id', $id)->update(['is_deleted' => 1 ]);

            return response()->json([
                'type' => 'success',
                'message' => 'Datos del jugador borrados!'
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Hubo un error al intentar borrar al jugador!'
        ]);
    }

}
