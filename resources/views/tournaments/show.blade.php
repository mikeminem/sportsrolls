@extends('layouts.app')

@section('title', config('app.name') ." :: " .  $tournament->title )
@section('description', $tournament->title ." | " . $tournament->summary . " | " . config('app.name') )
@section('url',  url('tournament_info/' . $tournament->slug) )
@section('keywords', $tournament->title ." | " . $tournament->summary . " | " . config('app.name') )


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

                    <div class="row text-right mb-3">
                        <div class="col-md-12">
                            <div class="fb-share-button" data-href="{{ url('tournament_info/' . $tournament->slug) }}" data-layout="button_count" data-size="large"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fwww.sportroles.com%2Ftournament_info%2FLiga-de-Veteranos-de-Guadalupe&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Compartir</a></div>

                            <a class="btn btn-primary whatsapp" href="whatsapp://send?text={{ url('tournament_info/' . $tournament->slug) }}"       data-action="share/whatsapp/share"  
                            target="_blank"><span class="fa fa-whatsapp"></span> Compartir </a>   
                        </div>
                    </div>

                    

                    <!-- Nav tabs -->
                    <ul class="nav nav-pills nav-justified">
                        
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#menu1"><span class="fa fa-calendar"></span> Próximos Juegos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#menu2"><span class="fa fa-list"></span> Resultados</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#menu3"><span class="fa fa-list-ol"></span> Tabla de Posiciones</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#menu4">Equipos</a>
                        </li>
                        <!--li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#menu5">Organizador</a>
                        </li-->
                        
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

                                    @if( ($journey->status=="Abierto") && 
                                    ($journey->show_public=="Si") && 
                                    ($journey->is_deleted==0) )

                                    <h3 class="text-center">{{ $journey->name }}</h3>
                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped" id="table">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th> Hora</th>
                                                        <th>Equipo Casa</th>
                                                        <th>-</th>
                                                        <th>Equipo Visitante</th>
                                                        <th>Rama / Categiría</th>
                                                        <th>Tipo</th>
                                                        
                                                    </tr>
                                                </thead>
                                                
                                                <tbody>
                                                    @php
                                                        $day = "";
                                                        $change=1;
                                                    @endphp
                                                    @foreach ($games as $game)
                                                        
                                                        @if($game->journey_id == $journey->id)
                                                            

                                                            @if ($change ==1)
                                                                @php
                                                                    $day = $game->game_day;
                                                                @endphp
                                                                <tr >   
                                                                    <td colspan="6" class="text-center table-success"><h4> 
                                                                        @php
                                                                            $dayName = date('D', strtotime($day));
                                                                            $diassemana = array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
                                                                            $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
 
                                                                            echo $diassemana[date('w', strtotime($day))]." ".date('d', strtotime($day))." de ".$meses[date('n', strtotime($day))-1]. " del ".date('Y', strtotime($day)) ;
                                                                        @endphp
                                                                        
                                                                    </h4></td>
                                                                </tr>   
                                                            @endif     

                                                            @if($day == $game->game_day)

                                                                @php
                                                                    $change=0;
                                                                @endphp
                                                                
                                                                <tr class="{{ $game->status === "Suspendido" ? 'table-danger' : '' }}" >
                                                                    <td>{{ $game->game_time }}</td>
                                                                    <td class="text-right">{{ $game->team1 }}</td>
                                                                    <th>VS </th>
                                                                    <td>{{ $game->team2 }}</td>
                                                                    <td>{{ $game->branch }} / {{ $game->category }}</td>
                                                                    <td><span class="badge badge-danger"> {{ $game->type }} </span></td>
                                                                </tr>
                                                            @else
                                                                @php
                                                                    $day = $game->game_day;
                                                                    $change =0;
                                                                @endphp

                                                                <tr >   
                                                                    <td colspan="6" class="text-center table-success"><h4> 
                                                                        @php
                                                                            $dayName = date('D', strtotime($day));
                                                                            $diassemana = array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
                                                                            $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

                                                                            echo $diassemana[date('w', strtotime($day))]." ".date('d', strtotime($day))." de ".$meses[date('n', strtotime($day))-1]. " del ".date('Y', strtotime($day)) ;
                                                                        @endphp
                                                                        
                                                                    </h4></td>
                                                                </tr>   

                                                                <tr class="{{ $game->status === "Suspendido" ? 'table-danger' : '' }}" >
                                                                    <td>{{ $game->game_time }}</td>
                                                                    <td class="text-right">{{ $game->team1 }}</td>
                                                                    <th>VS </th>
                                                                    <td>{{ $game->team2 }}</td>
                                                                    <td>{{ $game->branch }} / {{ $game->category }}</td>
                                                                    <td> <span class="badge badge-danger"> {{ $game->type }} </span></td>
                                                                </tr>
                                                                
                                                            @endif
                                                        @endif
                                                        
                                                    @endforeach
                                                    
                            
                                                </tbody>
                                            </table>
                                        </div>

                                        <p>
                                             NOTA: {{ $journey->notes }}
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

                                    @if (($journey->is_deleted == 0) && 
                                         ($journey->show_public == "Si") )
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
                                    @endif
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
                                @if ($category->show_stats == "Si")
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3>Categoría {{ $category->name }}</h3>


                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped" id="table">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th width="20px">Lugar</th>
                                                        <th width="200px">Equipo</th>
                                                        <th width="20px">JJ</th>
                                                        <th width="20px">JG</th>
                                                        @if ($tournament->draw_points>-1)
                                                            <th width="20px">JE</th>
                                                        @endif
                                                        <th width="20px">JP</th>
                                                        <th width="20px">PF</th>
                                                        <th width="20px">PC</th>
                                                        <th width="20px">DIF</th>
                                                        @if ($tournament->enable_points_system=="Si")
                                                            <th width="20px">PTOs</th>
                                                        @endif
                                                        
                                                    </tr>
                                                </thead>
                            
                                                <tbody>
                                                    @php
                                                        $lugar = 1;
                                                    @endphp
                                                    @foreach ( $scores[$category->id] as $score )
                                                        <tr>
                                                            <td class="text-center">{{ $lugar }}</td>
                                                            <td >{{ $score->team }}</td>
                                                            <td class="text-center ">{{ $score->JJ }}</td>
                                                            <td class="text-center ">{{ $score->JG }}</td>
                                                            @if ($tournament->draw_points>-1)
                                                                <td class="text-center ">{{ $score->JE }}</td>
                                                            @endif
                                                            <td class="text-center ">{{ $score->JP }}</td>
                                                            <td class="text-center ">{{ $score->PAF }}</td>
                                                            <td class="text-center ">{{ $score->PEC }}</td>
                                                            <td class="text-center ">{{ $score->DIF > 0 ? '+' : '' }}{{ $score->DIF }}</td>
                                                            @if (isset($score->PUNTOS))
                                                                <td class="text-center ">{{ $score->PUNTOS }}</td>
                                                            @endif                                                                
                                                        </tr>

                                                        @php
                                                        $lugar++;
                                                    @endphp
                                                                                                
                                                    @endforeach

                                                </tbody>
                                            </table>
                                            <p><b>Fuente: Sport Roles: www.sportroles.com</b></p>
                                        </div>
    
                                    </div>
                                </div>    

                                @endif
                            @endforeach

                        </div>
                        <div class="tab-pane container fade" id="menu4">
                            <div class="row">
                                <div class="col-md-12">
                                    <h1>Equipos Inscritos</h1>
                                </div>
                            </div>

                            
                            <div class="row">
                            @foreach ($categories as $category)
                                @if ($category->show_stats == "Si")
                                    <div class="col-md-6">
                                        <h3>Categoría {{ $category->name }}</h3>


                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped" id="table">
                                                <thead>
                                                    <tr class="text-left">
                                                        <th width="200px">Equipo</th>
                                                    </tr>
                                                </thead>
                            
                                                <tbody>
                                                   
                                                    @foreach ( $teams as $team )
                                                        @if ($team->category_id == $category->id)
                                                            <tr>
                                                                <td >
                                                                    <b>{{ $team->name }}</b> 
                                                                    @if( $team->photo )
                                                                            <br>
                                                                            <img class="img img-fluid" src="{{ asset('team/' . $team->photo) }} " style="width=100%">
                                                                            
                                                                    @endif
                                                                    <!--div class="text-right">

                                                                        <span class="fa fa-heart"></span> 123
                                                                        <span class="fa fa-thumbs-up"></span> 123
                                                                        <span class="fa fa-thumbs-down"></span>34

                                                                    </div-->

                                                                    @if ($team->status == 'Baja')
                                                                    <span class="badge badge-danger">Baja del torneo</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endif
                                                                                                
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        </div>
    
                                    </div>
                                    
                                    @endif
                                @endforeach
                            </div>    

                            
                        </div>
                        {{-- <div class="tab-pane container fade" id="menu5">Comité organizador</div> --}}


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