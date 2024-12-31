<?php

namespace App\Http\Controllers;

use App\Category;
use App\Journeys;
use App\Tournaments;
use App\Teams;
use App\Games;
use App\Roster;
use App\Pictures;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use DataTables;
use DB;
use App\Sports;
use App\Stadiums;
use App\WebPage;
use FontLib\TrueType\Collection;
use Illuminate\Support\Facades\DB as FacadesDB;

class TournamentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['showTournament', 'frontPage', 'results', 'calendar', 'standings', 'teams', 'game', 'photos']]);
    }
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
                    ->select('t.id','t.code', 't.title', 't.is_deleted', 't.status', 't.contacts', 't.prints', 't.clicks')
                    ->where('t.is_deleted', 0)
                    ->where('t.user_id',Auth::user()->id)
                    ->orderBy('t.id', 'desc')
                    ->get();

            return DataTables::of($rows)
                    ->addColumn('code', function($row) {
                        return  '<b>' . $row->code . '</b>';
                    })
                    ->addColumn('title', function($row) {
                        return '<h4>' . $row->title . '</h4> Equipos inscritos: <br> Impresiones: '.$row->prints.' - Clicks: '.$row->clicks;
                    })
                    ->addColumn('status', function($row) {
                        switch ($row->status) {
                            case 'Por Empezar':
                                return '<span class="badge badge-info">'. $row->status.' <br> Inscripciones abiertas</span>';
                            case 'En Progreso':
                                return '<span class="badge badge-warning">'. $row->status.'</span>';
                            case 'Terminado':
                                return '<span class="badge badge-danger">'. $row->status.'</span>';
                        }

                    })
                    ->addColumn('action', function($row) {

                        $routeEdit = route('tournaments.show', $row->id);
                        $routeDelete = route('tournaments.destroy', $row->id);

                        $links = '';

                        if ($row->status == 'Por Empezar') {
                            $links .= '<a href="' . $routeEdit . '" data-toggle="tooltip" title="¡Editar Registro!" id="btnUpdate">
                                    <span class="fa fa-edit tool"></span></a>
                                <a href="' . $routeDelete . '" title="Borrar Registro" data-name="'.$row->title.'" id="btnDelete">
                                <span class="fa fa-trash tool"></span>
                            </a>
                            <a href="javascript:void(0)" title="Iniciar Torneo" data-id="'. $row->id.'"  data-name="'.$row->title.'" data-status="En Progreso" id="btnStatus">
                                        <span class="fa fa-play tool"></span>
                                    </a>';
                        }

                        if ($row->status == 'En Progreso') {
                            $links .= '<a href="' . $routeEdit . '" data-toggle="tooltip" title="¡Editar Registro!" id="btnUpdate">
                            <span class="fa fa-edit tool"></span></a>
                                <a href="javascript:void(0)" title="Terminar y archivar Torneo" data-id="'. $row->id.'"  data-name="'.$row->title.'" data-status="Terminado" id="btnStatus">
                                        <span class="fa fa-stop tool"></span>
                                    </a>';
                        }

                        $links .= '<a href="javascript:void(0)" title="Ver Estadisticas" data-id="'. $row->id.'"  data-name="'.$row->title.'" id="btnStats">
                                <span class="fa fa-bar-chart tool"></span>
                            </a>';

                        return $links;
                    })
                    ->rawColumns(['title','status', 'action', 'code'])
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
            'title'     => 'required|max:120',
            'summary'   => 'required|max:240',
            'contacts'   => 'required|max:240',
        ];

        $attributes = [
            'title'     => 'Nombre',
            'summary'   => 'Descripción',
            'contacts'  => 'Contactos',
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
            $model->slug = str_slug($model->title);

            if (isset($request->enable_points_system))  {
                $model->enable_points_system = "Si";
            } else {
                $model->enable_points_system = "No";
                $model->winner_points =0;
                $model->loser_points =0;
                $model->draw_points =-1;
            }

            if (isset($request->enable_penalties_scoring))  {
                $model->enable_penalties_scoring = "Si";
            } else {
                $model->enable_penalties_scoring = "No";
                $model->winner_points_penalties =-1;
                $model->loser_points_penalties =-1;
            }

            if (!empty($path))
                $model->image = $path;

            if ($model->save())
                 return response()->json(['type' => 'success',
                                 'message' => 'Se ha creado el Torneo!']);
        } else {
            $model = Tournaments::find($request->id);

            $lastImage = $model->image;
            $model->fill($request->all());
            $model->slug = str_slug($model->title);

            if (isset($request->enable_points_system))  {
                $model->enable_points_system = "Si";
            } else {
                $model->enable_points_system = "No";
                $model->winner_points =0;
                $model->loser_points =0;
                $model->draw_points =-1;
            }

            if (isset($request->enable_penalties_scoring))  {
                $model->enable_penalties_scoring = "Si";
            } else {
                $model->enable_penalties_scoring = "No";
                $model->winner_points_penalties =-1;
                $model->loser_points_penalties =0-1;
            }

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
            $model = Tournaments::select('id',
                'code',
                'title',
                'summary',
                'contacts',
                'status',
                'sport_id',
                'enable_points_system',
                'winner_points',
                'loser_points',
                'draw_points',
                'winner_points_penalties',
                'loser_points_penalties',
                'enable_penalties_scoring')->find($id);

            if (!$model->code) {
                $model->code =  strtoupper( str_random(8));
            }

            if ( !$model->sport_id ) {
                $model->sport_id = Auth::user()->league->sport_id;
            }
            $sport = Sports::select('name')->find($model->sport_id);
            $model->sport = $sport->name;

        return $model;
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tournaments  $tournaments
     * @return \Illuminate\Http\Response
     */
    public function start( Request $request )
    {
        //
        if (isset($request)) {
            Tournaments::where('id', $request->idStart)->update(['status' => $request->status ]);

            return response()->json([
                'type' => 'success',
                'message' => 'El Torneo se ha inciado!'
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Error al intentar borrar el Torneo!'
        ]);
    }

    public function getScores(
        int $tournament_id,
        array $categories,
        string $points,
        string $limit = null
        ): array {

        // foreach ($categories as $category) {

        //     $query = "SELECT t.id, t.name as team,
        //         count('x') as JJ,
        //         count(case when PAF > PEC then 1 end) JG,
        //         count(case when PAF < PEC then 1 end) JP,
        //         count(case when PAF = PEC then 1 end) JE,
        //         count(case when PPAF > PPEC then 1 end) JGP,
        //         count(case when PPAF < PPEC then 1 end) JPP,
        //         sum(score.PAF) as PAF,
        //         sum(score.PEC) as PEC,
        //         sum(score.PAF) - sum(score.PEC) as DIF
        //         $points, t.logo
        //         FROM
        //         (
        //             (SELECT
        //                     h.team1_id team,
        //                     h.score_team1 PAF,
        //                     h.score_team2 PEC,
        //                     h.score_penalties_team1 PPAF,
        //                     h.score_penalties_team2 PPEC
        //                     FROM games h
        //                     INNER JOIN journeys j on j.id = h.journey_id
        //                     WHERE h.category_id=" . $category->id . " and
        //                     h.status = 'Finalizado' and
        //                     h.is_deleted = 0 and
        //                     h.type = 'Temporada Regular' and
        //                     j.show_public ='Si' and
        //                     j.tournament_id = ". $tournament_id ."
        //             )
        //         UNION ALL
        //             ( SELECT
        //                     v.team2_id team,
        //                     v.score_team2 PEF,
        //                     v.score_team1 PEC,
        //                     v.score_penalties_team2 PPAF,
        //                     v.score_penalties_team1 PPEC
        //                     FROM games v
        //                     INNER JOIN journeys j on j.id = v.journey_id
        //                     WHERE v.category_id=" . $category->id ." and
        //                     v.status = 'Finalizado' and
        //                     v.is_deleted = 0 and
        //                     v.type = 'Temporada Regular' and
        //                     j.show_public ='Si' and
        //                     j.tournament_id = ". $tournament_id ."
        //             )
        //         ) AS score
        //         JOIN teams t on t.id = score.team
        //         WHERE
        //         t.status = 'Activo'
        //         GROUP BY team ";

        //     if ( strlen($points)==0) {
        //         $query .= 'ORDER BY JG desc, JP asc';
        //     }

        //     if (strlen($points)>0) {
        //         $query .= 'ORDER BY PUNTOS DESC';
        //     }

        //     $query .= ', DIF DESC';

        //     if ($limit) {
        //         $query .= ' LIMIT ' . $limit;
        //     }

        //     $scores[$category->id] = DB::select( DB::raw( $query ) );
        // }

        //handle scores for multicategories
        $query = "SELECT t.id, t.category_id, t.name as team,
                count('x') as JJ,
                count(case when PAF > PEC then 1 end) JG,
                count(case when PAF < PEC then 1 end) JP,
                count(case when PAF = PEC then 1 end) JE,
                count(case when PPAF > PPEC then 1 end) JGP,
                count(case when PPAF < PPEC then 1 end) JPP,
                sum(score.PAF) as PAF,
                sum(score.PEC) as PEC,
                sum(score.PAF) - sum(score.PEC) as DIF
                $points, t.logo
                FROM
                (
                    (SELECT
                            h.team1_id team,
                            h.score_team1 PAF,
                            h.score_team2 PEC,
                            h.score_penalties_team1 PPAF,
                            h.score_penalties_team2 PPEC
                            FROM games h
                            INNER JOIN journeys j on j.id = h.journey_id
                            WHERE
                            h.status = 'Finalizado' and
                            h.is_deleted = 0 and
                            h.type = 'Temporada Regular' and
                            j.show_public ='Si' and
                            j.tournament_id = ". $tournament_id ."
                    )
                UNION ALL
                    ( SELECT
                            v.team2_id team,
                            v.score_team2 PEF,
                            v.score_team1 PEC,
                            v.score_penalties_team2 PPAF,
                            v.score_penalties_team1 PPEC
                            FROM games v
                            INNER JOIN journeys j on j.id = v.journey_id
                            WHERE
                            v.status = 'Finalizado' and
                            v.is_deleted = 0 and
                            v.type = 'Temporada Regular' and
                            j.show_public ='Si' and
                            j.tournament_id = ". $tournament_id ."
                    )
                ) AS score
                JOIN teams t on t.id = score.team
                WHERE
                t.status = 'Activo'
                GROUP BY team ";

            if ( strlen($points)==0) {
                $query .= 'ORDER BY JG desc, JP asc';
            }

            if (strlen($points)>0) {
                $query .= 'ORDER BY PUNTOS DESC';
            }

            $query .= ', DIF DESC';

            if ($limit) {
                $query .= ' LIMIT ' . $limit;
            }

        $scores = DB::select( DB::raw( $query ) );

        // foreach($scores as $scores) {
        //     echo  $key . " ---> ";
        //     echo '<hr>';
        // }
        //print_r($scores);


        return $scores;
    }

    function getGames(int $tournament_id, $status_game, $journey_ids) {

        return  DB::table('games as g')
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
            'g.code',
            'g.period'
            )
            ->join('teams as t1', 't1.id', '=', 'g.team1_id')
            ->join('teams as t2', 't2.id', '=', 'g.team2_id')
            ->leftJoin('categories as c', 'c.id', '=', 'g.category_id')
            ->join('journeys as j', 'j.id', '=', 'g.journey_id')
            ->leftJoin('stadiums as s', 's.id', '=', 'g.stadium_id')
            ->where('g.is_deleted', 0)
            ->where('j.is_deleted', 0)
            ->where('j.show_public', "Si")
            ->where('g.status', 'like', '%'. $status_game)
            ->where('g.tournament_id', $tournament_id )
            ->whereIn('g.journey_id', $journey_ids )
            ->orderBy('g.game_day', 'asc')
            ->orderBy('g.game_time', 'asc')
            ->get();

    }

    public function showTournament ($slug){
        if (!$slug) {
            //lets send this to the tournaments list
        }
        //lets get the tournament info
        $tournament = Tournaments::where('slug', $slug)->get();
        $teams = Teams::where('tournament_id', $tournament[0]->id)
            ->where('is_deleted',0)
            ->orderby('name','asc')
            ->get();

        $clicks = $tournament[0]->clicks + 1;
        Tournaments::where('id', $tournament[0]->id)->update(['clicks' => $clicks ]);

        //handle the game code, if the code doesn't exist, we just create a new one
        $games = Games::where('tournament_id', $tournament[0]->id)->get();

        foreach ($games as $game) {
            $code =  strtoupper( str_random(8));

            if (!$game->code) {
                Games::where('id', $game->id)->where('is_deleted',0)->update(['code' => $code ]);
            }
        }

        //send the game details
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
        ->join('categories as c', 'c.id', '=', 'g.category_id')
        ->join('journeys as j', 'j.id', '=', 'g.journey_id')
        ->leftJoin('stadiums as s', 's.id', '=', 'g.stadium_id')
        ->where('g.is_deleted', 0)
        ->where('j.is_deleted', 0)
        ->where('j.show_public', "Si")
        // ->where('g.status', 'Finalizado')
        ->where('g.tournament_id', $tournament[0]->id )
        ->orderBy('g.game_day', 'asc')
        ->orderBy('g.game_time', 'asc')
        ->get();


        $categories = Category::where('tournament_id', $tournament[0]->id)->where('is_deleted',0)->orderBy('name', 'asc')->get();
        $journeys = Journeys::where('tournament_id', $tournament[0]->id)->where('is_deleted',0)->orderBy('id','desc')->get();
        $stadiums = Stadiums::where('user_id', $tournament[0]->user_id)->where('is_deleted',0)->get(['id','name']);

        $scores = array();

        //build points section of the query!
        $points = '';
        if ($tournament[0]->enable_points_system == "Si"){
            $points = ", sum( case when PAF > PEC then " . $tournament[0]->winner_points . " else 0 end \n";

            if ($tournament[0]->draw_points > -1
                && $tournament[0]->enable_penalties_scoring == "No" ){
                $points .= " + case when PAF = PEC then " . $tournament[0]->draw_points . " else 0 end \n";
            }

            if ($tournament[0]->enable_points_system == "Si"
                && $tournament[0]->enable_penalties_scoring == "No"){
                    $points .= " + case when PAF < PEC then " . $tournament[0]->loser_points . " else 0 end ) as PUNTOS";
            } else {
                $points .= " + case when PPAF > PPEC then " . $tournament[0]->winner_points_penalties . " else 0 end \n";

                $points .= " + case when PPAF < PPEC then " . $tournament[0]->loser_points_penalties . " else 0 end ) as PUNTOS";

            }
        }

        // if ($tournament[0]->enable_penalties_scoring == "Si"){
        //     $penalties = ", sum( case when PPAF > PPEC then " . $tournament[0]->winner_points_penalties . " else 0 end \n";

        //     $penalties .= " + case when PPAF < PPEC then " . $tournament[0]->loser_points_penalties . " else 0 end ) as PUNTOS_PENALTIES";
        // }

        foreach ($categories as $category) {
            $query = "SELECT  t.name as team,
                count('x') as JJ,
                count(case when PAF > PEC then 1 end) JG,
                count(case when PAF < PEC then 1 end) JP,
                count(case when PAF = PEC then 1 end) JE,
                count(case when PPAF > PPEC then 1 end) JGP,
                count(case when PPAF < PPEC then 1 end) JPP,
                sum(score.PAF) as PAF,
                sum(score.PEC) as PEC,
                sum(score.PAF) - sum(score.PEC) as DIF
                $points, t.logo
                FROM
                (
                    (SELECT
                            h.team1_id team,
                            h.score_team1 PAF,
                            h.score_team2 PEC,
                            h.score_penalties_team1 PPAF,
                            h.score_penalties_team2 PPEC
                            FROM games h
                            INNER JOIN journeys j on j.id = h.journey_id
                            WHERE h.category_id=" . $category->id . " and
                            h.status = 'Finalizado' and
                            h.type = 'Temporada Regular' and
                            j.show_public ='Si' and
                            j.tournament_id = ". $tournament[0]->id ."
                    )
                UNION ALL
                    ( SELECT
                            v.team2_id team,
                            v.score_team2 PEF,
                            v.score_team1 PEC,
                            v.score_penalties_team2 PPAF,
                            v.score_penalties_team1 PPEC
                            FROM games v
                            INNER JOIN journeys j on j.id = v.journey_id
                            WHERE v.category_id=" . $category->id ." and
                            v.status = 'Finalizado' and
                            v.type = 'Temporada Regular' and
                            j.show_public ='Si' and
                            j.tournament_id = ". $tournament[0]->id ."
                    )
                ) AS score
                JOIN teams t on t.id = score.team
                WHERE
                t.status = 'Activo'
                GROUP BY team ";

            if ( strlen($points)==0) {
                $query .= 'ORDER BY JG desc, JP asc';
            }

            if (strlen($points)>0) {
                $query .= 'ORDER BY PUNTOS DESC';
            }

            $query .= ', DIF DESC';
            $scores[$category->id] = DB::select( DB::raw( $query ) );
        }

        return view ('tournaments.show', [
            'tournament' => $tournament[0],
            'journeys'   => $journeys,
            'games'      => $games,
            'categories' => $categories,
            'stadiums'   => $stadiums,
            'scores'     => $scores,
            'teams'      => $teams
        ]);
    }

    function frontPage($slug) {

        if (!$slug) {
            //lets send this to the tournaments list
            return view ('app.error');
        }

        //lets get the tournament info
        $tournament = Tournaments::where('slug', $slug)->get();

        $clicks = $tournament[0]->clicks + 1;
        Tournaments::where('id', $tournament[0]->id)->update(['clicks' => $clicks ]);
        // $teams = Teams::where('tournament_id', $tournament[0]->id)
        //     ->where('is_deleted',0)
        //     ->orderby('name','asc')
        //     ->get();

        $categories = Category::where('tournament_id', $tournament[0]->id)->where('is_deleted',0)->orderBy('name', 'asc')->get();
        $webContents = WebPage::where('tournament_id', $tournament[0]->id)->where('is_deleted',0)->orderBy('created_at', 'desc')->get();

        $points = '';
        if ($tournament[0]->enable_points_system == "Si"){
            $points = ", sum( case when PAF > PEC then " . $tournament[0]->winner_points . " else 0 end \n";

            if ($tournament[0]->draw_points > -1
                && $tournament[0]->enable_penalties_scoring == "No" ){
                $points .= " + case when PAF = PEC then " . $tournament[0]->draw_points . " else 0 end \n";
            }

            if ($tournament[0]->enable_points_system == "Si"
                && $tournament[0]->enable_penalties_scoring == "No"){
                    $points .= " + case when PAF < PEC then " . $tournament[0]->loser_points . " else 0 end ) as PUNTOS";
            } else {
                $points .= " + case when PPAF > PPEC then " . $tournament[0]->winner_points_penalties . " else 0 end \n";

                $points .= " + case when PPAF < PPEC then " . $tournament[0]->loser_points_penalties . " else 0 end ) as PUNTOS";

            }
        }

        $journeys = Journeys::where('tournament_id', $tournament[0]->id)
            ->where('is_deleted',0)
            ->where('show_public','Si')
            ->where('status','Abierto')
            ->where('start_at','<=', date('Y-m-d 00:00'))
            ->where('finish_at','>', date('Y-m-d 23:59') )
            ->orderBy('id','asc')
            ->get();

        //$stadiums = Stadiums::where('user_id', $tournament[0]->user_id)->where('is_deleted',0)->get(['id','name']);
        $scores = $this->getScores($tournament[0]->id, $categories->all(), $points);

        $journeyIDs = array_map(function($journay){
                return  $journay->id;
        },$journeys->all());

        $games  = $this->getGames($tournament[0]->id, '', $journeyIDs);

        $stadiums =  [];
        $stadiums = array_map(function($game){
            if ($game->stadium != null) {
                return  $game->stadium;
            }
        },$games->all());
        $stadiums = array_unique($stadiums);

        $game_days = [];
        $game_days = array_map(function($game){
            if ($game->game_day != null) {
                return  $game->game_day;
            }
        },$games->all());
        $game_days = array_unique($game_days);

        return view ('tournaments.front_page', [
            'tournament'    => $tournament[0],
            'webContents'   => $webContents,
            'categories'    => $categories,
            'scores'        => $scores,
            'journeys'      => $journeys,
            'games'         => $games,
            'stadiums'      => $stadiums,
            'game_days'     => $game_days
            //'teams'         => $teams,
            // 'teams'      => $teams
        ]);
    }

    function results($slug) {

        if (!$slug) {
            //lets send this to the tournaments list
            return view ('app.error');
        }

        //lets get the tournament info
        $tournament = Tournaments::where('slug', $slug)->get();

        $categories = Category::where('tournament_id', $tournament[0]->id)->where('is_deleted',0)->orderBy('name', 'asc')->get();
        $journeys = Journeys::where('tournament_id', $tournament[0]->id)->where('is_deleted',0)->orderBy('id','asc')->get();
        $stadiums = Stadiums::where('user_id', $tournament[0]->user_id)->where('is_deleted',0)->get(['id','name']);

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
        // ->where('g.status', 'Finalizado')
        ->where('g.tournament_id', $tournament[0]->id )
        ->orderBy('g.game_day', 'asc')
        ->orderBy('g.game_time', 'asc')
        ->get();


        return view ('tournaments.results', [
            'tournament' => $tournament[0],
            'journeys'   => $journeys,
            'categories' => $categories,
            'games'      => $games,
            // 'stadiums'   => $stadiums,
            // 'scores'     => $scores,
            // 'teams'      => $teams
        ]);
    }

    function photos($slug) {

        if (!$slug) {
            //lets send this to the tournaments list
            return view ('app.error');
        }

        //lets get the tournament info
        $tournament = Tournaments::where('slug', $slug)->get();

        $categories = Category::where('tournament_id', $tournament[0]->id)->where('is_deleted',0)->orderBy('name', 'asc')->get();
        $journeys   = Journeys::where('tournament_id', $tournament[0]->id)->where('is_deleted',0)->orderBy('id','desc')->get();
        $stadiums   = Stadiums::where('user_id', $tournament[0]->user_id)->where('is_deleted',0)->get(['id','name']);
        $pictures   = Pictures::where('tournament_id', $tournament[0]->id)->orderBy('id','desc')->get();

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
        // ->where('g.status', 'Finalizado')
        ->where('g.tournament_id', $tournament[0]->id )
        ->orderBy('g.game_day', 'asc')
        ->orderBy('g.game_time', 'asc')
        ->get();


        return view ('tournaments.photos', [
            'tournament'    => $tournament[0],
            'journeys'      => $journeys,
            'games'         => $games,
            'pictures'      => $pictures,
            // 'stadiums'   => $stadiums,
            // 'scores'     => $scores,
            // 'teams'      => $teams
        ]);
    }

    function calendar($slug) {

        if (!$slug) {
            //lets send this to the tournaments list
            return view ('app.error');
        }

        //lets get the tournament info
        $tournament = Tournaments::where('slug', $slug)->get();

        $categories = Category::where('tournament_id', $tournament[0]->id)->where('is_deleted',0)->orderBy('name', 'asc')->get();
        $journeys = Journeys::where('tournament_id', $tournament[0]->id)->where('is_deleted',0)->orderBy('id','desc')->get();
        $stadiums = Stadiums::where('user_id', $tournament[0]->user_id)->where('is_deleted',0)->get(['id','name']);


        return view ('tournaments.calendar', [
            'tournament' => $tournament[0],
            'journeys'   => $journeys,
            'categories' => $categories,
            // 'games'      => $games,
            // 'stadiums'   => $stadiums,
            // 'scores'     => $scores,
            // 'teams'      => $teams
        ]);
    }

    function standings($slug) {

        if (!$slug) {
            //lets send this to the tournaments list
            return view ('app.error');
        }

        //lets get the tournament info
        $tournament = Tournaments::where('slug', $slug)->get();

        $categories = Category::where('tournament_id', $tournament[0]->id)->where('is_deleted',0)->orderBy('name', 'asc')->get();
        $journeys = Journeys::where('tournament_id', $tournament[0]->id)->where('is_deleted',0)->orderBy('id','desc')->get();
        $stadiums = Stadiums::where('user_id', $tournament[0]->user_id)->where('is_deleted',0)->get(['id','name']);

        $points = '';
        if ($tournament[0]->enable_points_system == "Si"){
            $points = ", sum( case when PAF > PEC then " . $tournament[0]->winner_points . " else 0 end \n";

            if ($tournament[0]->draw_points > -1
                && $tournament[0]->enable_penalties_scoring == "No" ){
                $points .= " + case when PAF = PEC then " . $tournament[0]->draw_points . " else 0 end \n";
            }

            if ($tournament[0]->enable_points_system == "Si"
                && $tournament[0]->enable_penalties_scoring == "No"){
                    $points .= " + case when PAF < PEC then " . $tournament[0]->loser_points . " else 0 end ) as PUNTOS";
            } else {
                $points .= " + case when PPAF > PPEC then " . $tournament[0]->winner_points_penalties . " else 0 end \n";

                $points .= " + case when PPAF < PPEC then " . $tournament[0]->loser_points_penalties . " else 0 end ) as PUNTOS";

            }
        }

        $scores = $this->getScores($tournament[0]->id, $categories->all(), $points);

        return view ('tournaments.standings', [
            'tournament' => $tournament[0],
            'journeys'   => $journeys,
            'categories' => $categories,
            'scores'     => $scores,
            // 'games'      => $games,
            // 'stadiums'   => $stadiums,
            // 'teams'      => $teams
        ]);
    }

    function teams($slug)
    {

        if (!$slug) {
            //lets send this to the tournaments list
            return view ('app.error');
        }

        //lets get the tournament info
        $tournament = Tournaments::where('slug', $slug)->get();
        $teams = Teams::where('tournament_id', $tournament[0]->id)
            ->where('is_deleted',0)
            ->orderby('name','asc')
            ->get();

        $categories = Category::where('tournament_id', $tournament[0]->id)->where('is_deleted',0)->orderBy('name', 'asc')->get();
        //$journeys = Journeys::where('tournament_id', $tournament[0]->id)->where('is_deleted',0)->orderBy('id','desc')->get();
        //$stadiums = Stadiums::where('user_id', $tournament[0]->user_id)->where('is_deleted',0)->get(['id','name']);


        return view ('tournaments.teams', [
            'tournament' => $tournament[0],
            'teams'      => $teams,
            'categories' => $categories,
            // 'journeys'   => $journeys,
            // 'games'      => $games,
            // 'stadiums'   => $stadiums,
            // 'scores'     => $scores,
        ]);
    }

    public function game(string $slug, string $code )
    {
        if (!$code) {
            return view ('app.error');
        }

        $game  = DB::table('games as g')
        ->select('g.id',
         't1.name as team1',
         't1.logo as t1logo',
         'g.score_team1',
         'g.score_team2',
         't2.name as team2',
         't2.logo as t2logo',
         'c.name as category',
         'g.status' , 'g.notes',
         'g.team1_id',
         'g.team2_id',
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
        ->join('categories as c', 'c.id', '=', 'g.category_id')
        ->join('journeys as j', 'j.id', '=', 'g.journey_id')
        ->leftJoin('stadiums as s', 's.id', '=', 'g.stadium_id')
        ->where('g.is_deleted', 0)
        ->where('j.is_deleted', 0)
        ->where('j.show_public', "Si")
        ->where('g.code', $code)
        //->where('g.tournament_id', $tournament[0]->id )
        ->orderBy('g.game_day', 'asc')
        ->orderBy('g.game_time', 'asc')
        ->get()[0];

        $players = Roster::whereIn('team_id', [$game->team1_id, $game->team2_id])->get();


        return view ('games.show', [
            'game'      => $game,
            'players'   => $players
            // 'tournament' => $tournament[0],
            // 'teams'      => $teams,
            // 'categories' => $categories,
            // 'journeys'   => $journeys,
            // 'stadiums'   => $stadiums,
            // 'scores'     => $scores,
        ]);

    }


}
