<?php

namespace App\Http\Controllers;

use App\Roster;
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

    public function index(Request $request)
    {
         if($request->ajax()){

            $rows = [];

            if ( $request->tournament ) {
                
                $rows = DB::table('rosters as r')
                    //->join('users as u', 'u.id', '=', 'r.player_id')
                    ->join('teams as t', 't.id', '=', 'r.team_id')
                    ->select(
                        'r.id', 
                        'r.number', 
                        'r.positions', 
                        'r.name', 
                        'r.last_name', 
                        'r.phone', 
                        't.name as team', 
                        'r.tournament_id as tournament', 
                        'r.status', 
                        'r.team_id',
                        'r.category_id',
                        'r.emergency_call',
                        'r.emergency_phone',
                        'r.dob',
                        'r.image'
                    )
                    ->where('r.is_deleted', 0)
                    //->where('r.user_id',Auth::user()->id)
                    ->where('r.tournament_id', $request->tournament)
                    ->get();    
                
            }     
            
            return DataTables::of($rows)
                    ->addColumn('image', function($row) {

                        $image = '<img class="img" src="'. asset('images/No-photo-m.png') . '" style="width:100px">';
                        if(!is_null($row->image) && !empty($row->image))
                             $image = '<img src="' . asset('players_photos/' . $row->image) . '" style="height:100px">';
                    
                        return $image;
                    })
                    ->addColumn('player', function($row) {

                        return  $row->last_name . ', ' . $row->name . ' <b>player #' . $row->number . '</b>  ';
                    })
                    ->addColumn('emergency_call', function($row) {
                        return  $row->emergency_call . '<br> ' . $row->emergency_phone;
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
                    ->rawColumns(['image', 'player', 'emergency_call', 'action'])
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
        $rules = [
            'name'          => 'required|max:45',
            'last_name'     => 'required|max:45',
            'tournament_id' => 'required',
            'category_id'   => 'required',
            'team_id'       => 'required',
            'number'        => 'required|max:10',
            'phone'         => 'required|max:10',
            'status'        => 'required'
        ];

        $attributes = [
            'name'          => 'Name',
            'tournament_id' => 'Tournament',
            'last_name'     => 'Last Name',
            'category_id'   => 'Category',
            'team_id'       => 'Team',
            'number'        => 'Number in T-shirt',
            'phone'         => 'Playuer phone number',
            'status'        => 'Player status'
        ];

        //Validate the data
        $validated = Validator::make($request->all(),$rules, [] , $attributes);

        if ($validated->fails()) {
            return response()->json([
                'type' => 'error',
                'message' => 'There is an error trying to save the player information!',
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
            $model          = new Roster($request->all());
            $model->user_id = Auth::user()->id;

            if (!empty($pathphoto)) {
                $model->image = $pathphoto;
            }
            // try {
            //     $model->save();
            // } catch (Exception $e) {
            //     echo $e->getMessage();
            //     die();
            // }
                
            if ($model->save())
                 return response()->json(['type' => 'success', 
                                 'message' => 'Player saved!']);      
        } else {
            //update
            $model = Roster::find($request->id);
            $lastphoto = $model->image;
            $model->fill($request->all());

            if (!empty($pathphoto)) {
                Storage::delete('public/players_photos/' . $lastphoto);
                $model->image = $pathphoto;
            }
            
            if ($model->update()) 
                return response()->json(['type' => 'success', 
                                 'message' => 'Player updated!']); 
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error trying to save player information!'
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
                //'player_id', 
                'number', 
                //'positions', 
                'status', 
                'category_id', 
                'is_deleted', 
                'tournament_id',
                'name', 
                'last_name',
                'phone',
                'emergency_call',
                'emergency_phone',
                'dob',
                'image'
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
                'message' => 'Player deleted!'
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'There is an error trying to delete the playeyr!'
        ]);
    }

}
