@extends('layouts.app')

@section('title', 'Detalles del Juego')
@section('description',  "Detalles del Juego ")

{{-- content starts --}}
@section('content')

{{-- main menu --}}
@include('components.menuTournament', ['active' => 'results'])

<div class="container">
    <div class="row py-1">
        <div class="col-md-12">

            <div class="card">
                <div class="card-body">
                    @if($game->status !== 'Finalizado')
                        <div class="row">
                            <div class="col-12 text-right">
                                <button class="btn btn-success" id="btnDataEntry">
                                    <span class="fa fa-edit"></span> Capturar Actividad
                                </button>

                                <button class="btn btn-primary" id="btnSetup" style="display: none">
                                     <span class="fa fa-cog"></span> Configurar Juego
                                </button>

                                 <button class="btn btn-success" id="btnStart" style="display: none">
                                     <span class="fa fa-play"></span> Comenzar Juego
                                </button>

                                <button class="btn btn-danger" id="btnFinishDataEntry" style="display: none">
                                     <span class="fa fa-sign-out"></span> Finalizar
                                </button>
                            </div>
                        </div>
                    @endif

                    <div class="row" id="score">
                        <div class="col-6 text-center">

                            <div class="row align-items-center">
                                <div class="col-3 text-center">
                                    @if( $game->t1logo )
                                        <img class="img-fluid" src="{{ asset('team_logo/' . $game->t1logo) }}" width="100px" >
                                    @else
                                        <img class="img-fluid" src="{{ asset('images/_shield.png') }}" width="100px" >
                                    @endif
                                </div>

                                <div class="col-9 text-right align-items-center">
                                    <span class="score" >{{ $game->score_team1 }}</span>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-12 text-left align-items-center">
                                    <h5>{{ $game->team1 }}</h5>
                                    (23-1)
                                </div>
                            </div>

                        </div>

                        <div class="col-6 text-center">
                            <div class="row align-items-center">
                                <div class="col-9 text-left align-items-center">
                                    <span class="score" >{{ $game->score_team2 }}</span>
                                </div>

                                <div class="col-3 text-center">
                                    @if( $game->t2logo )
                                        <img class="img-fluid" src="{{ asset('team_logo/' . $game->t2logo) }}" width="100px" >
                                    @else
                                        <img class="img-fluid" src="{{ asset('images/_shield.png') }}" width="100px" >
                                    @endif

                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-12 text-right align-items-center">
                                    <h5>{{ $game->team2 }}</h5>
                                    (12 -2)
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- datos del score,  --}}
                    <div id="dashboard" style="display: none">
                        <div class="row mb-1" >

                            <div class="col-6">
                                <div class="row">
                                    <div class="col-6 text-left">
                                        @if( $game->t1logo )
                                            <img class="img-fluid" src="{{ asset('team_logo/' . $game->t1logo) }}" width="40px" >
                                        @else
                                            <img class="img-fluid" src="{{ asset('images/_shield.png') }}" width="40px" >
                                        @endif

                                    </div>

                                    <div class="col-6 text-right align-items-center">
                                        <span class="score" id="score_{{ $game->team1_id }}">{{ $game->score_team1 }}</span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 text-left align-items-center">
                                        {{ $game->team1 }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 text-right">
                                <div class="row">
                                    <div class="col-4 text-left align-items-center">
                                        <span class="score" id="score_{{ $game->team2_id }}" >{{ $game->score_team2 }}</span>
                                    </div>
                                    <div class="col-8 text-right align-items-center">

                                        @if( $game->t2logo )
                                            <img class="img-fluid" src="{{ asset('team_logo/' . $game->t2logo) }}" width="40px" >
                                        @else
                                            <img class="img-fluid" src="{{ asset('images/_shield.png') }}" width="40px" >
                                        @endif
                                    </div>

                                </div>

                                  <div class="row">
                                    <div class="col-12 text-right align-items-center">
                                        {{ $game->team2 }}
                                    </div>
                                </div>

                            </div>
                        </div>

                         <div class="row">
                        <div class="col-6">
                            <button
                                class="btn btn-primary m-1"
                                id="addPlayer"
                                team-id="{{ $game->team1_id }}">
                                    <span class="fa fa-sign-in"></span> Jugadores en la Banca
                            </button>
                            @include('components.tablePlayers',['teamId' => $game->team1_id])
                        </div>
                        <div class="col-6">
                            <button
                                class="btn btn-primary m-1"
                                id="addPlayer"
                                team-id="{{ $game->team2_id }}">
                                    <span class="fa fa-sign-in"></span> Jugadores en la Banca
                            </button>
                            @include('components.tablePlayers',['teamId' => $game->team2_id])
                        </div>
                    </div>

                        <div class="row">
                            <div class="col-12 text-center">
                                <h2>REGISTRO DEL JUEGO</h2>

                                <div class="table-responsive">
                                    <table class="table table-hover" id="table-game-log">
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
                                            <tr id="no-activity">
                                                <td colspan="7" class="text-centeer">
                                                    <b>No hay registro del juego aún</b>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>

                            </div>

                        </div>
                    </div>
                    {{-- fin del dashboard --}}

                    {{-- <hr />
                    <div class="row">
                        <h3 class="col-12 text-center">Detalles del Juego</h3>
                        <div class="col-12 text-center">
                            Fecha y Hora:{{ $game->game_day }} {{ $game->game_time }} -
                            Tipo: {{ $game->type }} - Estado: {{ $game->status }} -
                            Categoría: {{ ($game->category) ? $game->category : 'Interdivisional' }}
                            {{ isset($game->group) ? ' - ' . $game->group : '' }} -
                            Lugar: {{ $game->stadium }}
                        </div>

                    </div> --}}

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
          <h4 class="modal-title">CÓDIGO DE ACCESO</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>


        <!-- Modal body -->
        <form id="codeForm">
            <input type="hidden" name="game_id" value="{{ $game->id }}">

            <div class="modal-body">
            <h5>
                Ingresa el CÓDIGO DE ACCESO para habilitar la captura de datos del juego, si no tienes el CÓDIGO DE ACCESO, solicítalo al administrador de la liga.
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


<!-- codeModal Form -->
<div class="modal" id="SetupModal">
    <div class="modal-dialog mw-100 w-100">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">CONFIGURACIÓN DEL JUEGO</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>


            <!-- Modal body -->
            <div class="modal-body">

                <!-- Nav tabs -->
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#team1"> A - {{ $game->team1 }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#team2">B - {{ $game->team2 }} </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#referees">ÁRBITROS</a>
                    </li>

                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active" id="team1">
                        @include('components.tableGamePlayers', ['teamId' => $game->team1_id, 'gameId' => $game->id] )
                    </div>
                    <div class="tab-pane fade" id="team2">
                        @include('components.tableGamePlayers', ['teamId' => $game->team2_id, 'gameId' => $game->id] )
                    </div>
                    <div class="tab-pane fade" id="referees">

                        @include('components.tableGameReferees', ['gameId' => $game->id] )
                    </div>
                </div>

            </div>
            {{ csrf_field() }}

            <!-- Modal footer -->
            <div class="modal-footer">
                <button class="btn btn-primary btn-lg" id="btnStartGame">
                    <span class="fa fa-check"></span>
                    Iniciar Juego
                </button>
                <button type="button" class="btn btn-primary btn-lg" class="close" data-dismiss="modal">
                    <span class="fa fa-remove"></span>
                    Cancelar
                </button>
            </div>

      </div>
    </div>
  </div>
<!-- End codeModal Form -->

{{-- Players list Modal --}}
<div class="modal" id="PlayersModal">
    <div class="modal-dialog">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">LISTADO DE JUGADORES</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>


        <!-- Modal body -->
        {{-- <form id="PlayersForm"> --}}
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <table class="table table-hover" id="table-players-list">
                            <thead>
                                <tr>
                                    <td>#</td>
                                    <td>FOTO</td>
                                    <td>NOMBRE</td>
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
          <h4 class="modal-title">REGISTRAR MOVIMIENTO</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>


        <!-- Modal body -->
        {{-- <form id="ActionForm"> --}}

            <div class="modal-body">
                <div id="activity">
                    <table class="table">
                        <thead>
                            <tr>
                                <td>ACCIÓN</td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <tr colspan="2">
                                    <h3>
                                        <span id="action-player-id">NOMBRE JUGADOR</span>
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
                    CERRAR
                </button>
            </div>
        {{-- </form> --}}

      </div>
    </div>
  </div>
{{-- End Activity list Modal --}}


@endsection

@section('js')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/jquery.cookie.js') }}"></script>
<script type="text/javascript">

        jQuery(document).ready(function($) {

            const maxPlayers = 12;
            const maxStarters = 5;
            const maxCapitan = 1;

            let gameEnable = false;
            let gameId = 0;
            let playersList = [];
            let playersListA = [];
            let playersListB = [];



            if ( $.cookie('playersList') ) {
                playersList = $.parseJSON($.cookie('playersList'));
            }

            if ( $.cookie('playersListA') ) {
                playersListA = $.parseJSON($.cookie('playersListA'))
                let teamId = {{ $game->team1_id }}
                playersListA.forEach( player => {

                    addPlayerToTable(player);

                })
            }

            if ( $.cookie('playersListB') ) {
                playersListB = $.parseJSON($.cookie('playersListB'))
                let teamId = {{ $game->team2_id }}
                playersListB.forEach( player => {

                    addPlayerToTable(player);

                })
            }

            $(document).on("change", "#starter-player_{{ $game->team1_id }}, #starter-player_{{ $game->team2_id }}", function (e) {
                handleStartersChange($(this));
            })

            $(document).on("change", "#captain-player_{{ $game->team1_id }}, #captain-player_{{ $game->team2_id }}", function (e) {
                handleCaptainChange($(this));
            })

             function handleCaptainChange($checkbox) {
                let teamId = $checkbox.attr('data-team-id');
                let capitanChecked = $('input[type="checkbox"][id="captain-player_' + teamId + '"]:checked').length;

                if (capitanChecked > maxCapitan) {
                    alert('Solo puedes seleccionar un capitán por equipo')
                    $checkbox.prop('checked', false);
                    return;
                }
                //at this point we can save the captain change in the database
                //alert('attended -> ' + $checkbox.attr('data-player-id') + ', game-id -> ' + $checkbox.attr('data-game-id') + ', team-id -> ' + teamId )
            }

            function handleStartersChange($checkbox) {
                let teamId = $checkbox.attr('data-team-id');
                let startersChecked = $("#starter-player_" + teamId + ":checked").length;

                if (startersChecked > maxStarters) {
                    alert('Solo puedes seleccionar mas jugadores para el cuadro titular hasta el maximo permitido')
                    $checkbox.prop('checked', false);
                    return;
                }
                //at this point we can save the starter change in the database
                //alert('attended -> ' + $checkbox.attr('data-player-id') + ', game-id -> ' + $checkbox.attr('data-game-id') + ', team-id -> ' + teamId )
            }

            //Configuration of the game
            $(document).on('click', '#btnStartGame', function(e) {
                e.preventDefault();
                //validate capitan values
                const teamIds = [{{ $game->team1_id }}, {{ $game->team2_id }}];

                for (const teamId of teamIds) {
                    const captainCount = $('input[type="checkbox"][id="captain-player_' + teamId + '"]:checked').length;

                    if (captainCount < 1) {
                        alert('Debes seleccionar al menos un capitán para el equipo ' + teamId + '.');
                        return;
                    }

                    if (captainCount > maxCapitan) {
                        alert('Solo puedes seleccionar un capitán por equipo. Revisa el equipo ' + teamId + '.');
                        return;
                    }
                }

                //validate the team assistance

                //validate the team starters

                //save the game configs



                alert('Start game');
            })

            $(document).on("click", "#addPlayer", function (e) {
                e.preventDefault();

                // $('#players-list').empty()
                $('#table-players-list > tbody').empty()
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

                    $('#table-players-list').append(
                        '<tr id="player-'+ player.id +'">'
                            + '<td class="text-center"><span class="playerNumber">#'+ player.number +'</span></td>'
                            + '<td><img src="' + photo + '" width="60px height="60px""></td>'
                            + '<td>'+ player.last_name +', ' + player.name +'</td>'
                            + '<td class="text-right"><button class="btn btn-success" id="btnInGame" player-id="'+ player.id +'" team-id="'+ player.team_id +'"><span class="fa fa-plus"></span></td>'
                        + '</tr>')

                })

                jQuery("#PlayersModal").modal('show');
            });

            //$(document).on("click", "#table-players tr", function (e) {
            $(document).on("click", "#btnInGame", function (e) {
                e.preventDefault();

                const playerId = $(this).attr('player-id')
                const teamId = $(this).attr('team-id')

                //validate if we can add a players in game
                if ( teamId == {{ $game->team1_id }} ) {
                    if (playersListA.length >= maxPlayers) {
                        alert('Ya no se pueden agregar mas jugaddores al juego')
                        return
                    }
                }

                if ( teamId == {{ $game->team2_id }} ) {
                     if (playersListB.length >= maxPlayers) {
                        alert('Ya no se pueden agregar mas jugaddores al juego')
                        return
                    }
                }

                //remove player from th list
                $(this).parent().parent().remove();

                //$('#table-game-log > tbody').empty()
                $('#no-activity').remove();

                let player = playersList.filter( x => x.id == playerId )

                let players = playersList.filter( x =>
                    x.id == playerId
                ).forEach( player => {

                        let photo =''
                        if (player.photo == null) {
                            photo = '{{ asset('images/') }}/No-photo-m.png'
                        } else {
                            photo = '{{ asset('players_photos/') }}/' + player.photo
                        }

                    addPlayerToTable(player)

                        //table-game-log
                        //this code can be inside the logGameHandle
                        $('#table-game-log').prepend(
                            '<tr>'
                                + '<td>'+ player.number +'</td>'
                                + '<td>'+ player.team_id +'</td>'
                                + '<td class="text-left"><img src="' + photo + '" width="60px" height="60px"> '+ player.last_name +', ' + player.name +'</td>'
                                + '<td>INGRESA</td>'
                                + '<td>-</td>'
                                + '<td>00:00</td>'
                                + '<td>'
                                    + '<button class="btn btn-danger" id="btnDeleteLog" player-id="'+ player.id +'" team-id="'+ player.team_id +'"><span class="fa fa-remove"></span> </button>'
                                +'</td>'
                            + '</tr>')

                })

                if ( teamId == {{ $game->team1_id }} ) {
                    playersListA.push(...player)
                    $.cookie('playersListA', JSON.stringify(playersListA))
                }

                if ( teamId == {{ $game->team2_id }} ) {
                    playersListB.push(...player)
                    $.cookie('playersListB', JSON.stringify(playersListB))
                }


                //remove the player from the main array
                playersList = playersList.filter( x => x.id != playerId )
                $.cookie('playersList', JSON.stringify(playersList))

            })

            //player change aout of the game
            $(document).on("click", "#btnChange", function (e) {

                e.preventDefault();

                Swal.fire({
                    title: "¿Deseas realizar el cambio?",
                    text: "Cambio de jugador SALE!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Si, realizar cambio!"
                    }).then((result) => {
                    if (result.isConfirmed) {

                        const playerId = $(this).attr('player-id')
                        const teamId = $(this).attr('team-id')
                        let player = []

                        if ( teamId == {{ $game->team1_id }} ) {
                            player = playersListA.filter( x => x.id == playerId )
                            playersListA = playersListA.filter( x => x.id != playerId )
                            $.cookie('playersListA', JSON.stringify(playersListA))
                        }

                        if ( teamId ==   {{ $game->team2_id }} ) {
                            player = playersListB.filter( x => x.id == playerId )
                            playersListB = playersListB.filter( x => x.id != playerId )
                            $.cookie('playersListB', JSON.stringify(playersListB))
                        }

                        playersList.push(...player)
                        $.cookie('playersList', JSON.stringify(playersList))
                        //remove player from th list
                        $(this).parent().parent().remove();

                        //add the row in table logs
                        let players = playersList.filter( x =>
                            x.id == playerId
                        ).forEach( player => {

                            let photo =''
                            if (player.photo == null) {
                                photo = '{{ asset('images/') }}/No-photo-m.png'
                            } else {
                                photo = '{{ asset('players_photos/') }}/' + player.photo
                            }

                            //table-game-log
                            //this code can be inside the logGameHandle
                            $('#table-game-log').prepend(
                                '<tr>'
                                    + '<td>'+ player.number +'</td>'
                                    + '<td>'+ player.team_id +'</td>'
                                    + '<td class="text-left"><img src="' + photo + '" width="60px" height="60px"> '+ player.last_name +', ' + player.name +'</td>'
                                    + '<td>SALE</td>'
                                    + '<td>-</td>'
                                    + '<td>00:00</td>'
                                    + '<td>'
                                        + '<button class="btn btn-danger" id="btnDeleteLog" player-id="'+ player.id +'" team-id="'+ player.team_id +'"><span class="fa fa-remove"></span> </button>'
                                    +'</td>'
                                + '</tr>')

                        })

                    //register the activity on DB
                        // handleLogGame(
                        //     tournamentId,
                        //     gameId,
                        //     teamId,
                        //     playerId,
                        //     actionId,
                        //     actionStatus,
                        //     actionValue,
                        //     period,
                        //     '00:00',
                        //     ''
                        // )


                    }
                })

            })

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
                        let period = $('#period').html();

                        let tournamentId = {{ $game->tournament_id }}
                        let gameId = {{ $game->id }}

                        handleLogGame(
                            tournamentId,
                            gameId,
                            teamId,
                            playerId,
                            actionId,
                            actionStatus,
                            actionValue,
                            period,
                            '00:00',
                            ''
                        )
                        jQuery("#ActionsModal").modal('hide');

                    }
                })
            })

            $(document).on("click", "#btnDataEntry", function (e) {
                e.preventDefault();
                jQuery("#codeModal").modal('show');
            });

            $(document).on("click", "#btnSetup", function (e) {
                e.preventDefault();
                jQuery("#SetupModal").modal('show');
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

                            if (data.game_id != {{ $game->id }} ) {
                                $.notify("Clave del juego no valida o incorrecta!", "error");

                                //we need to remove all cookies related to the game
                                $.removeCookie('enableGame');
                                $.removeCookie('game_id');
                                $.removeCookie('playersList');
                                $.removeCookie('playersListA');
                                $.removeCookie('playersListB');
                                return;
                            }

                            $.cookie('enableGame', true);
                            $.cookie('game_id', data.game_id);
                            enableGame(true);
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

                let game_id = $.cookie('game_id') ?? 0;

                if ( {{ $game->id }} == game_id) {
                    gameId = {{ $game->id }};
                } else {
                    gameId = 0;
                    //we need to remove all cookies related to the game
                    $.removeCookie('enableGame');
                    $.removeCookie('game_id');
                    $.removeCookie('playersList');
                    $.removeCookie('playersListA');
                    $.removeCookie('playersListB');
                    toggleEnable = false;
                }


                if (toggleEnable == false) {

                    $('#dashboard').hide();
                    $('#score').show();
                    $('#title').html('Detalles del juego');

                    $('#btnDataEntry').show();
                    $('#btnFinishDataEntry').hide();
                    $('#btnStart').hide();
                    $('#btnSetup').hide();
                    $('[id="addPlayer"]').hide();

                } else {

                    $('#dashboard').show();
                    $('#score').hide();
                    $('#title').html('Dashboard del juego');

                    $('#btnDataEntry').hide();
                    $('#btnFinishDataEntry').show();
                    //$('#btnStart').show();
                    $('#btnSetup').show();
                    $('[id="addPlayer"]').show();

                }
            }

            /* this fnction add a player dynamically and reuse all for all this section*/
            function addPlayerToTable(player) {
                let photo ='';

                if (player.photo == null) {
                    photo = '{{ asset('images/') }}/No-photo-m.png'
                } else {
                    photo = '{{ asset('players_photos/') }}/' + player.photo
                }

                $('#table-' + player.team_id ).append(
                    '<tr id="player-'+ player.id +'">'
                        + '<td class="text-left">' + player.number + '</td>'
                        + '<td><img src="' + photo + '" width="60px height="60px""></td>'
                        + '<td> '+ player.last_name +', ' + player.name
                        + '</td>'
                        + '<td class="text-center">'
                        + '0'
                        + '</td>'
                        + '<td class="text-center">'
                        + '0'
                        + '</td>'
                        + '<td class="text-center">'
                        + '<button class="btn btn-primary w-40" id="btnAction" player-id="'
                            + player.id +'" team-id="'+ player.team_id +'" player-name="'+ player.name + ' ' + player.last_name
                            +'"><span class="fa fa-plus"></span> </button>'
                        + '<button class="btn btn-danger w-40" id="btnChange" player-id="'+ player.id +'" team-id="'+ player.team_id +'"> '
                            + '<span class="fa fa-sign-out"></span> </button>'
                        + '</td>'
                    + '</tr>'
                )

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

                        console.log(data)

                        $('#table-game-log').prepend(

                            '<tr game-lop="' + data.id + '" >'
                                + '<td class="text-right"><span class="score">'+ 'NN' + '</span></td>'
                                + '<td>'+ player_id +'</td>'
                                + '<td class="text-left"><img src="" width="60px" height="60px"> '+ 'Player Name'  + '</td>'
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
                            console.log(data.score + ' - ' + team_id)
                            $('#score_' + team_id).html(data.score)
                        }

                        $.notify(data.message, data.type);

                    },
                    error:function(xhr){
                        const message = xhr.responseJSON && xhr.responseJSON.message;
                        $.notify(message || "Hubo un error al registrar la acción del juego", "error");
                    },
                });

            }

        });

</script>
@endsection
