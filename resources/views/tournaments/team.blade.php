@extends('layouts.app')

@section('title',  $tournament->title . ' - Resultados' )
@section('description', $tournament->title ." | " . $tournament->summary . " | " . config('app.name') )
@section('url',  url('tournament_info/' . $tournament->slug) )
@section('keywords', $tournament->title ." | " . $tournament->summary . " | " . config('app.name') )
@section('image', asset('tournaments/' . $tournament->user->league->logo1 ) )


@section('content')

@include('components.menuTournament', ['active' => 'teams'])

<div class="container">
    <div class="row mb">

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-12">

                            <div class="row">
                                <div class="col-md-12 text-center">
                                    @if( $team->logo )
                                        <img class="img" src="{{ asset('team_logo/' . $team->logo) }} " style="width:240px">
                                    @else
                                        <img class="img" src="{{ asset('images/_shield.png') }} " style="width:240px">
                                    @endif
                                    <h2>{{ $team->name }}</h2>

                                    <!-- Nav tabs -->
                                    <ul class="nav nav-pills nav-justified">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#home">Resultados</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#menu1">Jugadores</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#menu2">Estadísticas</a>
                                    </li>
                                    </ul>

                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                    <div class="tab-pane container active" id="home">
                                        @include('components.listGames', ['games' => $games])
                                    </div>
                                    <div class="tab-pane container fade" id="menu1">
                                        @include('components.listPlayers', ['players' => $players])
                                    </div>
                                    <div class="tab-pane container fade" id="menu2">
                                        <p>
                                            <span class="badge badge-primary">Sección no disponible</span>
                                        </p>
                                    </div>
                                    </div>
                            </div>

                            <div class="fb-share-button" data-href="{{ url('tournament_info/' . $tournament->slug . '/' . $category->name . '/' . $team->slug) }}" data-layout="button_count" data-size="large"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fwww.deportezacatecas.com%2Ftournament_info%2FLiga-de-Veteranos-de-Guadalupe&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Compartir</a></div>
                            <a class="btn btn-primary whatsapp"
                                href="whatsapp://send?text={{ url('tournament_info/'   . $tournament->slug . '/' . $category->name . '/' . $team->slug) }}"
                            target="_blank"><span class="fa fa-whatsapp"></span> Compartir </a>
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

    });

</script>
@endsection
