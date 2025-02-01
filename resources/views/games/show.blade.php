@extends('layouts.app')

@section('title', 'Detalles del Juego')
@section('description',  "Detalles del Juego - ")

@section('content')

<div class="container">
    <div class="row py-1">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header text-center">
                    <h2>Detalles del Juego</h2>
                </div>
                <div class="card-body">
                    @if($game->status !== 'Finalizado')
                        <div class="row text-right">
                            <div class="col-12">
                                <button class="btn btn-danger" id="btnDataEntry"> <span class="fa fa-edit"></span> {{ __('Data Entry') }}</button>

                                {{-- <button class="btn btn-primary gameButton"  id="btnRegisterPlayer" data-id-team="{{ $game->team1 }}">
                                    <span class="fa fa-pencil"></span> {{ __('Register Player') }}
                                </button> --}}

                                <button class="btn btn-warning" id="btnFinishDataEntry" style="display: none">
                                     <span class="fa fa-edit"></span> {{ __('Finish Data Entry') }}
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
                    <div class="row">
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
                                    {{-- <div class="card">
                                        <div class="card-header">
                                            Jugadores
                                        </div>
                                        <div class="card-body">
                                            @include('components.tableGamePlayers', ['teamId' => $game->team1_id])
                                        </div>
                                    </div> --}}
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

                                    {{-- <div class="card">
                                        <div class="card-header">
                                            Jugadores
                                        </div>
                                        <div class="card-body">
                                            @include('components.tableGamePlayers', ['teamId' => $game->team2_id])
                                        </div>
                                    </div> --}}
                                </div>
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
          <h4 class="modal-title">Ingresa el código de acceso</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>


        <!-- Modal body -->
        <form id="codeForm">

            <div class="modal-body">
            <h5>
                Ingresa el código de acceso para habilitar la captura de datos del juego, si no tienes el código de acceso, solicitalo al administrador de la liga.
            </h5>
            <p><div class="form-group">
                <label for="access_code">{{ __('Access Code') }}</label>
                <input type="text" class="form-control" name="access_code" id="access_code" maxlength="6" required>
            </div></p>

            </div>
            {{ csrf_field() }}

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-lg">
                    <span class="fa fa-check"></span>
                    {{__('Enable Game data entry')}}
                </button>
                <button type="button" class="btn btn-primary btn-lg" class="close" data-dismiss="modal">
                    <span class="fa fa-remove"></span> {{__('Cancel')}}
                </button>
            </div>
        </form>

      </div>
    </div>
  </div>
<!-- End codeModal Form -->

<!-- Player Modal Form -->
<div id="playerModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"> {{ __("Player information") }}</h4>
                <button type="button" class="close" data-dismiss="modal" data-backdrop="false">&times;</button>
            </div>
            <!--mandamos la forma-->
            <form id="playerForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">


                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="fisrt_name"><b>{{ __("First Name") }} *</b></label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required maxlength="45">
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="last_name"><b>{{ __("Last Name") }} *</b></label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required maxlength="45">
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="email"><b>{{ __("E-Mail") }} *</b></label>
                                <input type="text" class="form-control" id="email" name="email" required maxlength="45">
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label for="phone"><b>{{ __("Phone Number") }} *</b></label>
                                <input type="text" class="form-control" id="phone" name="phone" required maxlength="10">
                            </div>
                        </div>


                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label for="dob"><b>{{ __("DOB") }}</b></label>
                                <input type="date" class="form-control" id="dob" name="dob" required >

                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label for="access_pin"><b>{{ __("Access Pin") }}</b></label>
                                <input type="text" class="form-control" id="access_pin" name="access_pin" maxlength="4">
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label for="gender"><b>{{ __("Gender") }} *</b></label>
                                <select class="form-control" id="gender" name="gender">
                                    <option value="M">Male</option>
                                    <option value="F">Female</option>
                                    <option value="N">{{ __('Not specified') }}</option>

                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label for="player_number"><b>{{ __("Number in T-shirt") }} *</b></label>
                                <input type="text" class="form-control" id="player_number" name="player_number" required maxlength="5">
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12">
                            <p><b>{{ __("Notify in case of emergency") }}</b></p>
                        </div>

                        <div class="col-md-9 col-sm-12">
                            <div class="form-group">
                                <label for="emergency_name"><b>{{ __("Emergency Name") }}</b></label>
                                <input type="text" class="form-control" id="emergency_name" name="emergency_name" maxlength="80">
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label for="emergency_phone"><b>{{ __("Emergency Phone") }}</b></label>
                                <input type="text" class="form-control" id="emergency_phone" name="emergency_phone" maxlength="10">
                            </div>
                        </div>



                        <div class="col-md-3 col-sm-6">
                            <img id="player_photo" src="{{ asset('images/No-photo-m.png') }}" class="img-fluid img-thumbnai" alt="{{ __('Player Photo') }}">
                        </div>

                        <div class="col-md-9 col-sm-6">
                            <div class="form-group">
                                <label for="photo"><b>{{ __("Photo") }}</b></label>
                                <input type="file" class="form-control" id="photo" name="photo" maxlength="80" accept="image/*">
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <b>Datos marcados con * son obligatorios</b>
                            <input type="hidden" class="form-control" id="id" name="id" readonly>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="form-group text-right">
                        {{ csrf_field() }}

                            <button type="submit" class="btn btn-primary btn-lg">
                                <span class="fa fa-save"></span>
                                {{__('Save')}}
                            </button>

                            <button type="button" class="btn btn-primary btn-lg" class="close" data-dismiss="modal">
                                <span class="fa fa-remove"></span> {{__('Cancel')}}
                            </button>
                        </div>
                    </div>
            </form>
                <!--fin de la forma-->
         </div>
    </div>
</div>
<!--end of the modal window-->


<!--accessPinModal-->
<div class="modal" id="accessPinModal">
    <div class="modal-dialog">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Ingresa tu PIN de acceso</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>


        <!-- Modal body -->
        <form id="pinForm">

            <div class="modal-body">
            <h5>
                Type your PIN access code to make a check for your attendance to the game, if you dont remember your PIN access, ask to the league administrator.
            </h5>
            <p><div class="form-group">
                <label for="access_pin">{{ __('Access Pin for Player') }}</label>
                <input type="text" class="form-control" name="access_pin" id="access_pin" maxlength="6" required>
            </div></p>

            </div>
            {{ csrf_field() }}

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-lg">
                    <span class="fa fa-check"></span>
                    {{__('Enable Game data entry')}}
                </button>
                <button type="button" class="btn btn-primary btn-lg" class="close" data-dismiss="modal">
                    <span class="fa fa-remove"></span> {{__('Cancel')}}
                </button>
            </div>
        </form>

      </div>
    </div>
  </div>
<!--end of accessPinModal-->

@endsection

@section('js')
<script type="text/javascript">

        jQuery(document).ready(function($) {

            let gameEnable = false;

            $(document).on("click", "#btnDataEntry", function (e) {
                e.preventDefault();
                //$('#row_name').text($(this).attr('data-name'));
                //$('#deleteForm').attr('action', $(this).attr('href'));
                jQuery("#codeModal").modal('show');


            });

            $(document).on("click", "#btnFinishDataEntry", function (e) {
                enableGame(false);
                $(this).hide();
                $('#btnDataEntry').show();
            });

            $(document).on("click", "#btnRegisterPlayer", function (e) {
                e.preventDefault();
                jQuery("#playerModal").modal('show');
            });

            btnAttendance
            $(document).on("click", "#btnAttendance", function (e) {
                e.preventDefault();
                jQuery("#accessPinModal").modal('show');
            });

            $(document).on("click", "#player", function (e) {
                alert('Jugador x');
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
                            enableGame(data);
                            $('#btnDataEntry').hide();
                            $('#btnFinishDataEntry').show();

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

            //player form handle
            $(document).on("submit", "#playerForm", function (e) {
                e.preventDefault();

                $.ajax({
                    type: "POST",
                    url: "{{ url('players') }}",
                    dataType: "json",
                    data: $(this).serialize(),
                    success: function (data) {
                        $("#codeForm").trigger('reset');
                        jQuery.noConflict();
                        jQuery("#playerForm").modal('toggle');

                        $.notify(data.message, data.type);

                    },
                    error:function(){
                        $.notify( "{{ __('There is an error trying to save the player') }}", "error");
                    },
                });

                return false;
            });


            $(document).on("submit", "#pinForm", function (e) {
                e.preventDefault();

                $.ajax({
                    type: "POST",
                    url: "{{ url('player_game_attendance') }}",
                    dataType: "json",
                    data: $(this).serialize(),
                    success: function (data) {

                        if (data.access === 'true') {
                            enableGame(data);
                            $('#btnDataEntry').hide();
                            $('#btnFinishDataEntry').show();

                            $("#codeForm").trigger('reset');
                            jQuery.noConflict();
                            jQuery("#codeModal").modal('toggle');
                        }

                        $('#access_code').val('');
                        $.notify(data.message, data.type);

                    },
                    error:function(){
                        $.notify("There is an issue trying to register the attendance for player!", "error");
                    },
                });

                return false;
            });

            //init the page variables
            enableGame(gameEnable);

            function enableGame(toggleEnable) {

                if (toggleEnable == false) {
                    $('.gameButton').hide();
                    $('.gameActions').hide();

                } else {
                    $('.gameButton').show();
                }
            }

        });

</script>
@endsection
