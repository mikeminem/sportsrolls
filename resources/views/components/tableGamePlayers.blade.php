<table class="table table-hover table-responsive">
    <thead>
        <tr>
          <th>#</th>
          <th>Nombre</th>
          <th>Fouls</th>
          <th>Points</th>
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
                <td>{{ $player->number }}</td>
                <td>{{ $player->last_name }}, {{ $player->name }}</td>
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
