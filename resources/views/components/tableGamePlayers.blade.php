<table class="table table-striped table-hover" id="table_" {{ $teamId }}>
    <thead>
        <tr>
            <th>Foto</th>
            <th>#</th>
            <th>Nombre</th>
            <th>Asistencia <br>
                Max <span id="maxPlayers" class="bold">12</span>
            </th>
            <th>Titular <br>
                Max <span id="maxStarter" class="bold">5</span>
            </th>
        </tr>
    </thead>
    <tbody>
        @if(count($players) ==0)
        <tr>
            <td colspan="7">No hay Jugadores registrados</td>
        </tr>
        @endif
        @foreach ($players as $player)
        @if($player->team_id == $teamId)
        <tr id="player-{{ $player->id }}">
            <td>
                @if ($player->photo !== NULL)
                <img class="img" src="{{ asset('players_photos/' . $player->photo) }}" style="width:50px">
                @else
                <img class="img" src="{{ asset('images/No-photo-m.png') }}" style="width:50px">
                @endif

            </td>
            <td><b>{{ $player->number }}</b></td>
            <td>{{ $player->last_name }}, {{ $player->name }}</td>
            <td>
                <div class="form-check-inline">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" value="1" game-id="{{ $gameId }}" data-player-id="{{ $player->id }}" id="attendance-player">
                    </label>
                </div>
            </td>

            <td>
                <div class="form-check-inline">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" value="1" game-id="{{ $gameId }}" data-playerid="{{ $player->id }}" id="starter-player">
                    </label>
                </div>
            </td>

        </tr>
        @endif

        @endforeach

    </tbody>
</table>
