<div class="row mt-5" >
    <div class="col-md-3 col-sm-6 d-flex align-items-stretch">
        <div class="card text-center w-100 border-0">
            <div class="card-body">
                <i class="fa fa-trophy feature"></i>
                <br>
                <h2>{{ $tournaments->count() }}</h2>
                @if ($links)
                    <a href="{{ url('tournaments') }}">Torneos Activos</a>
                @else
                    {{ __('Tournaments') }}
                @endif
                
            </div>
          </div>
    </div>

    <div class="col-md-3 col-sm-6 d-flex align-items-stretch">
        <div class="card text-center w-100 border-0">
            <div class="card-body">
                <i class="fa fa-group feature"></i>
                <br>
                <h2>{{ $teams->count() }}</h2>
                @if ($links)
                    <a href="{{ url('teams') }}">Equipos Activos</a>
                @else
                {{ __('Teams') }}
                    
                @endif
                
               
            </div>
          </div>
    </div>

    <div class="col-md-3 col-sm-6 d-flex align-items-stretch">
        <div class="card text-center w-100 border-0">
            <div class="card-body">
                <i class="fa fa-calendar feature"></i>
                <br>
                <h2>{{ $journeys->count() }}</h2>
                @if ($links)
                    <a href="{{ url('journeys') }}">Jornadas</a>
                @else
                    Jornadas
                @endif
                
            </div>
          </div>
    </div>

    <div class="col-md-3 col-sm-6 d-flex align-items-stretch">
        <div class="card text-center w-100 border-0">
            <div class="card-body">
                <i class="fa fa-tasks feature"></i>
                <br>
                <h2>{{ $games->count() }}</h2>
                @if ($links)
                    <a href="{{ url('games') }}">Juegos Programados</a>
                @else
                {{ __('Games') }}
                    
                @endif
               
            </div>
          </div>
    </div>
</div>
<hr>