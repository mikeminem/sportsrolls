<?php

namespace App\Providers;

use App\FollowTeams;
use App\PageCategory;
use App\Teams;
use App\Tournaments;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use DB;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        view()->composer("*", function($view)
        {


            //create the finger print of the device
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
            $acceptLanguage = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
            $ipAddress = $_SERVER['REMOTE_ADDR'];
            $fingerPrint = md5 ($userAgent . $acceptLanguage . $ipAddress);
            $view->with('fingerPrint', $fingerPrint);

            if (Auth::guest()) {
                $pageCategories = PageCategory::where('status','Mostrar')->get();
                $followTournaments = Tournaments::where('status','En Progreso')->get();

                $followTeams = FollowTeams::where('follower_id', $fingerPrint)->get();
                //find the follower_id
                $follower = (count($followTeams)>0) ? true: null;

                $view->with('followTournaments', $followTournaments);
                $view->with('follower', $follower);

                //get the tournament name
                $ftournaments = [];
                $fteams = [];
                foreach ($followTeams as $followTeam) {
                    $ftournaments[] = $followTeam->follow_tournament_id;
                    $fteams[] = $followTeam->follow_team_id;
                }

                $followTournament = Tournaments::whereIn('id', $ftournaments)->get();

                $view->with('followTournament', $followTournament);

                $followTeam = Teams::whereIn('id', $fteams)->get();
                $view->with('followTeam', $followTeam);

                //find games
                $followGames = DB::table('games as g')
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
                //->where('g.status', 'like', '%'. $status_game)
                ->whereIn('g.tournament_id', $ftournaments )
                ->where(function($query) use($fteams) {
                    $query->WhereIn('g.team1_id',$fteams)
                        ->OrWhereIn('g.team2_id',$fteams);
                })
                ->where('g.game_day','>=', Date('Y-m-d'))
                //->whereIn('g.journey_id', $journey_ids )
                ->orderBy('g.game_day', 'asc')
                ->orderBy('g.game_time', 'asc')
                ->get();

                $view->with('followGames', $followGames);
                $view->with('pageCategories', $pageCategories);
            }

        });


    }
}
