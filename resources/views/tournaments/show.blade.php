@extends('layouts.app')

@section('title', config('app.name') ." :: " .  $tournament->title )
@section('description',   $tournament->title ." " . $tournament->summary . " - " . config('app.name') )

@section('content')
<div class="jumbotron rounded-0 text-center my-1">
    <h1 >{{ $tournament->title }}</h1>
    <p>{{ $tournament->summary }}</p>
  </div>


<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs">
                        
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#menu1">Próximos Juegos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#menu2">Resultados</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#menu3">Tabla de Posiciones</a>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#menu4">Equipos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#menu5">Organizador</a>
                        </li> --}}
                        
                    </ul>
                    
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane container active" id="menu1">
                            <div class="row">
                                <div class="col-md-12">
                                    <h1>Próximos juegos</h1>
                                </div>
                            </div>

                            <p>
                                @php
                                    $i=0;
                                @endphp
                                @foreach ($tournament->journeys as $journey)

                                    @if( ($journey->status=="Abierto") && (($journey->is_deleted==0)) )

                                    <h3 class="text-center">{{ $journey->name }}</h3>
                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped" id="table">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th>Fecha / Hora</th>
                                                        <th>Equipo Casa</th>
                                                        <th>-</th>
                                                        <th>Equipo Visitante</th>
                                                        <th>Rama</th>
                                                        <th>Categoria</th>
                                                        
                                                    </tr>
                                                </thead>
                                                
                                                <tbody>
                                                    @foreach ($games as $game)

                                                        @if($game->journey_id == $journey->id)
                                                            <tr class="{{ $game->status === "Suspendido" ? 'table-danger' : '' }}" >
                                                                <td>{{ $game->game_day }} / {{ $game->game_time }}</td>
                                                                <td class="text-right">{{ $game->team1 }}</td>
                                                                <th>VS </th>
                                                                <td>{{ $game->team2 }}</td>
                                                                <td>{{ $game->branch }}</td>
                                                                <td>{{ $game->category }}</td>
                                                            </tr>        
                                                        @endif
                                                        
                                                    @endforeach
                                                    
                            
                                                </tbody>
                                            </table>
                                        </div>

                                        <p>
                                            <h4> Nota: {{ $journey->notes }}</h4>
                                        </p>

                                        @php
                                            $i++;
                                        @endphp    
                                    @endif    
                                @endforeach

                                @if($i==0)
                                    <div class="alert alert-danger">
                                        <strong>Nota!</strong> no se encontraron juegos agendados
                                    </div>    
                                @endif
                                
                            </p>


                        </div>
                        <div class="tab-pane container fade" id="menu2">

                            <div class="row">
                                <div class="col-md-12">
                                    <h1>Resultados de los juegos</h1>
                                </div>
                            </div>

                            <div class="row">
                                @foreach ($tournament->journeys as $journey)

                                    <div class="col-md-4">
                                        <p>
                                            <h3> {{ $journey->name }}</h3>

                                            @foreach ($categories as $category)
                                                <h5>Categoría {{ $category->name}}</h5>
                                                <div class="games">
                                                    @php 
                                                        $i=0;
                                                    @endphp
                                                @foreach ($games as $game)
                                                
                                                        @if (($game->journey_id == $journey->id) && ($game->status == "Finalizado"))
                                                            
                                                            @php 
                                                                $i++;
                                                            @endphp
                                                                    
                                                            @if ($game->category_id == $category->id)
                                                                <div class="match p-2">    
                                                                    @if(($game->score_team1 > $game->score_team2 ))
                                                                        <b class="win">{{ $game->team1}} {{ $game->score_team1}}</b> <br>
                                                                        {{ $game->team2}} {{ $game->score_team2}} 
                                                                    @elseif ($game->score_team1 < $game->score_team2)
                                                                        {{ $game->team1}} {{ $game->score_team1}} <br />
                                                                        <b class="win">{{ $game->team2}} {{ $game->score_team2}}</b>
                                                                    @else
                                                                        {{ $game->team1}} {{ $game->score_team1}} <br>
                                                                        {{ $game->team2}} {{ $game->score_team2}} 
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        @endif    
                                                @endforeach
                                                </div>
                                                <br />
                                            @endforeach

                                            @if ($i==0)
                                            <div class="alert alert-danger">
                                                <strong>Nota!</strong> no se encontraron resultados en esta jornada
                                              </div>
                                            @endif
                                            
                                        </p>

                                    </div>
                                @endforeach
                            </div>
                            
                            
                        </div>
                        <div class="tab-pane container fade" id="menu3">
                            <div class="row">
                                <div class="col-md-12">
                                    <h1>Tabla de Posiciones</h1>
                                </div>
                            </div>

                            
                            @foreach ($categories as $category)
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3>Categoría {{ $category->name }}</h3>


                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped" id="table">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th>Lugar</th>
                                                        <th>Equipo</th>
                                                        <th>JJ</th>
                                                        <th>JG</th>
                                                        <th>JP</th>
                                                        <th>PAF</th>
                                                        <th>PEC</th>
                                                        <th>Dif</th>
                                                    </tr>
                                                </thead>
                            
                                                <tbody>
                                                    @php
                                                        $lugar = 1;
                                                    @endphp
                                                    @foreach ( $scores[$category->id] as $score )
                                                        <tr>
                                                            <td>{{ $lugar }}</td>
                                                            <td>{{ $score->team }}</td>
                                                            <td class="text-right">{{ $score->JJ }}</td>
                                                            <td class="text-right">{{ $score->JG }}</td>
                                                            <td class="text-right">{{ $score->JP }}</td>
                                                            <td class="text-right">{{ $score->PAF }}</td>
                                                            <td class="text-right">{{ $score->PEC }}</td>
                                                            <td class="text-right">{{ $score->DIF }}</td>
                                                        </tr>

                                                        @php
                                                        $lugar++;
                                                    @endphp
                                                                                                
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        </div>
    
                                    </div>
                                </div>    
                            @endforeach
                            



                        </div>
                        {{-- <div class="tab-pane container fade" id="menu4">Equipos Registrados</div>
                        <div class="tab-pane container fade" id="menu5">Comité organizador</div> --}}
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    jQuery(document).ready(function($){
        
    });

</script>
@endsection