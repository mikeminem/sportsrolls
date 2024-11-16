@extends('layouts.app')

@section('title',  $tournament->title . ' - ' . __('Front Page')  )
@section('description', $tournament->title ." | " . $tournament->summary . " | " . config('app.name') )
@section('url',  url('tournament_info/' . $tournament->slug) )
@section('keywords', $tournament->title ." | " . $tournament->summary . " | " . config('app.name') )


@section('content')

@include('components.menuTournament', ['active' => 'front-page'])

<div class="container">
    <div class="row">
        <div class="col-md-8">

            {{-- Goleadores --}}
            <div class="card mb-2">
                <div class="card-header bg-dark text-light">{{ __('Goleadores')}}</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 m-1">
                            @foreach ($categories as $category )
                                <h4>{{ __('Category') }} {{ $category->name }}</h4>
                                <table class="table table-striped text-left">
                                    <thead>
                                        <tr>
                                          <th>Place</th>
                                          <th>Player</th>
                                          <th>Team</th>
                                          <th>Goals</th>
                                        </tr>
                                      </thead>
                                      <tbody>

                                            @switch($category->name)
                                            @case('Primera')
                                                <tr>
                                                    <td>1</td>
                                                    <td>Jose Rosales</td>
                                                    <td>United FC</td>
                                                    <td>10</td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>Noa</td>
                                                    <td>Parientes</td>
                                                    <td>7</td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td>Zylla</td>
                                                    <td>Real FC</td>
                                                    <td>6</td>
                                                </tr>

                                                <tr>
                                                    <td>4</td>
                                                    <td>Abraham Sigala</td>
                                                    <td>Parientes FC</td>
                                                    <td>6</td>
                                                </tr>
                                                @break
                                            @case('Segunda')
                                                <tr>
                                                    <td>1</td>
                                                    <td>Erik Lopez </td>
                                                    <td>Pamanes</td>
                                                    <td>7</td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>Miguel Calderon</td>
                                                    <td>Fosiles FC</td>
                                                    <td>5</td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td>Pedro Chavez</td>
                                                    <td>Ojocaliente</td>
                                                    <td>5</td>
                                                </tr>
                                                @break
                                            @default
                                            <tr>
                                                <td colspan="4">There aren't records in this section</td>
                                            </tr>
                                            @endswitch
                                                
                                 
                                        </tbody>
                                </table>
                                
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>

            {{-- jornada de la semana --}}
            @foreach ($journeys as $journey)
                
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="title"><b> {{ $journey->name }}</b></h3>
                            </div>
                        </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        @foreach ($game_days as $game_day)
                                            @php
                                                $date = date_create($game_day);
                                            @endphp

                                            <div class="col-md-12 text-center m-2">
                                                <h4>
                                                    {{ date_format($date, 'l jS \o\f F Y') }}
                                                </h4>
                                            </div>


                                            @foreach ($games as $game)
                                                @if($game->journey_id == $journey->id 
                                                    && $game->game_day == $game_day )
                                                    
                                                    
                                                    
                                                    @include('components/btnGame')
                                                @endif
                                            @endforeach
                                                
                                        @endforeach
                                      
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">                       
                                    <b>Nota: {{ $journey->notes ?? 'Sin incidencias' }}</b>   
                                </div>
                            </div>
                    </div>
                </div>   
                    
            @endforeach



            {{-- noticias y contenidos web --}}

            @if (count($webContents) ==0)
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                ¡No se ha publicado contenido, regresa pronto!
                            </div>
                        </div>

                    </div>
                </div>
            @else
                @foreach ($webContents as $webContent)
                    <a href="{{ asset('webpage/' . $webContent->slug ) }}" alt="{{ $webContent->title }}" title="{{ $webContent->title }}" class="btnDZ">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        @if ($webContent->image)
                                            <img class="rounded img-fluid" 
                                            src="{{ asset('web_pages/' . $webContent->image) }} "
                                            alt="{{ $webContent->title }}" 
                                            title="{{ $webContent->title }}"
                                            /><br>
                                        @endif
                                        <h3><b>{{ $webContent->title }}</b></h3>
                                        <b>{{ $webContent->description }}</b>   
                                    </div>
                                </div>
            
                            </div>
                        </div>   
                    </a>     
                @endforeach
            
            @endif
        </div>

        <div class="col-md-4">
            <div class="card mb-2">
                <div class="card-header bg-dark text-light">{{ __('Tournament Leads')}}</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 m-1">
                            @foreach ($categories as $category )
                                <h4>{{ __('Category') }} {{ $category->name }}</h4>
                                <ul class="list-group list-group-flush mb-2">
                                    @php
                                        $place = 1;
                                    @endphp
                                    @foreach ( $scores[$category->id] as $score )
                                        <a href="#">
                                            <li class="list-group-item d-flex justify-content-between align-items-left">
                                                    @if( $score->logo )
                                                        <img class="img" src="{{ asset('team_logo/' . $score->logo) }} " style="width:40px">
                                                    @else
                                                        <img class="img" src="{{ asset('images/_shield.png') }} " style="width:40px">
                                                    @endif
                                                    {{ $score->team }}
                                                    <span class="badge badge-light badge-pill">{{ $place }}</span>
                                                </li>    
                                        </a>
                                        @php
                                            $place++;
                                        @endphp
                                    @endforeach
                                </ul>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row text-right">
                        <div class="col-md-12">
                            <div class="fb-share-button" 
                                data-href="{{ url('tournament_info/' . $tournament->slug) }}" 
                                data-layout="button_count" data-size="large">
                                    <a target="_blank" 
                                        href="{{ url('tournament_info/' . $tournament->slug) }}" 
                                        class="fb-xfbml-parse-ignore">Share
                                    </a>
                            </div>
                            <a class="btn btn-primary whatsapp" 
                                href="whatsapp://send?text={{ url('tournament_info/' . $tournament->slug) }}"  
                            target="_blank"><span class="fa fa-whatsapp"></span> Share </a>   
                        </div>
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

        $('#filterCategories').change( function () {
            // const branch = $('#branch option:selected').val();

            // if (branch === '') {
            //     $.notify("Selecciona una Rama", "error");
            //     return;
            // }

            $("#filterCategories option:selected").each(function () {
                categoryId=$(this).val();

                $("#filterTeams").html('<option value="">Selecciona una cateogría</option>');

                getTeams("filterTeams",categoryId);
                // getTeamsByCategoryAndBranch("team1_id", categoryId, branch );
                // getTeamsByCategoryAndBranch("team2_id", categoryId, branch );
                
            });
        })

        $('#btnBuscar').click( function () {
            alert('Vamos a buscar')
        })
    });

</script>
@endsection