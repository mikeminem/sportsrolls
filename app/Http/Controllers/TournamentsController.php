<?php

namespace App\Http\Controllers;

use App\Category;
use App\Tournaments;
use App\Teams;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use DataTables;
use DB;
use App\Sports;
use Illuminate\Support\Facades\DB as FacadesDB;

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
                                <span class="fa fa-edit tool">
                                </span>
                                </a><a href="' . $routeDelete . '" title="Borrar Registro" data-name="'.$row->title.'" id="btnDelete"> 
                                <span class="fa fa-trash tool"></span>
                            </a>
                            <a href="javascript:void(0)" title="Iniciar Torneo" data-id="'. $row->id.'"  data-name="'.$row->title.'" data-status="En Progreso" id="btnStatus"> 
                                        <span class="fa fa-play tool"></span>
                                    </a>';
                        }

                        if ($row->status == 'En Progreso') {
                            $links .= '<a href="javascript:void(0)" title="Terminar y archivar Torneo" data-id="'. $row->id.'"  data-name="'.$row->title.'" data-status="Terminado" id="btnStatus"> 
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
            'title'     => 'Nombre ',
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
            $model = Tournaments::select('id', 'code', 'title', 'summary', 'contacts', 'status','sport_id', 'enable_points_system','winner_points', 'loser_points', 'draw_points')->find($id);

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

    public function showTournament ($slug){
        if (!$slug) {
            //lets send this to the tournaments list
        }

        //lets get the tournament info
        $tournament = Tournaments::where('slug', $slug)->get();

        $teams = Teams::where('tournament_id', $tournament[0]->id)->orderby('name','asc')->get();

        $clicks = $tournament[0]->clicks + 1;
        Tournaments::where('id', $tournament[0]->id)->update(['clicks' => $clicks ]);

        //send the game details
        $games  = DB::table('games as g')
        ->select('g.id', 't1.name as team1', 'g.score_team1', 'g.score_team2',  't2.name as team2', 'c.name as category', 'g.status' , 'g.notes', 'g.score_team1', 'g.score_team2', 'g.game_day', 'g.journey_id', 'g.game_time', 'g.tournament_id', 'g.branch', 'g.category_id')
        ->join('teams as t1', 't1.id', '=', 'g.team1_id')
        ->join('teams as t2', 't2.id', '=', 'g.team2_id')
        ->join('categories as c', 'c.id', '=', 'g.category_id')        
        ->join('journeys as j', 'j.id', '=', 'g.journey_id')        
        ->where('g.is_deleted', 0)
        ->where('j.is_deleted', 0)
        ->where('j.show_public', "Si")
        // ->where('g.status', 'Finalizado')
        ->where('g.tournament_id', $tournament[0]->id )
        ->orderBy('g.game_day', 'asc')
        ->orderBy('g.game_time', 'asc')
        ->get();  

        $categories = Category::where('tournament_id', $tournament[0]->id)->where('is_deleted',0)->get();
        
        //lets send the score
        $scores = array();


        //build points section of the query!
        $points = "";
        if ($tournament[0]->enable_points_system == "Si"){
            $points = ", sum( case when PAF > PEC then " . $tournament[0]->winner_points . " else 0 end \n";

            if ($tournament[0]->draw_points > -1){
                $points .= " + case when PAF = PEC then " . $tournament[0]->draw_points . " else 0 end \n";
            }
                
            $points .= " + case when PAF < PEC then " . $tournament[0]->loser_points . " else 0 end ) as PUNTOS";
        }
        
        foreach ($categories as $category) {
            $query = "SELECT  t.name as team,
                count('x') as JJ,
                count(case when PAF > PEC then 1 end) JG,
                count(case when PAF < PEC then 1 end) JP,
                count(case when PAF = PEC then 1 end) JE,
                sum(score.PAF) as PAF,
                sum(score.PEC) as PEC,
                sum(score.PAF) - sum(score.PEC) as DIF
                $points
                FROM 
                (
                    (SELECT 
                            h.team1_id team, 
                            h.score_team1 PAF,
                            h.score_team2 PEC
                            FROM games h
                            INNER JOIN journeys j on j.id = h.journey_id
                            WHERE h.category_id=" . $category->id . " and
                            h.status = 'Finalizado' and
                            j.show_public ='Si' and
                            j.tournament_id = ". $tournament[0]->id ."
                    )
                UNION ALL
                    ( SELECT 
                            v.team2_id team,
                            v.score_team2 PEF,
                            v.score_team1 PEC
                            FROM games v
                            INNER JOIN journeys j on j.id = v.journey_id
                            WHERE v.category_id=" . $category->id ." and
                            v.status = 'Finalizado' and
                            j.show_public ='Si' and
                            j.tournament_id = ". $tournament[0]->id ."
                    )
                ) as score
                join teams t on t.id = score.team
                where
                t.status = 'Activo'
                group by t.name
                order by JG desc";

            if (strlen($points)>0) {
                $query .= ", PUNTOS desc";
            }

            $query .= ", DIF desc";
            
            $scores[$category->id] = DB::select( DB::raw( $query) );
        }

        return view ('tournaments.show', [
            'tournament' => $tournament[0],
            'games'      => $games,
            'categories' => $categories,
            'scores'     => $scores,
            'teams'      => $teams
        ]);



    }
}
