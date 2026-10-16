<?php

namespace App\Http\Controllers;

use App\Actions;
use App\GameLogs;
use App\Games;
use App\Roster;
use App\Tournaments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GameLogsController extends Controller
{
    public function storeLog(Request $request)
    {
        $data = $request->validate([
            'game_id' => 'required|integer|min:1',
            'tournament_id' => 'required|integer|min:1',
            'team_id' => 'required|integer|min:1',
            'player_id' => 'required|integer|min:1',
            'action_id' => 'required|integer|min:1',
            'status' => 'required|in:Exitoso,Fallado',
            'period' => 'nullable|integer|min:1|max:99',
            'time' => ['required', 'regex:/^[0-9]{2}:[0-5][0-9]$/'],
            'comment' => 'nullable|string|max:1000',
        ]);

        abort_unless($request->session()->get('game_capture.'.$data['game_id']) === true,
            403, 'Introduce el código de acceso de este partido.');

        return DB::transaction(function () use ($data) {
            $game = Games::where('id', $data['game_id'])->where('is_deleted', 0)
                ->lockForUpdate()->firstOrFail();
            abort_if($game->status === 'Finalizado', 409, 'El partido está finalizado.');
            abort_unless((int) $game->tournament_id === (int) $data['tournament_id'] &&
                in_array((int) $data['team_id'], [(int) $game->team1_id, (int) $game->team2_id], true),
                422, 'El equipo o torneo no corresponde al partido.');

            $player = Roster::where('id', $data['player_id'])
                ->where('team_id', $data['team_id'])->where('is_deleted', 0)->first();
            abort_unless($player, 422, 'El jugador no pertenece al equipo.');

            $tournament = Tournaments::findOrFail($game->tournament_id);
            $action = Actions::where('id', $data['action_id'])
                ->where('sport_id', $tournament->sport_id)->where('is_deleted', 0)
                ->where('is_enabled', 'Si')->first();
            abort_unless($action, 422, 'La acción no está habilitada para este deporte.');
            abort_if($data['status'] === 'Fallado' && $action->need_confirmation !== 'Si',
                422, 'Esta acción no permite registrar un fallo.');
            abort_unless(is_numeric($action->value) && $action->value >= 0,
                422, 'El valor de la acción no es válido.');

            // The catalog supplies the value; client values and deletion flags are ignored.
            $data['value'] = $action->value;
            $log = new GameLogs($data);
            $log->is_deleted = 0;
            $log->saveOrFail();

            $column = (int) $game->team1_id === (int) $data['team_id'] ? 'score_team1' : 'score_team2';
            if ($action->scoring === 'Si' && $data['status'] === 'Exitoso' && $action->value > 0) {
                $game->$column = $game->$column + $action->value;
                $game->saveOrFail();
            }

            return response()->json([
                'id' => $log->id, 'type' => 'success', 'message' => 'Registro guardado',
                'score' => $game->$column,
            ]);
        });
    }
}
