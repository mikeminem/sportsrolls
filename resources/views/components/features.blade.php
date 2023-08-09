<div class="row mt-5" >
    <div class="col-md-3 d-flex align-items-stretch">
        <div class="card text-center w-100 border-0">
            <div class="card-body">
                <i class="fa fa-trophy feature"></i>
                <br>
                <h1>{{ $tournaments->count() }}</h1>
                @if ($links)
                    <a href="{{ url('tournaments') }}">Torneos Activos</a>
                @else
                    Torneos Activos
                @endif
                
            </div>
          </div>
    </div>

    <div class="col-md-3 d-flex align-items-stretch">
        <div class="card text-center w-100 border-0">
            <div class="card-body">
                <i class="fa fa-group feature"></i>
                <br>
                <h1>{{ $teams->count() }}</h1>
                @if ($links)
                    <a href="{{ url('teams') }}">Equipos Activos</a>
                @else
                    Equipos Activos
                @endif
                
               
            </div>
          </div>
    </div>

    <div class="col-md-3 d-flex align-items-stretch">
        <div class="card text-center w-100 border-0">
            <div class="card-body">
                <i class="fa fa-calendar feature"></i>
                <br>
                <h1>{{ $journeys->count() }}</h1>
                @if ($links)
                    <a href="{{ url('journeys') }}">Jornadas Programadas</a>
                @else
                    Jornadas Programadas
                @endif
                
            </div>
          </div>
    </div>

    <div class="col-md-3 d-flex align-items-stretch">
        <div class="card text-center w-100 border-0">
            <div class="card-body">
                <i class="fa fa-tasks feature"></i>
                <br>
                <h1>{{ $games->count() }}</h1>
                @if ($links)
                    <a href="{{ url('games') }}">Juegos Programados</a>
                @else
                    Juegos Programados
                @endif
               
            </div>
          </div>
    </div>
</div>
<hr>