<?php

namespace App\Http\Controllers;

use App\GameLogs;
use App\Games;
use Illuminate\Http\Request;

class GameLogsController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['storeLog']]);
    }

    public function storeLog(Request $request) {
        $id = 0;
        $message = '';
        $score = 0;

        $log = new GameLogs($request->all());

        //see if we need to update the game table for the score
        if (
            $log->value > 0
            && $log->status == 'Exitoso'
        ) {
            //change the score value
            $game =  Games::find($log->game_id);

            if ($game->team1_id == $log->team_id) {
                $game->score_team1 = $game->score_team1 + $log->value;
                $score = $game->score_team1;
            }

            if ($game->team2_id == $log->team_id) {
                $game->score_team2 = $game->score_team2 + $log->value;
                $score = $game->score_team2;
            }

            $game->update();
        }

        if ($log->save()) {
            $id = $log->id;
            $message = 'Registro guardado';
        } else {
            $message = 'Error al intentar guardar el registro';
        }

        return response()->json([
            'id'        => $id,
            'type'      => 'success',
            'message'   => $message,
            'score'     => $score
        ]);

    }
}
