<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use DataTables;
use DB;
use App\Tournaments;
use App\Games;
use App\Journeys;
use App\Stadiums;
use Illuminate\Support\Facades\View;

class GamesController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['showGame', 'data_entry', 'getJourneyGames']]);
    }

    public function index(Request $request)
    {
         if($request->ajax()) {
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
                        'g.score_team2',
                        't2.name as team2',
                        'c.name as category',
                        'g.status' ,
                        'g.notes',
                        'g.team1_id',
                        'g.team2_id',
                        'g.score_team1',
                        'g.score_team2',
                        'g.game_day',
                        'g.journey_id',
                        'g.game_time',
                        'g.tournament_id',
                        'g.category_id',
                        'g.branch',
                        'g.type',
                        's.name as stadium',
                        'g.score_penalties_team1',
                        'g.score_penalties_team2',
                        't.enable_penalties_scoring',
                        'g.access_code',
                        'g.period'
                    )
                    ->join('teams as t1', 't1.id', '=', 'g.team1_id')
                    ->join('teams as t2', 't2.id', '=', 'g.team2_id')
                    ->leftJoin('categories as c', 'c.id', '=', 'g.category_id')
                    ->leftjoin('groups as gr', 'gr.id', '=', 'g.group_id')
                    ->join('tournaments as t', 't.id', '=', 'g.tournament_id')
                    ->leftJoin('stadiums as s', 's.id', '=', 'g.stadium_id')
                    ->where('g.is_deleted', 0)
                    //->where('gr.is_deleted', 0)
                    ->where('g.user_id',Auth::user()->id)
                    ->where('g.tournament_id', $request->tournament)
                    ->orderBy('g.id', 'desc')
                    ->get();
            }

            return DataTables::of($rows)
                    ->addColumn('access_code', function($row) {
                        return '<b>' . $row->access_code . '</br>';
                    })
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
                        $score1 = '';
                        $score2 = '';
                        $penalScore1 = '';
                        $penalScore2 = '';
                        $result = '';
                        $direction = ' - ';

                        if ($row->score_team1 > $row->score_team2) {
                            $score1 .= '<b class="win">'. $row->score_team1 . "</b>";
                            $score2 .= $row->score_team2;
                            $direction = '<i class="fa fa-arrow-left"></i>';
                        } elseif ($row->score_team2 > $row->score_team1) {
                            $score2 .= '<b class="win">'.  $row->score_team2 . "</b>";
                            $score1 .= $row->score_team1;
                            $direction = '<i class="fa fa-arrow-right"></i>';

                        } elseif ($row->score_team1 == $row->score_team2) {
                            $score1 .=  $row->score_team1;
                            $score2 .=  $row->score_team2;

                            //here is where the penalties score goes
                            if ( $row->enable_penalties_scoring === 'Si') {
                                $penalScore1 = $row->score_penalties_team1;
                                $penalScore2 = $row->score_penalties_team2;

                                //definition of the direction
                                if ($penalScore1 > $penalScore2) {
                                    $direction = '<i class="fa fa-arrow-left"></i>';
                                } elseif ($penalScore2 > $penalScore1) {
                                    $direction = '<i class="fa fa-arrow-right"></i>';
                                }

                            }

                        }
                        if ( $row->enable_penalties_scoring === 'No') {
                            $direction = ' - ';
                        }

                        $result = '<h5>'.$score1 . ' ' . $direction .  ' ' . $score2 . '</h5>';

                        if ( $row->enable_penalties_scoring === 'Si') {
                            if ( (strlen($penalScore1)>0)
                                || (strlen($penalScore2)>0) ) {
                                    $result .= '<i>(' . $penalScore1 .' - '. $penalScore2 .')</i>';

                            }
                        }

                        return $result;
                    })
                    ->addColumn('action', function($row) {

                        $links          = '';
                        $routeEdit      = route('games.show', $row->id);
                        //$routeHistory   = route('game_history', $row->code);
                        $routeDelete    = route('games.destroy', $row->id);

                        //if ($row->status != "Finalizado") {

                            $links .= '<a href="' . $routeEdit . '" data-toggle="tooltip" title="¡Editar Registro!" id="btnUpdate">
                                        <span class="fa fa-edit tool">
                                        </span>
                                    </a>';
                        //}

                        // if ($row->status == "Finalizado") {
                        //     $links .= '<a href="javascript:void(0)" data-toggle="tooltip"
                        //                     data-id="' . $row->id . '"
                        //                     data-notes="' . $row->notes . '"
                        //                     data-status="' . $row->status . '"
                        //                     data-team1="' . $row->team1 . '"
                        //                     data-team2="' . $row->team2 . '"
                        //                     data-team1_id="' . $row->team1_id . '"
                        //                     data-team2_id="' . $row->team2_id . '"
                        //                     data-score_team1="' . $row->score_team1 . '"
                        //                     data-score_team2="' . $row->score_team2 . '"
                        //                     data-score_penalties_team1="' . $row->score_penalties_team1 . '"
                        //                     data-score_penalties_team2="' . $row->score_penalties_team2 . '"
                        //                     data-penalties="'. $row->enable_penalties_scoring .'"
                        //                     title="Capturar Historia!"
                        //                     id="btnHistory">
                        //                 <span class="fa fa-history tool">
                        //                 </span>
                        //             </a>';
                        // }

                        $links .= ' <a href="javascript:void(0)"
                            data-id="' . $row->id . '"
                            data-notes="' . $row->notes . '"
                            data-status="' . $row->status . '"
                            data-period="' . $row->period . '"
                            data-team1="' . $row->team1 . '"
                            data-team2="' . $row->team2 . '"
                            data-score_team1="' . $row->score_team1 . '"
                            data-score_team2="' . $row->score_team2 . '"
                            data-score_penalties_team1="' . $row->score_penalties_team1 . '"
                            data-score_penalties_team2="' . $row->score_penalties_team2 . '"
                            data-penalties="'. $row->enable_penalties_scoring .'"
                            title="Capturar resultados"
                            id="btnScore">
                        <span class="fa fa-calculator tool"></span>
                        </a>
                        <a href="' . $routeDelete . '" data-name="'. $row->id .'"  title="Borrar Registro"  id="btnDelete">
                            <span class="fa fa-trash tool"></span>
                        </a>';

                        return $links;

                    })
                    ->rawColumns(['access_code', 'date_time', 'status', 'game_time','score', 'action'])
                    ->make(true);
        }

        $tournaments = Tournaments::where('is_deleted','0')
            ->where('status','!=','Terminado')
            ->where('user_id',Auth::user()->id)
            ->get(['id','title']);

        $stadiums = Stadiums::where('is_deleted','0')
            ->where('user_id',Auth::user()->id)
            ->where('is_active',1)
            ->orderBy('name','asc')
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
            //'category_id'       => 'required',
            'game_day'          => 'required',
            'game_time'         => 'required',
            'team1_id'          => 'required',
            'team2_id'          => 'required',
            //'period'            => 'required',
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
            //'period'            => 'Periodo / Tiempo',
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
            $model              = new Games($request->all());
            $model->user_id     = Auth::user()->id;
            $model->code        = strtoupper( str_random(8));
            $model->access_code = strtoupper( str_random(6));

            if ($model->save())
                 return response()->json(['type' => 'success',
                                 'message' => 'Se ha creado el Juego!']);
        } else {
            $model = Games::find($request->id);
            $model->fill($request->all());

            if (!$model->code) {
                $model->code    = strtoupper( str_random(8));
            }

            if (!$model->access_code) {
                $model->access_code    = strtoupper( str_random(6));
            }

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

    public function list($tournament_id) {

        if ($tournament_id) {
            $list = Games::where('tournament_id',$tournament_id)->get();
            return (string) $list;
        }

        return (string) false;
    }

    public function updateScore(Request $request) {
        //update the score
        if ($request) {
            $game = Games::find($request->id);

            //$game->status = "Finalizado";
            $game->fill($request->all());
            if ($game->update()) {
                //we need to update the wins, losses, and position for both teams

                return response()->json(['type' => 'success',
                                 'message' => 'Se ha modificado el juegoo!']);
            }
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar guardar el resultado del juego!'
        ]);
    }

    public function showGame(Request $request) {

        echo 'Holis' . $request->code;
        if (!$request->code) {
            return false;
        }

        //show the game page
        return view('games.show');
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

    function data_entry(Request $request) {
        if (!$request->access_code) {
            return response()->json([
                'access'=>'false',
                'type'=>'error',
                'message' => 'Algo salió NO HAY clave de acceso'
            ]);
        }

        $access_code = $request->access_code;

        $has_access = Games::where('access_code', $access_code)->get(['id']);

        if (count($has_access) === 0) {
            return response()->json([
                'access'=>'false',
                'type'=>'error',
                'message' => 'No tiene acceso a editar el juego',
                'game_id' => 0
            ]);
        }

        return response()->json([
            'access'=>'true',
            'type'=>'success',
            'message' => 'Bienvenido al modo de edición del juego',
            'game_id' => $has_access[0]->id
        ]);

    }

    function getTeamGames ($teamId)
    {
        $gameList = false;

        if ($teamId) {
            $query = "select
                t1.name as team1,
                score.score1,
                t2.name as team2,
                score.score2,
                if(score.score1 > score.score2, 'Ganado','Perdido') as result,
                score.game_day,
                score.game_time
                from
                (
                    (
                        select h.team1_id team1, h.team2_id team2, h.score_team1 score1, h.score_team2 score2, h.game_day, h.game_time
                        from games h
                        WHERE
                        h.status = 'Finalizado'
                        and h.is_deleted = 0
                        and h.type = 'Temporada Regular'
                    )
                    UNION ALL
                    (
                        select v.team2_id team1, v.team1_id team2, v.score_team2 score1, v.score_team1 score2,  v.game_day, v.game_time
                        from games v
                        WHERE
                        v.status = 'Finalizado'
                        and v.is_deleted = 0
                        and v.type = 'Temporada Regular'
                    )
                ) AS score
                JOIN teams t1 on t1.id = score.team1
                JOIN teams t2 on t2.id = score.team2
                WHERE
                t1.status = 'Activo'
                AND t1.id = $teamId
                AND t2.status = 'Activo'
                ORDER BY score.game_day DESC, score.game_time DESC
                ;";

            $gameList = DB::select( DB::raw( $query ) );

        }

        return $gameList;

    }

    function getJourneyGames($journeyId) {

        //get the tournament
        $journey = Journeys::where('id', $journeyId)->get();
        $tournament = Tournaments::where('id', $journey[0]->tournament_id)->get();

        $games  = DB::table('games as g')
        ->select('g.id',
         't1.name as team1',
         't1.logo as t1logo',
         'g.score_team1',
         'g.score_team2',
         't2.name as team2',
         't2.logo as t2logo',
         'c.name as category',
         'g.status' , 'g.notes',
         'g.score_team1',
         'g.score_team2',
         'g.game_day',
         'g.journey_id',
         'g.game_time',
         'g.tournament_id',
         'g.branch',
         'g.category_id',
         's.name as stadium',
         'g.type',
         'g.score_penalties_team1',
         'g.score_penalties_team2',
         'g.code')
        ->join('teams as t1', 't1.id', '=', 'g.team1_id')
        ->join('teams as t2', 't2.id', '=', 'g.team2_id')
        ->leftJoin('categories as c', 'c.id', '=', 'g.category_id')
        ->join('journeys as j', 'j.id', '=', 'g.journey_id')
        ->leftJoin('stadiums as s', 's.id', '=', 'g.stadium_id')
        ->where('g.is_deleted', 0)
        ->where('j.is_deleted', 0)
        ->where('j.show_public', "Si")
        ->where('j.id', $journeyId)
        ->orderBy('g.game_day', 'asc')
        ->orderBy('g.game_time', 'asc')
        ->get();

        $btnGames = '';
        foreach ($games as $game) {
            $viewGame =  View::make('components/btnGame', [
                'game'         => $game,
                'tournament'    => $tournament[0]
            ]);

            $btnGames .= (string)$viewGame;


        }
        return $btnGames;

    }

}
