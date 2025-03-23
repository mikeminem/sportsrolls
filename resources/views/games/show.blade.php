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
                                        <span class="score" id="score_{{ $game->team1_id }}">{{ $game->score_team1 }}</span>
                                    </div>

                                </div>


                            </div>
                            <div class="col-4 text-center align-items-center">
                                    <h1 class="crono">00:00</h1>
                                    <button class="btn btn-primary"> <span class="fa fa-cog"></span></button>
                                    <button class="btn btn-danger"> <span class="fa fa-stop"></span></button>
                                    <button class="btn btn-primary"> <span class="fa fa-play"></span></button>
                            </div>
                            <div class="col-4 text-right">
                                <div class="row">
                                    <div class="col-4 text-left align-items-center">
                                        <span class="score" id="score_{{ $game->team2_id }}" >{{ $game->score_team2 }}</span>
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
                    <table class="table">
                        <thead>
                            <tr>
                                <td>Acción</td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <tr colspan="2">
                                    <h3>
                                        <span id="action-player-id">Nombre Player</span>
                                    </h3>
                                </tr>

                            </tr>
                            @foreach($actions as $action)
                                {{-- <input type="radio" value="{{ $action->id }}" class="activity" name="action" id="action{{ $action->id }}">
                                <label for="action{{ $action->id }}" >{{ $action->name }}</label> <br> --}}

                                <tr>
                                    <td>{{ $action->name }}</td>
                                    <td class="text-right">
                                        <button class="btn btn-success" id="btnAddAction"
                                            data-id="{{ $action->id }}"
                                            data-value="{{ $action->value }}"
                                            player-id=""
                                            team-id=""
                                            data-status="Exitoso" > <span class="fa fa-check"></span>  </button>
                                        @if ($action->need_confirmation == 'Si')
                                            <button class="btn btn-danger" id="btnAddAction"
                                                data-id="{{ $action->id }}"
                                                data-value="{{ $action->value }}"
                                                player-id=""
                                                team-id=""
                                                data-status="Fallado" ><span class="fa fa-remove"></span> </button>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>

                </div>

            </div>
            {{ csrf_field() }}

            <!-- Modal footer -->
            <div class="modal-footer">
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
            let playersListA = []
            let playersListB = []


            if ( $.cookie('playersList') ) {
                playersList = $.parseJSON($.cookie('playersList'))
            }

            if ( $.cookie('playersListA') ) {
                playersListA = $.parseJSON($.cookie('playersListA'))
                let teamId = {{ $game->team1_id }}
                playersListA.forEach( player => {

                        let photo =''
                        if (player.photo == null) {
                            photo = '{{ asset('images/') }}/No-photo-m.png'
                        } else {
                            photo = '{{ asset('players_photos/') }}/' + player.photo
                        }

                        $('#table-' + teamId ).append(
                            '<tr id="player-'+ player.id +'">'
                                + '<td class="text-right"><span class="playerNumber">'+ player.number +'</span></td>'
                                + '<td><img src="' + photo +'" width="60px" height="60px"></td>'
                                + '<td>'+ player.last_name +', ' + player.name +'</td>'
                                + '<td class="text-right">'
                                    + '<button class="btn btn-success mr-1" id="btnAction" player-id="'+ player.id +'" team-id="'+ player.team_id +'" player-name="'+ player.name + ' ' + player.last_name +'"><span class="fa fa-arrow-up"></span> </button>'
                                    + '<button class="btn btn-danger" id="btnChange" player-id="'+ player.id +'" team-id="'+ player.team_id +'"><span class="fa fa-arrow-left"></span> </button>'
                                +'</td>'
                            + '</tr>')

                })
            }

            if ( $.cookie('playersListB') ) {
                playersListB = $.parseJSON($.cookie('playersListB'))
                let teamId = {{ $game->team2_id }}
                playersListB.forEach( player => {
                        let photo =''
                        if (player.photo == null) {
                            photo = '{{ asset('images/') }}/No-photo-m.png'
                        } else {
                            photo = '{{ asset('players_photos/') }}/' + player.photo
                        }

                        $('#table-' + teamId ).append(
                            '<tr id="player-'+ player.id +'">'
                                + '<td class="text-right"><span class="playerNumber">'+ player.number +'</span></td>'
                                + '<td><img src="' + photo +'" width="60px" height="60px"></td>'
                                + '<td>'+ player.last_name +', ' + player.name +'</td>'
                                + '<td class="text-right">'
                                    + '<button class="btn btn-success mr-1" id="btnAction" player-id="'+ player.id +'" team-id="'+ player.team_id +'" player-name="'+ player.name + ' ' + player.last_name +'"><span class="fa fa-arrow-up"></span> </button>'
                                    + '<button class="btn btn-danger" id="btnChange" player-id="'+ player.id +'" team-id="'+ player.team_id +'"><span class="fa fa-arrow-left"></span> </button>'
                                +'</td>'
                            + '</tr>')
                    console.log(player.id + player.name)

                })
            }



            $(document).on("click", "#addPlayer", function (e) {
                e.preventDefault();

                // $('#players-list').empty()
                $('#table-players > tbody').empty()
                //filter players by team-id
                let selectedTeam = parseInt ($(this).attr("team-id") );

                let players = playersList.filter( x =>
                    x.team_id == selectedTeam
                ).forEach( player => {

                    let photo =''
                    if (player.photo == null) {
                        photo = '{{ asset('images/') }}/No-photo-m.png'
                    } else {
                        photo = '{{ asset('players_photos/') }}/' + player.photo
                    }

                    $('#table-players').append(
                        '<tr id="player-'+ player.id +'">'
                            + '<td class="text-right"><span class="playerNumber">'+ player.number +'</span></td>'
                            + '<td><img src="' + photo + '" width="60px height="60px""></td>'
                            + '<td>'+ player.last_name +', ' + player.name +'</td>'
                            + '<td class="text-right"><button class="btn btn-success" id="btnInGame" player-id="'+ player.id +'" team-id="'+ player.team_id +'"><span class="fa fa-plus"></span> Ingresa</td>'
                        + '</tr>')

                    // $('#table-players').append(
                    //     '<tr id="player-'+ player.id +'">'
                    //         + '<td>'+ player.number +'</td>'
                    //         + '<td><img src=" {{ asset('players_photos/') }}/' + player.photo +'" width="60px height="60px""></td>'
                    //         + '<td>'+ player.last_name +', ' + player.name +'</td>'
                    //         + '<td class="text-right"><button class="btn btn-success" id="btnInGame" player-id="'+ player.id +'" team-id="'+ player.team_id +'"><span class="fa fa-plus"></span> Ingresa</td>'
                    //     + '</tr>')
                })

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

                let player = playersList.filter( x =>
                    x.id == playerId
                )

                let players = playersList.filter( x =>
                    x.id == playerId
                ).forEach( player => {

                        let photo =''
                        if (player.photo == null) {
                            photo = '{{ asset('images/') }}/No-photo-m.png'
                        } else {
                            photo = '{{ asset('players_photos/') }}/' + player.photo
                        }

                        $('#table-' + teamId ).append(
                            '<tr id="player-'+ player.id +'">'
                                + '<td class="text-right"><span class="playerNumber">'+ player.number +'</span></td>'
                                + '<td><img src="' + photo + '" width="60px" height="60px"></td>'
                                + '<td>'+ player.last_name +', ' + player.name +'</td>'
                                + '<td class="text-right">'
                                    + '<button class="btn btn-success mr-1" id="btnAction" player-id="'+ player.id +'" team-id="'+ player.team_id +'" player-name="'+ player.name + ' ' + player.last_name +'"><span class="fa fa-arrow-up"></span> </button>'
                                    + '<button class="btn btn-danger" id="btnChange" player-id="'+ player.id +'" team-id="'+ player.team_id +'"><span class="fa fa-arrow-left"></span> </button>'
                                +'</td>'
                            + '</tr>')

                        // $('#table-' + teamId ).append(
                        //     '<tr id="player-'+ player.id +'">'
                        //         + '<td>'+ player.number +'</td>'
                        //         + '<td><img src=" {{ asset('players_photos/') }}/' + player.photo +'" width="60px" height="60px"></td>'
                        //         + '<td>'+ player.last_name +', ' + player.name +'</td>'
                        //         + '<td class="text-right">'
                        //             + '<button class="btn btn-success mr-1" id="btnAction" player-id="'+ player.id +'" team-id="'+ player.team_id +'" player-name="'+ player.name + ' ' + player.last_name +'"><span class="fa fa-arrow-up"></span> </button>'
                        //             + '<button class="btn btn-danger" id="btnChange" player-id="'+ player.id +'" team-id="'+ player.team_id +'"><span class="fa fa-arrow-left"></span> </button>'
                        //         +'</td>'
                        //     + '</tr>')


                        //table-game-log
                        //this code can be inside the logGameHandle
                        $('#table-game-log').prepend(
                            '<tr>'
                                + '<td>'+ player.number +'</td>'
                                + '<td>'+ player.team_id +'</td>'
                                + '<td class="text-left"><img src="' + photo + '" width="60px" height="60px"> '+ player.last_name +', ' + player.name +'</td>'
                                + '<td>INICIA</td>'
                                + '<td>-</td>'
                                + '<td>00:00</td>'
                                + '<td>'
                                    + '<button class="btn btn-danger" id="btnDeleteLog" player-id="'+ player.id +'" team-id="'+ player.team_id +'"><span class="fa fa-remove"></span> </button>'
                                +'</td>'
                            + '</tr>')

                        // $('#table-game-log').prepend(
                        //     '<tr>'
                        //         + '<td>'+ player.number +'</td>'
                        //         + '<td>'+ player.team_id +'</td>'
                        //         + '<td class="text-left"><img src=" {{ asset('players_photos/') }}/' + player.photo +'" width="60px" height="60px"> '+ player.last_name +', ' + player.name +'</td>'
                        //         + '<td>INICIA</td>'
                        //         + '<td>-</td>'
                        //         + '<td>00:00</td>'
                        //         + '<td>'
                        //             + '<button class="btn btn-danger" id="btnDeleteLog" player-id="'+ player.id +'" team-id="'+ player.team_id +'"><span class="fa fa-remove"></span> </button>'
                        //         +'</td>'
                        //     + '</tr>')

                        }
                )
                if ( teamId == {{ $game->team1_id }} ) {
                    playersListA.push(...player)
                    $.cookie('playersListA', JSON.stringify(playersListA))
                }

                if ( teamId == {{ $game->team2_id }} ) {
                    playersListB.push(...player)
                    $.cookie('playersListB', JSON.stringify(playersListB))
                }


                //remove the player from the main array
                playersList = playersList.filter( x =>
                    x.id != playerId )
                console.log(playersList)
                $.cookie('playersList', JSON.stringify(playersList))

            });

            $(document).on("click", "#btnAction", function (e) {

                $('#action-player-id').html($(this).attr('player-name'));

                //add the teamid and playerid to the addAction button
                $("[id=btnAddAction]").attr('player-id', $(this).attr('player-id'))
                $("[id=btnAddAction]").attr('team-id', $(this).attr('team-id'))

                jQuery("#ActionsModal").modal('show');
            })

            $(document).on("click", "#btnAddAction", function (e) {

                Swal.fire({
                    title: "¿Deseas registrar la acción?",
                    text: "Esta acción se agregará al registro del juego!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Si, registrar acción!"
                    }).then((result) => {
                    if (result.isConfirmed) {

                        //data-id="{{ $action->id }}" data-value="{{ $action->value }}" data-status="Exitoso"
                        let actionId = $(this).attr("data-id")
                        let actionValue = $(this).attr("data-value")
                        let actionStatus = $(this).attr("data-status")
                        let playerId = $(this).attr('player-id')
                        let teamId = $(this).attr('team-id')

                        let tournamentId = {{ $game->tournament_id }}
                        let gameId = {{ $game->id }}

                        handleLogGame(tournamentId, gameId, teamId, playerId, actionId, actionStatus, actionValue, 1, '00:00', '')
                        jQuery("#ActionsModal").modal('hide');

                    }
                });
            })



            $(document).on("click", "#btnDataEntry", function (e) {
                e.preventDefault();
                jQuery("#codeModal").modal('show');
            });

            $(document).on("click", "#btnFinishDataEntry", function (e) {
                $.removeCookie('enableGame');
                $.removeCookie('playersList');
                $.removeCookie('playersListA');
                $.removeCookie('playersListB');
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



            //this function handles the activity of the game
            async function handleLogGame(
                    tournament_id = null,
                    game_id=null,
                    team_id=null,
                    player_id=null,
                    action_id=null,
                    status=null,
                    value=0,
                    period=null,
                    time=null,
                    comment=null
                ) {

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                await $.ajax({
                    type: "POST",
                    url: "{{ url('game_log') }}",
                    dataType: "json",
                    // data: $(this).serialize(),
                    data: {
                        'tournament_id': tournament_id,
                        'game_id':      game_id,
                        'player_id':    player_id,
                        'team_id':      team_id,
                        'action_id':    action_id,
                        'value':        value,
                        'period':       period,
                        'time':         time,
                        'status':       status,
                        'comment':      comment
                    },
                    success: function (data) {

                        $('#table-game-log').prepend(

                            '<tr game-lop="' + data.id + '" >'
                                + '<td class="text-right"><span class="score">'+ 'NN' + '</span></td>'
                                + '<td>'+ 'player.team_id' +'</td>'
                                + '<td class="text-left"><img src="" width="60px" height="60px"> '+ 'player.last_name' +', ' + 'player.name '+'</td>'
                                + '<td>Registro</td>'
                                + '<td>-</td>'
                                + '<td>00:00</td>'
                                + '<td>'
                                    + '<button class="btn btn-danger" id="btnDeleteLog" player-id="'+ 'player.id' +'" team-id="'+ 'player.team_id' +'"><span class="fa fa-remove"></span> </button>'
                                +'</td>'
                            + '</tr>'
                        )

                        //update the score
                        if (data.score>0) {
                            $('#score_' + team_id).html(data.score)
                        }

                        $.notify(data.message, data.type);

                    },
                    error:function(){
                        $.notify("Hubo un error al intentar registrar la Acción dl Juego!", "error");
                    },
                });

            }

        });

</script>
@endsection
