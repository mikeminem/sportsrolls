@extends('layouts.app')

@section('title', 'Detalles del Juego')
@section('description',  "Detalles del Juego - ")

@section('content')

@include('components.menuTournament', ['active' => 'results'])

<div class="container">
    <div class="row py-1">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header text-center">
                    <h2 id="title">Detalles del Juego</h2>
                </div>
                <div class="card-body">
                    @if($game->status !== 'Finalizado')
                        <div class="row">
                            <div class="col-9 text-left">
                                Lugar: {{ $game->stadium }} |
                                Fecha: {{ $game->game_day }} {{ $game->game_time }}
                                Tipo de juego: {{ $game->type }}
                            </div>
                            <div class="col-3 text-right">
                                <button class="btn btn-success" id="btnDataEntry">
                                    <span class="fa fa-edit"></span> Capturar Actividad
                                </button>

                                <button class="btn btn-primary" id="btnFinishDataEntry" style="display: none">
                                     <span class="fa fa-sign-out"></span> Finalizar
                                </button>

                            </div>
                        </div>
                    @else
                    <div class="row text-center">
                        <div class="col-12">
                            {{ $game->status }}  {{ $game->category }}
                            {{ isset($game->group) ? ' - ' . $game->group : '' }}
                            {{ Str::upper($game->type ) }}
                            <br />
                            {{ $game->stadium }}
                        </div>
                    </div>
                    @endif

                    <div class="row" id="score">
                        <div class="col-6 text-left">

                            <div class="row align-items-center">
                                <div class="col-6 text-left">
                                    @if( $game->t1logo )
                                        <img class="img-fluid" src="{{ asset('team_logo/' . $game->t1logo) }}" width="80%" >
                                    @else
                                        <img class="img-fluid" src="{{ asset('images/_shield.png') }}" width="80%" >
                                    @endif
                                </div>

                                <div class="col-6 text-right">
                                    <h3><span id="score_team1">{{ $game->score_team1 }}</span></h3>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 text-center">
                                    <h4>{{ $game->team1 }}</h4>
                                </div>
                            </div>

                            <div class="row text-left">
                                <div class="col-12">
                                    @include('components.tableGamePlayers', ['teamId' => $game->team1_id])

                                </div>
                            </div>

                        </div>

                        <div class="col-6 text-right">
                            <div class="row align-items-center">
                                <div class="col-6 text-left">
                                    <h3><span id="score_team2">{{ $game->score_team2 }}</span></h3>
                                </div>

                                <div class="col-6 text-center">
                                    @if( $game->t2logo )
                                        <img class="img-fluid" src="{{ asset('team_logo/' . $game->t2logo) }}" width="80%" >
                                    @else
                                        <img class="img-fluid" src="{{ asset('images/_shield.png') }}" width="80%" >
                                    @endif

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 text-center">
                                    <h4>{{ $game->team2 }}</h4>
                                </div>
                            </div>



                            <div class="row text-left">
                                <div class="col-12">
                                    @include('components.tableGamePlayers', ['teamId' => $game->team2_id])

                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="dashboard" style="display: none">
                        <div class="row mb-3"  >
                            <div class="col-4">
                                <div class="row">
                                    <div class="col-8  text-left">
                                        @if( $game->t1logo )
                                            <img class="img-fluid" src="{{ asset('team_logo/' . $game->t1logo) }}" width="60px" >
                                        @else
                                            <img class="img-fluid" src="{{ asset('images/_shield.png') }}" width="60px" >
                                        @endif

                                        {{ $game->team1 }}
                                    </div>

                                    <div class="col-4 text-right align-items-center">
                                        <span class="crono">00</span>
                                    </div>

                                </div>


                            </div>
                            <div class="col-4 text-center align-items-center">
                                    <h1 class="crono">00:00</h1>
                                    <button class="btn btn-danger"> <span class="fa fa-stop"></span></button>
                                    <button class="btn btn-primary"> <span class="fa fa-play"></span></button>
                            </div>
                            <div class="col-4 text-right">
                                <div class="row">
                                    <div class="col-4 text-right align-items-center">
                                        <span class="crono">00</span>
                                    </div>
                                    <div class="col-8 text-right align-items-center">
                                        {{ $game->team2 }}

                                        @if( $game->t2logo )
                                            <img class="img-fluid" src="{{ asset('team_logo/' . $game->t2logo) }}" width="60px" >
                                        @else
                                            <img class="img-fluid" src="{{ asset('images/_shield.png') }}" width="60px" >
                                        @endif
                                    </div>

                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                @include('components.tablePlayers',['teamId' => $game->team1_id])
                            </div>
                            <div class="col-6">
                                @include('components.tablePlayers',['teamId' => $game->team2_id])

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 text-center">
                                <h2>REGISTRO DEL JUEGO</h2>

                                <div class="table-responsive">
                                    <table class="table table-hover" id="table-game-log"">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>EQUIPO</th>
                                            <th class="text-left">JUGADOR</th>
                                            <th>ACCIÓN</th>
                                            <th>PERIODO</th>
                                            <th>TIEMPO</th>
                                            <th>OPCIONES</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr id="no-activity"">
                                                <td colspan="7" class="text-centeer">
                                                    <b>No hay registro del juego aún</b>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>

                            </div>

                    </div>

                </div> {{-- end of the card body --}}
            </div> {{-- end of the card --}}
        </div>
    </div>
</div>

<!-- codeModal Form -->
<div class="modal" id="codeModal">
    <div class="modal-dialog">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Código de acceso</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>


        <!-- Modal body -->
        <form id="codeForm">

            <div class="modal-body">
            <h5>
                Ingresa el código de acceso para habilitar la captura de datos del juego, si no tienes el código de acceso, solicitalo al administrador de la liga.
            </h5>
            <p><div class="form-group">
                <label for="access_code">Código de Acceso</label>
                <input type="text" class="form-control" name="access_code" id="access_code" maxlength="6" required>
            </div></p>

            </div>
            {{ csrf_field() }}

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-lg">
                    <span class="fa fa-check"></span>
                    Aceptar
                </button>
                <button type="button" class="btn btn-primary btn-lg" class="close" data-dismiss="modal">
                    <span class="fa fa-remove"></span>
                    Cancelar
                </button>
            </div>
        </form>

      </div>
    </div>
  </div>
<!-- End codeModal Form -->

{{-- Players list Modal --}}
<div class="modal" id="PlayersModal">
    <div class="modal-dialog ">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Listado de Jugadores</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>


        <!-- Modal body -->
        {{-- <form id="PlayersForm"> --}}
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <table class="table table-hover" id="table-players">
                            <thead>
                                <tr>
                                    <td>#</td>
                                    <td>Foto</td>
                                    <td>Nombre</td>
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>

                    </div>

                </div>

            </div>
            {{ csrf_field() }}

            <!-- Modal footer -->
            <div class="modal-footer">
                {{-- <button type="submit" class="btn btn-primary btn-lg">
                    <span class="fa fa-check"></span>
                    Agregar
                </button> --}}
                <button type="button" class="btn btn-primary btn-lg" class="close" data-dismiss="modal">
                    <span class="fa fa-remove"></span>
                    Cerrar
                </button>
            </div>
        {{-- </form> --}}

      </div>
    </div>
  </div>
{{-- End Players list modal --}}


{{-- Activity list Modal --}}
<div class="modal" id="ActionsModal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Registrar acción del Jugador</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>


        <!-- Modal body -->
        {{-- <form id="ActionForm"> --}}

            <div class="modal-body">
                <div id="activity">
                    @foreach($actions as $action)
                        <input type="radio" value="{{ $action->id }}" class="activity" name="action" id="action{{ $action->id }}">
                        <label for="action{{ $action->id }}" >{{ $action->name }}</label> <br>
                    @endforeach
                </div>

            </div>
            {{ csrf_field() }}

            <!-- Modal footer -->
            <div class="modal-footer">
                {{-- <button type="submit" class="btn btn-primary btn-lg">
                    <span class="fa fa-check"></span>
                    Agregar
                </button> --}}
                <button type="button" class="btn btn-primary btn-lg" class="close" data-dismiss="modal">
                    <span class="fa fa-remove"></span>
                    Cerrar
                </button>
            </div>
        {{-- </form> --}}

      </div>
    </div>
  </div>
{{-- End Activity list Modal --}}


@endsection

@section('js')

<script src="{{ asset('js/jquery.cookie.js') }}"></script>
<script type="text/javascript">

        jQuery(document).ready(function($) {


            let gameEnable = false
            let playersList = []
            let playersList{{ $game->team1_id }} = []
            let playersList{{ $game->team2_id }} = []

            if ( $.cookie('playersList') ) {
                playersList = $.parseJSON($.cookie('playersList'))
                console.log(playersList)
            }

            if ( $.cookie('playersList{{ $game->team1_id  }}') ) {
                playersListB = $.parseJSON($.cookie('playersList{{ $game->team1_id  }}'))
                console.log(playersList{{ $game->team1_id  }})
            }

            if ( $.cookie('playersList{{ $game->team1_id  }}') ) {
                playersListB = $.parseJSON($.cookie('playersList{{ $game->team1_id  }}'))
                console.log(playersList{{ $game->team1_id  }})
            }




            $(document).on("click", "#addPlayer", function (e) {
                e.preventDefault();

                // $('#players-list').empty()
                $('#table-players > tbody').empty()
                //filter players by team-id
                let selectedTeam = parseInt ($(this).attr("team-id") );

                let players = playersList.filter( x =>
                    x.team_id == selectedTeam
                ).forEach( player => $('#table-players').append(
                        '<tr id="player-'+ player.id +'">'
                            + '<td>'+ player.number +'</td>'
                            + '<td><img src=" {{ asset('players_photos/') }}/' + player.photo +'" width="60px height="60px""></td>'
                            + '<td>'+ player.last_name +', ' + player.name +'</td>'
                            + '<td class="text-right"><button class="btn btn-success" id="btnInGame" player-id="'+ player.id +'" team-id="'+ player.team_id +'"><span class="fa fa-plus"></span> Ingresa</td>'
                        + '</tr>')
                )

                jQuery("#PlayersModal").modal('show');
            });

            //$(document).on("click", "#table-players tr", function (e) {
            $(document).on("click", "#btnInGame", function (e) {
                e.preventDefault();

                let playerId = $(this).attr('player-id')
                let teamId = $(this).attr('team-id')

                //remove player from th list
                $(this).parent().parent().remove();
                //$('#table-game-log > tbody').empty()
                $('#no-activity').remove();

                let players = playersList.filter( x =>
                    x.id == playerId
                ).forEach( player => {
                        $('#table-' + teamId ).append(
                            '<tr>'
                                + '<td>'+ player.number +'</td>'
                                + '<td><img src=" {{ asset('players_photos/') }}/' + player.photo +'" width="60px" height="60px"></td>'
                                + '<td>'+ player.last_name +', ' + player.name +'</td>'
                                + '<td class="text-right">'
                                    + '<button class="btn btn-success mr-1" id="btnAction" player-id="'+ player.id +'" team-id="'+ player.team_id +'"><span class="fa fa-arrow-up"></span> </button>'
                                    + '<button class="btn btn-danger" id="btnChange" player-id="'+ player.id +'" team-id="'+ player.team_id +'"><span class="fa fa-retweet"></span> </button>'
                                +'</td>'
                            + '</tr>')


                        //table-game-log
                        $('#table-game-log').prepend(
                            '<tr>'
                                + '<td>'+ player.number +'</td>'
                                + '<td>'+ player.team_id +'</td>'
                                + '<td class="text-left"><img src=" {{ asset('players_photos/') }}/' + player.photo +'" width="60px" height="60px"> '+ player.last_name +', ' + player.name +'</td>'
                                + '<td>INICIA</td>'
                                + '<td>-</td>'
                                + '<td>00:00</td>'
                                + '<td>'
                                    + '<button class="btn btn-danger" id="btnDeleteLog" player-id="'+ player.id +'" team-id="'+ player.team_id +'"><span class="fa fa-remove"></span> </button>'
                                +'</td>'
                            + '</tr>')
                        }

                )


                //add it to the log game table
                //table_game_log


                //remove the player from the main array
                playersList = playersList.filter( x =>
                    x.id != playerId )
                console.log(playersList)
                $.cookie('playersList', JSON.stringify(playersList))

            });

            $(document).on("click", "#btnAction", function (e) {
                jQuery("#ActionsModal").modal('show');
            })


            $(document).on("click", "#btnDataEntry", function (e) {
                e.preventDefault();
                jQuery("#codeModal").modal('show');
            });

            $(document).on("click", "#btnFinishDataEntry", function (e) {
                $.removeCookie('enableGame');
                $.removeCookie('playersList');
                enableGame(false);
                $(this).hide();
                $('#btnDataEntry').show();
            });

            $(document).on("submit", "#codeForm", function (e) {
                e.preventDefault();

                $.ajax({
                    type: "POST",
                    url: "{{ url('game_data_entry') }}",
                    dataType: "json",
                    data: $(this).serialize(),
                    success: function (data) {

                        if (data.access === 'true') {

                            enableGame(true);
                            $.cookie('enableGame', true);
                            playersList = {!! $players !!}

                            $.cookie('playersList', JSON.stringify(playersList))

                            $("#codeForm").trigger('reset');
                            jQuery.noConflict();
                            jQuery("#codeModal").modal('toggle');
                        }

                        $('#access_code').val('');
                        $.notify(data.message, data.type);

                    },
                    error:function(){
                        $.notify("Hubo un error al intentar habilitar el juego!", "error");
                    },
                });

                return false;
            });

            //init the page variables
            enableGame(gameEnable);

            function enableGame(toggleEnable) {

                if ( $.cookie('enableGame') ) {
                    toggleEnable = $.cookie('enableGame');
                }


                if (toggleEnable == false) {

                    $('#dashboard').hide();
                    $('#score').show();
                    $('#title').html('Detalles del juego');

                    $('#btnDataEntry').show();
                    $('#btnFinishDataEntry').hide();

                } else {

                    $('#dashboard').show();
                    $('#score').hide();
                    $('#title').html('Dashboard del juego');

                    $('#btnDataEntry').hide();
                    $('#btnFinishDataEntry').show();

                }
            }

        });

</script>
@endsection
