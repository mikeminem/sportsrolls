<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use DataTables;
use DB;
use App\Tournaments;
use App\Games;
use App\Category;
use App\Stadiums;

class GamesController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
         if($request->ajax()){
            // SELECT g.id, t1.name as team1, g.score_team1, g.score_team2,  t2.name as team2, c.name as category, g.status , g.notes
            // FROM games as g
            // inner join teams t1 on t1.id = g.team1_id
            // inner join teams t2 on t2.id = g.team2_id
            // inner join categories c on c.id = g.category_id
            // WHERE
            // g.user_id = 3 and
            // g.is_deleted = 0
            $rows = [];

            if ($request->tournament) {
            
                $rows = DB::table('games as g')
                    ->select(
                        'g.id', 
                        't1.name as team1', 
                        'g.score_team1', 
                        'g.score_team2',  't2.name as team2', 
                        'c.name as category', 
                        'g.status' , 'g.notes', 
                        'g.score_team1', 
                        'g.score_team2', 
                        'g.game_day', 
                        'g.journey_id', 
                        'g.game_time', 
                        'g.tournament_id', 
                        'g.branch', 
                        'g.type',
                        's.name as stadium'
                    )
                    ->join('teams as t1', 't1.id', '=', 'g.team1_id')
                    ->join('teams as t2', 't2.id', '=', 'g.team2_id')
                    ->join('categories as c', 'c.id', '=', 'g.category_id')  
                    ->leftJoin('stadiums as s', 's.id', '=', 'g.stadium_id')  
                    ->where('g.is_deleted', 0)
                    ->where('g.user_id',Auth::user()->id)
                    ->where('g.tournament_id', $request->tournament)
                    ->orderBy('g.id', 'desc')
                    ->get();     
            }   
            
            return DataTables::of($rows)
                    ->addColumn('date_time', function($row) {
                        $day = substr($row->game_day,8,2) . "/" . substr($row->game_day,5,2) . "/" . substr($row->game_day,0,4); 
                        return $day . '<br />' . $row->game_time ;
                    })
                    ->addColumn('stadium', function($row) {
                        return $row->stadium ?? '--';  
                    })
                    ->addColumn( 'status', function($row) {
                        $status = $row->status;
                        switch ($row->status) {
                            case 'Abierto':
                                $status = '<span class="badge badge-primary">Abierto</span>';
                                break;
                            case 'Finalizado':
                                $status = '<span class="badge badge-success">Finalizado</span>';
                                break; 
                            case 'Protestado':
                                $status = '<span class="badge badge-warning">Protestado</span>';
                                break;
                            case 'Cancelado':
                                $status = '<span class="badge badge-danger">Cancelado</span>';
                                break;   
                            case 'Suspendido':
                                $status = '<span class="badge badge-danger">Suspendido</span>';
                                break;   
                        }
                        return $status;
                    })
                    ->addColumn('score', function($row) {
                        $score1 = "";
                        $score2 = "";

                        if ($row->score_team1 > $row->score_team2) {
                            $score1 .= '<b class="win">'. $row->score_team1 . "</b>";
                            $score2 .= $row->score_team2;
                        } else if ($row->score_team2 > $row->score_team1) {
                            $score2 .= '<b class="win">'.  $row->score_team2 . "</b>";
                            $score1 .= $row->score_team1;

                        } else if ($row->score_team1 == $row->score_team2) {
                            $score1 .=  $row->score_team1;
                            $score2.=  $row->score_team2;
                        }
                        // return "<h5>".$score1 . " - " . $score2 . "</h5>"; 
                        return $score1 . " - " . $score2; 
                    })
                    ->addColumn('action', function($row) {

                        
                        $links = '';
                        if ($row->status != "Finalizado") {
                            
                            $routeEdit = route('games.show', $row->id);
                            $routeDelete = route('games.destroy', $row->id);

                            $links .= '<a href="' . $routeEdit . '" data-toggle="tooltip" title="¡Editar Registro!" id="btnUpdate">
                                        <span class="fa fa-edit tool">
                                        </span>
                                    </a> 
                                    <a href="' . $routeDelete . '" data-name="'. $row->id .'"  title="Borrar Registro"  id="btnDelete"> 
                                        <span class="fa fa-trash tool"></span>
                                    </a>';      
                        }
                                
                        $links .= ' <a href="javascript:void(0)" data-id="' . $row->id . '" data-team1="' . $row->team1 . '" data-team2="' . $row->team2 . '" title="Capturar resultados" id="btnScore"> 
                        <span class="fa fa-calculator tool"></span>
                        </a>';
                        
                        return $links;
                        
                    })
                    ->rawColumns(['date_time', 'status', 'game_time','score', 'action'])
                    ->make(true);
        }

        $tournaments = Tournaments::where('is_deleted','0')
            ->where('status','!=','Terminado')
            ->where('user_id',Auth::user()->id)
            ->get(['id','title']);

        $stadiums = Stadiums::where('is_deleted','0')
            ->where('user_id',Auth::user()->id)
            ->get(['id','name']);

        return view ('games.index', compact('tournaments', 'stadiums'));
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
            'tournament_id'     => 'required',
            'journey_id'        => 'required',
            'branch'            => 'required',
            'category_id'       => 'required',
            'game_day'          => 'required',
            'game_time'         => 'required',
            'team1_id'          => 'required',
            'team2_id'          => 'required',
        ];

        $attributes = [
            'tournament_id'     => 'Torneo',
            'journey_id'        => 'Jornada',
            'branch'            => 'Rama',
            'category_id'       => 'Categoria',
            'game_day'          => 'Día de juego',
            'game_time'         => 'Hora del juego',
            'team1_id'          => 'Equipo 1 (Visitante)',
            'team2_id'          => 'Equipo 2 (Casa)',
        ];

        //Validate the data
        $validated = Validator::make($request->all(),$rules, [] , $attributes);


        if ($validated->fails()) {
            return response()->json([
                'type' => 'error',
                'message' => 'Error al intentar guardar el juego!',
                'errors' => $validated->errors()
            ]);
        }

        if ($request->team1_id ==$request->team2_id ) {
            return response()->json([
                'type' => 'error',
                'message' => 'Error al intentar guardar el juego!',
                'errors' => "Error, no se puede programar un juego con el misto equipo como ribales"
            ]);
        }


        if (!isset($request->id) || empty($request->id)) {
            $model = new Games($request->all());
            $model->user_id = Auth::user()->id;
            
            if ($model->save())
                 return response()->json(['type' => 'success', 
                                 'message' => 'Se ha creado el Juego!']);      
        } else {
            $model = Games::find($request->id);
            $model->fill($request->all());

            
            if ($model->update()) 
                return response()->json(['type' => 'success', 
                                 'message' => 'Se ha modificado el juegoo!']); 
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar guardar el juego!'
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Journeys  $category
     * @return boolean
     */
    public function show( $id)
    {
        //
        if ($id) {
            // $rows = Games::select('id', 'name', 'notes', 'start_at', 'finish_at', 'tournament_id', 'sport_id', 'is_deleted', 'status')->find($id);
            $rows = Games::find($id);

            $t = $rows->tournament;
        
            return $rows;
        }
        return false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        if (isset($id) && $id > 0) {
            Games::where('id', $id)->update(['is_deleted' => 1 ]);

            return response()->json([
                'type' => 'success',
                'message' => 'El Juego se ha borrado!'
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar borrar el Juego!'
        ]);
    }

    public function list ($tournament_id) {
        
        if ($tournament_id) {
            $list = Games::where('tournament_id',$tournament_id)->get();
            return (string) $list;
        }

        return (string) false;
    }

    public function updateScore(Request $request) {
        //update the score
        if ($request) {
            $model = Games::find($request->id);

            $model->status = "Finalizado";
            $model->fill($request->all());
            if ($model->update()) 
                return response()->json(['type' => 'success', 
                                 'message' => 'Se ha modificado el juegoo!']); 
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar guardar el resultado del juego!'
        ]);
    }

    public function standings() {
        // SELECT  t.name as team,
        // count('x') as JJ,
        // count(case when PAF > PEC then 1 end) JG,
        // count(case when PAF < PEC then 1 end) JP,
        // sum(score.PAF) as PAF,
        // sum(score.PEC) as PEC,
        // sum(score.PAF) - sum(score.PEC) as DIF,
        // sum(
        //         case when PAF > PEC then 2 else 0 end 
        //         + case when PAF < PEC then 1 else 0 end
        //     ) as puntos 
        // FROM 
        // (
        //     (SELECT 
        //             h.team1_id team, 
        //             h.score_team1 PAF,
        //             h.score_team2 PEC
        //             FROM games h
        //             WHERE h.category_id=3
        //     )
        // UNION ALL
        //     ( SELECT 
        //             v.team2_id team,
        //             v.score_team2 PEF,
        //             v.score_team1 PEC
        //             FROM games v
        //             WHERE v.category_id=3
        //     )
        // ) as score
        // join teams t on t.id = score.team
        // group by t.name
        // order by JG desc, JP asc, DIF desc

    }
}
