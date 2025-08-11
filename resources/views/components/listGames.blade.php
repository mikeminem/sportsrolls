<div class="table-responsive text-left">
    <table class="table table-hover" >
        <thead>
        <tr>
            <th>Fecha</th>
            <th>Equipo</th>
            <th>Marcado</th>
            <th>Resultado</th>
        </tr>
        </thead>
        <tbody>

             @foreach($games as $game)
                <tr>
                    <td>{{ $game->game_day }}</td>
                    <td>{{ $game->team2 }}</td>
                    <td>{{ $game->score1 }} - {{ $game->score2 }}</td>
                    <td>
                        @if ($game->result ==='Ganado')
                            <span class="badge badge-success">{{ $game->result }}</span>
                        @else
                            <span class="badge badge-danger">{{ $game->result }}</span>
                        @endif

                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
</div>
