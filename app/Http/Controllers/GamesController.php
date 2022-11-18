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
            
            $rows = DB::table('games as g')
                    ->select('g.id', 't1.name as team1', 'g.score_team1', 'g.score_team2',  't2.name as team2', 'c.name as category', 'g.status' , 'g.notes', 'g.score_team1', 'g.score_team2', 'g.game_day', 'g.journey_id', 'g.game_time', 'g.tournament_id', 'g.branch')
                    ->join('teams as t1', 't1.id', '=', 'g.team1_id')
                    ->join('teams as t2', 't2.id', '=', 'g.team2_id')
                    ->join('categories as c', 'c.id', '=', 'g.category_id')        
                    ->where('g.is_deleted', 0)
                    ->where('g.user_id',Auth::user()->id)
                    ->orderBy('g.id', 'desc')
                    ->get();        
            
            return DataTables::of($rows)
                    ->addColumn('date_time', function($row) {
                        $day = substr($row->game_day,8,2) . "/" . substr($row->game_day,5,2) . "/" . substr($row->game_day,0,4); 

                        return $row->game_time ." - ". $day;
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

                        

                        if ($row->status != "Finalizado") {
                            
                            $routeEdit = route('games.show', $row->id);
                            $routeDelete = route('games.destroy', $row->id);

                            $links = '<a href="' . $routeEdit . '" data-toggle="tooltip" title="¡Editar Registro!" id="btnUpdate">
                                        <span class="fa fa-edit tool">
                                        </span>
                                    </a> 
                                    <a href="javascript:void(0)" data-id="'.$row->id .'" data-team1="'.$row->team1.'" data-team2="'.$row->team2.'" title="Capturar resultados" id="btnScore"> 
                                        <span class="fa fa-refresh tool"></span>
                                    </a>
                                    <a href="' . $routeDelete . '" title="Borrar Registro"  id="btnDelete"> 
                                        <span class="fa fa-trash tool"></span>
                                    </a>';

                        return $links;

                        }

                        
                    })
                    ->rawColumns(['game_time','score', 'action'])
                    ->make(true);
        }

        $tournaments = Tournaments::where('is_deleted','0')->where('status','!=','Terminado')->where('user_id',Auth::user()->id)->get(['id','title']);
        $categories = Category::where('user_id',Auth::user()->id)->where('is_deleted',0)->get();


        return view ('games.index', compact('tournaments', 'categories'));
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
            Journeys::where('id', $id)->update(['is_deleted' => 1 ]);

            return response()->json([
                'type' => 'success',
                'message' => 'La jornada se ha borrado!'
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar borrar la jornada!'
        ]);
    }

    public function list ($tournament_id) {
        
        if ($tournament_id) {
            $list = Journeys::where('tournament_id',$tournament_id)->get();
            return (string) $list;
        }

        return (string) false;
    }

    public function updateScore(Request $request) {
        //update the score
        if ($request) {
            $model = Games::find($request->id);

            $model->status = "Cerrado";
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
        // (SELECT t.name as team, count('x') as JJ, 
        // SUM(CASE WHEN g.score_team1 > g.score_team2 THEN 1 ELSE 0 END)  as JG,
        // SUM(CASE WHEN g.score_team1 < g.score_team2 THEN 1 ELSE 0 END)  as JP,
        // sum(g.score_team1) as PAF, 
        // sum(g.score_team2) as PEC, 
        // sum(g.score_team1 - g.score_team2) as DIF
        // FROM teams t
        // inner join games as g on g.team1_id = t.id
        // where
        // t.branch = "Varonil" and
        // t.tournament_id = 6 and
        // t.category_id = 4
        // group by team)
        // UNION
        // (SELECT t.name as team, 
        // count('x') as JJ, 
        // SUM(CASE WHEN g.score_team2 > g.score_team1 THEN 1 ELSE 0 END) as JG,
        // SUM(CASE WHEN g.score_team2 < g.score_team1 THEN 1 ELSE 0 END) as JP,
        // sum(g.score_team2) as PAF, 
        // sum(g.score_team1) as PEC,  
        // sum(g.score_team2 - g.score_team1) as DIF
        // FROM teams t
        // inner join games as g on g.team2_id = t.id
        // where
        // t.branch = "Varonil" and
        // t.tournament_id = 6 and
        // t.category_id = 4
        // group by team)
        // order by JG desc, dif desc


        //OP2
        // with cte as (
        //     (SELECT distinct( t.name) as team,
        //            sum(1) as JJ,
        //            SUM(CASE WHEN g.score_team1 > g.score_team2 THEN 1 ELSE 0 END)  as JG,
        //            SUM(CASE WHEN g.score_team1 < g.score_team2 THEN 1 ELSE 0 END)  as JP,
        //            sum(g.score_team1) as PAF, 
        //            sum(g.score_team2) as PEC, 
        //            sum(g.score_team1 - g.score_team2) as DIF
        //            FROM games as g
        //            inner join teams as t on t.id = g.team1_id
        //            WHERE
        //            g.category_id = 4
        //            GROUP BY team)
        //            UNION
        //            (SELECT distinct( t.name) as team,
        //            sum(1) as JJ,
        //            SUM(CASE WHEN g.score_team1 < g.score_team2 THEN 1 ELSE 0 END)  as JG,
        //            SUM(CASE WHEN g.score_team1 > g.score_team2 THEN 1 ELSE 0 END)  as JP,
        //            sum(g.score_team2) as PAF, 
        //            sum(g.score_team1) as PEC, 
        //            sum(g.score_team2 - g.score_team1) as DIF
        //            FROM games as g
        //            inner join teams as t on t.id = g.team2_id
        //            WHERE
        //            g.category_id = 4
        //            GROUP BY team)
        //     )
        //     select cte.team, sum(JJ) as JJ, sum(JG) as JG, sum(JP) as JP, sum(PAF) as PAF, sum(PEC) as PEC, sum(DIF) as DIF 
        //     FROM cte 
        //     group by team
        //     order by JG desc, DIF desc

    }
}
