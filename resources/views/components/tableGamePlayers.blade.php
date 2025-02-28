<table class="table table-striped table-hover" id="table_"{{ $teamId }}>
    <thead>
        <tr>
          <th>Foto</th>
          <th>#</th>
          <th>Jugador</th>
          <th> - </th>
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
                      {{-- <button class="btn btn-primary float-right gameActions" id="btnAction" ><span class="fa fa-plus"></span></button> --}}
                </td>
              </tr>
          @endif

        @endforeach

      </tbody>
</table>
