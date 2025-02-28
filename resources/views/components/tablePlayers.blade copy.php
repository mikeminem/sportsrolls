<div class="table-responsive">
    <table class="table table-hover" id="db_table_"{{ $teamId }}>
        <thead>
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Faltas</th>
            <th>
                <button class="btn btn-primary">Agregar Jugador</button>
            </th>
        </tr>
        </thead>
        <tbody>
            @foreach ($players as $player)
            @if($player->team_id == $teamId)
                <tr id="player-{{ $player->id }}">
                  <td><b>{{ $player->number }}</b></td>
                  <td>
                    @if ($player->photo !== NULL)
                          <img class="img" src="{{ asset('players_photos/' . $player->photo) }}" style="width:50px">
                      @else
                          <img class="img" src="{{ asset('images/No-photo-m.png') }}" style="width:50px">
                      @endif
                    {{ $player->last_name }}, {{ $player->name }}</td>
                  <td>
                        {{-- <button class="btn btn-primary float-right gameActions" id="btnAction" ><span class="fa fa-plus"></span></button> --}}
                  </td>
                  <td> - </td>
                </tr>

            @endif

          @endforeach

        </tbody>
    </table>
</div>
