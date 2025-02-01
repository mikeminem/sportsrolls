<table class="table table-striped table-hover">
    <thead>
        <tr>
          <th>Foto</th>
          <th>#</th>
          <th>Jugador</th>
          <th>Asistencia</th>
          <th>Faltas</th>
          <th>Puntos</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @if(count($players) ==0)
            <tr>
              <td colspan="5">No hay Jugadores</td>
            </tr>
        @endif
        @foreach ($players as $player)
          @if($player->team_id == $teamId)
              <tr>
                <td>
                    @if ($player->photo !== NULL)
                        <img class="img" src="{{ asset('players_photos/' . $player->photo) }}" style="width:60px">
                    @else
                        <img class="img" src="{{ asset('images/No-photo-m.png') }}" style="width:60px">
                    @endif

                </td>
                <td><b>{{ $player->number }}</b></td>
                <td>{{ $player->last_name }}, {{ $player->name }}</td>
                <td class="text-center"><span class="fa fa-check"></span></td>
                <td class="text-center">0</td>
                <td class="text-center">0</td>
                <td>
                      <button class="btn btn-primary float-right gameButton" id="btnAttendance" data-id="{{ $player->id }}"><span class="fa fa-check"></span></button>
                      <button class="btn btn-primary float-right gameActions" id="btnAction" ><span class="fa fa-play"></span></button>
                </td>
              </tr>
          @endif

        @endforeach

      </tbody>
</table>
