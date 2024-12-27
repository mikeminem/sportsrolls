@extends('layouts.app')

@section('title', config('app.name') ." :: " .  __('Games') )
@section('description',  "Administrador de Juegos ")

@section('content')

<div class="container">
    <div class="row py-2">
        <div class="col-md-12">

            <div class="card">
                <div class="card-body">
                    <div class="text-left">
                        <h1>{{ __('Games')}}</h1>
                    </div>

                    @if (Auth::check() &&  ( (Auth::user()->role == 'Admin') || (Auth::user()->role == 'Promoter') ))
                        <a role="button"
                            class="btn btn-primary"
                            href="javascript:void(0);"
                            id="btnAdd"
                             >
                            <span class="fa fa-plus"></span> {{ __("New")}}
                        </a>

                        <a role="button" class="btn btn-primary" href="javascript:void(0);" id="btnPrint">
                            <span class="fa fa-print"></span> {{ __("Print")}}
                        </a>
                    @endif

                    <a role="button" class="btn btn-primary" href="javascript:void(0);" id="btnRefresh">
                        <span class="fa fa-refresh"></span> {{ __("Refresh")}}
                    </a>
                    <hr />
                        <div class="row">
                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label for="filterTournaments"><b>Torneos Activos</b></label>
                                    <select class="form-control" required id="filterTournaments" name="filterTournaments"  >
                                        <option value="">Todos</option>
                                        @foreach($tournaments as $tournament)
                                            <option value="{{ $tournament->id}}">{{ $tournament->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label for="filterJourneys"><b>Jornadas</b></label>
                                    <select class="form-control" required id="filterJourneys" name="filterJourneys"  >
                                        <option value="">Selecciona un Torneo</option>
                                    </select>

                                </div>
                            </div>

                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label for="branch"><b>Rama *</b></label>
                                    <select class="form-control" required id="filterBranchs" name="filterBranchs"  >
                                        <option value="">Todas</option>
                                        <option value="Varonil">Varonil</option>
                                        <option value="Femenil">Femenil</option>
                                        <option value="Mixto">Mixto</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label for="title"><b>Categorias *</b></label>
                                    <select class="form-control" required id="filterCategories" name="filterCategories">
                                        <option value="">Selecciona un torneo</option>
                                    </select>
                                </div>
                            </div>


                        </div>

                    <hr />

                    <div class="table-responsive">
                        <table class="table table-hover" id="table">
                            <thead>
                                <tr>
                                    <th>Torneo</th>
                                    <th>Jornada</th>
                                    <th>Fecha / Hora</th>
                                    <th>Código de Acceso</th>
                                    <th>Equipo 1 (Visitante)</th>
                                    <th>Marcador</th>
                                    <th>Equipo 2 (Casa)</th>
                                    <th>Rama</th>
                                    <th>Categoría</th>
                                    <th>Category ID</th>
                                    <th>Tipo</th>
                                    <th>Estadio</th>
                                    <th>Estatus</th>
                                    <th>Id</th>

                                    <th> - </th>
                                </tr>
                            </thead>

                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form -->
<div id="formModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" >
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"> Datos del Juego</h4>
                <button type="button" class="close" data-dismiss="modal" data-backdrop="false">&times;</button>
            </div>
            <!--mandamos la forma-->
            <form id="updateForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" class="form-control" readonly id="sport_id" name="sport_id">

                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="tournament_id"><b>Torneo *</b></label>
                                <select class="form-control" required id="tournament_id" name="tournament_id">
                                    <option value="">Selecciona</option>
                                    @foreach($tournaments as $tournament)
                                        <option value="{{ $tournament->id}}">{{ $tournament->title}}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="journey_id"><b>Jornada *</b></label>
                                <select class="form-control" required id="journey_id" name="journey_id">
                                    <option value="">Selecciona un torneo</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="type"><b>Tipo *</b></label>
                                <select class="form-control" required id="type" name="type"  >
                                    <option value="Temporada Regular" selected>Temporada Regular</option>
                                    <option value="Amistoso">Amistoso</option>
                                    <option value="Pretemporada">Pretemporada</option>
                                    <option value="Finales">Finales</option>
                                    <option value="Semifinal">Semifinal</option>
                                    <option value="Final">Final</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="stadium_id"><b>Sede (Estadio / Gimnasio) *</b></label>
                                <select class="form-control" required id="stadium_id" name="stadium_id">
                                    <option value="">Selecciona la sede</option>
                                    @foreach($stadiums as $stadium)
                                        <option value="{{ $stadium->id}}">{{ $stadium->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="branch"><b>Rama *</b></label>
                                <select class="form-control" required id="branch" name="branch"  >
                                    <option value="">Selecciona</option>
                                    <option value="Varonil">Varonil</option>
                                    <option value="Femenil">Femenil</option>
                                    <option value="Mixto">Mixto</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="title"><b>Categoria</b></label>
                                <select class="form-control" id="category_id" name="category_id">
                                    <option value="">Selecciona un torneo</option>

                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="game_day"><b>Fecha dej Juego *</b></label>
                                <input type="date" class="form-control" id="game_day" name="game_day" required maxlength="80">
                            </div>
                        </div>


                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="game_time"><b>Hora del Juego *</b></label>
                                <input type="time" class="form-control" id="game_time" name="game_time" required max="80" min="5">
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="team1_id"><b>Equipo 1 (Casa)</b></label>
                                <select class="form-control" required id="team1_id" name="team1_id">
                                    <option value="">Selecciona torneo, rama y cateogria</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="team2_id"><b>Equipo 2 (Visitante)</b></label>
                                <select class="form-control" required id="team2_id" name="team2_id">
                                    <option value="">Selecciona torneo, rama y cateogria</option>
                                </select>

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

<!-- Score Modal Form -->
<div id="scoreModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" >
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"> Capturar Resultado del Juego</h4>
                <button type="button" class="close" data-dismiss="modal" data-backdrop="false">&times;</button>
            </div>
            <!--mandamos la forma-->
            <form id="scoreForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" class="form-control" id="idscore" name="id">

                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="score_team1"><span id="team1score" >Equipo 1 </span> (Casa) * </label>
                                <input type="number" class="form-control" id="score_team1" name="score_team1" required>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="score_team2"><span id="team2score" >Equipo 2 </span> (Visitante) *</label>
                                <input type="number" class="form-control" id="score_team2" name="score_team2" required>

                            </div>
                        </div>
                    </div>

                    <div class="row" id="penalties">
                        <div class="col-md-12 col-sm-12">
                            <b>Resultado en Penales en caso de aplicar</b>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="score_penalties_team1"><span id="team1scorePenalties" >Equipo 1 </span> (Casa) * </label>
                                <input type="number" class="form-control" id="score_penalties_team1" name="score_penalties_team1" >
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="score_penalties_team2"><span id="team2scorePenalties" >Equipo 2 </span> (Visitante) *</label>
                                <input type="number" class="form-control" id="score_penalties_team2" name="score_penalties_team2" >

                            </div>
                        </div>

                    </div>


                    <div class="row">

                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="status"><span id="team1score" >Estado del Juego *</span></label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="">Selecciona</option>
                                    <option value="Finalizado">Finalizado</option>
                                    <option value="En Curso">En Curso</option>
                                    <option value="Cancelado">Cancelado</option>
                                    <option value="Suspendido">Suspendido</option>
                                    <option value="Protestado">Protestado</option>

                                </select>
                            </div>
                        </div>


                    </div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="game_day"><b>Notas</b></label>

                                <textarea class="form-control" id="notes" name="notes" rows="4"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <b>Datos marcados con * son obligatorios</b>
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
<!--end of the Score modal-->

<!-- delete modal -->
<div class="modal" id="deleteModal">
    <div class="modal-dialog">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Borrar Rregistro</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>


        <!-- Modal body -->
        <form id="deleteForm">
            @method('DELETE')

            <div class="modal-body">
            <h4>¿Deseas borrar el juego # <mark><span class="text-blod" id="row_name"></span></mark>?</h4>
            </div>
            {{ csrf_field() }}

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-lg">
                    <span class="fa fa-check"></span>
                    {{__('Delete')}}
                </button>
                <button type="button" class="btn btn-primary btn-lg" class="close" data-dismiss="modal">
                    <span class="fa fa-remove"></span> {{__('Cancel')}}
                </button>
            </div>
        </form>

      </div>
    </div>
  </div>
<!-- end of the delete modal -->


<!-- delete modal -->
<div class="modal fade" id="historyModal">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">{{ __('Registry game logs') }}</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
            <div class="modal-body">
                <div class="row mb-1">
                    <div class="col-4 text-center">
                        <h3><span id="team1_name" >Equipo 1</span> (Casa)</h3>
                    </div>
                    <div class="col-4 text-center">
                        <h2><span id="score_team1_h">0 </span> -  <span id="score_team2_h">0</span> </h2>
                    </div>
                    <div class="col-4 text-center">
                        <h3><span id="team2_name" >Equipo 2</span> (Casa)</h3>
                    </div>
                </div>
                <hr />

                <div class="row mb-1">
                    <div class="col-12 text-left">

                        <form id="frmAddAction">
                            <input type="text" name="game_id" id="game_id">
                            <input type="text" name="tournament_id" id="tournament_id">

                            <div class="row">
                                <div class="col-3 text-left">
                                    <div class="form-group">
                                        <label for="team">Team</label>
                                        <select class="form-control" id="cboTeam" name="cboTeam">
                                            <option value="">{{ __('Select') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-3 text-left">
                                    <div class="form-group">
                                        <label for="player">Player</label>
                                        <select class="form-control" id="cboPlayer" name="cboPlayer">
                                            <option value="">{{ __('Select') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-3 text-left">
                                    <div class="form-group">
                                        <label for="action">Action</label>
                                        <select class="form-control" id="cboAction" name="cboAction">
                                            <option value="">{{ __('Select') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-3 text-left d-flex flex-row  mt-auto">
                                        <button type="submit" class="btn btn-primary">  <span class="fa fa-save"></span> {{ __('Save') }}</button>
                                </div>

                            </div>
                            {{ csrf_field() }}

                        </form>

                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Player Name') }}</th>
                                    <th>{{ __('Action') }}</th>
                                    <th>{{ __('Affect score') }}</th>
                                    <th> - </th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td colspan="5">{{ __('There are not actions registered yet') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>


            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-lg" class="close" data-dismiss="modal">
                    <span class="fa fa-remove"></span> {{__('Close')}}
                </button>
            </div>


      </div>
    </div>
  </div>
<!-- end of the delete modal -->

@endsection

@section('js')
<script type="text/javascript">
        jQuery(document).ready(function($){

            fetch_data();

            function fetch_data(tournament = '') {

            $('#table').DataTable({
                order: [[10, 'desc']],
                "language": {
                    "sProcessing":    "Procesando...",
                    "sLengthMenu":    "Mostrar _MENU_ registros",
                    "sZeroRecords":   "No se encontraron resultados ",
                    "sEmptyTable":    "No se encontró información / Selecciona un torneo",
                    "sInfo":          "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty":     "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered":  "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix":   "",
                    "sSearch":        "Buscar:",
                    "sUrl":           "",
                    "sInfoThousands":  ",",
                    "sLoadingRecords": "...Cargando...",
                    "oPaginate": {
                        "sFirst":    "Primero",
                        "sLast":    "Último",
                        "sNext":    "Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "oAria": {
                        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('games.index') }}',
                    data: { tournament: tournament },
                },
                columns: [
                    { data: 'tournament_id', name: 'tournament_id', visible:false},
                    { data: 'journey_id', name: 'journey_id', visible:false},
                    { data: 'date_time', name: 'date_time'},
                    { data: 'access_code', name: 'access_code'},
                    { data: 'team1', name: 'team1', className: "text-right"},
                    { data: 'score', name: 'score', className: "text-center"},
                    { data: 'team2', name: 'team2'},
                    { data: 'branch', name: 'branch'},
                    { data: 'category', name: 'category'},
                    { data: 'category_id', name: 'category_id', visible:false},
                    { data: 'type', name: 'type'},
                    { data: 'stadium', name: 'stadium'},
                    { data: 'status', name: 'status'},
                    { data: 'id', name: 'id', visible:false},
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        }

            //filters
            $(document).on("change", "#filterTournaments", function () {
                //Table.columns(0).search(this.value).draw();
                    //var tournament = $('#filterTournaments').val();
                    var tournament=$(this).val();

                    $('#table').DataTable().destroy();
                    fetch_data(tournament);


                    if (tournament =="") {
                        $("#filterCategories").empty();
                        $("#filterCategories").html('<option value="">Selecciona un torneo</option>');
                        $("#filterJourneys").empty();
                        $("#filterJourneys").html('<option valu="">Selecciona un torneo</option>');
                    } else {
                        getJourneys("filterJourneys",tournament,"");
                        getCategories("filterCategories",tournament);
                    }

            });

            $(document).on("change", "#filterJourneys", function () {
                //Table.columns(1).search(this.value).draw();
                $('#table').DataTable().columns(1).search(this.value).draw();
            });


            $(document).on("change", "#filterBranchs", function () {
                //Table.columns(6).search(this.value).draw();
                $('#table').DataTable().columns(7).search(this.value).draw();

            });

            $(document).on("change", "#filterCategories", function () {
                //Table.columns(7).search(this.value).draw();
                $('#table').DataTable().columns(9).search(this.value).draw();

            });


            $(document).on("click", "#btnAdd", function () {
                $("#updateForm").trigger('reset');
                $("#id").val("");

                var tournament = $('#filterTournaments option:selected').val();
                var journeySelected = $('#filterJourneys option:selected').val();
                var branch = $('#filterBranchs option:selected').val();
                var category = $('#filterCategories option:selected').val();

                $('#tournament_id').val(tournament);
                // $('#tournament').html($('#filterTournaments option:selected').text());

                $("#journey_id").html('<option valu="">Selecciona un torneo</option>');
                getJourneys("journey_id",tournament,journeySelected);

                $('#branch').val(branch);

                //journey
                //$('#journey_id').val($('#filterJourneys option:selected').val());
                // $('#journey').html($('#filterJourneys option:selected').text());

                $("#sport_id").val("{{ Auth::user()->league->sport->id}}");
                //$("#category_id").html(getCategories("{{ Auth::user()->id}}"));
                getCategories("category_id",tournament);


                jQuery.noConflict();
                jQuery("#formModal").modal('show');
            });

            $(document).on("change", "#tournament_id", function (e) {
                $("#tournament_id option:selected").each(function () {
                    var tournament=$(this).val();

                    if (tournament =="")
                    {
                        $('#category_id').attr('required', true);
                        $('#branch').val('');
                        $("#category_id").empty();
                        $("#category_id").html('<option value="">Selecciona un torneo</option>');
                        $("#journey_id").empty();
                        $("#journey_id").html('<option valu="">Selecciona un torneo</option>');

                    }
                    else
                    {
                        $("#journey_id").html('<option valu="">Selecciona un torneo</option>');
                        getJourneys("journey_id",tournament,"");
                        getCategories("category_id",tournament);

                    }

                });
            });

            //branch
            $(document).on("change", "#branch", function (e) {
                const branch = $('#branch option:selected').val();
                $('#category_id').attr('required', true);

                $("#tournament_id option:selected").each(function () {
                    var tournament=$(this).val();

                    if (tournament =="") {
                        $('#category_id').attr('required', true);
                        $("#team1_id").empty();
                        $("#team1_id").html('<option value="">Selecciona un torneo</option>');
                        $("#team2_id").empty();
                        $("#team2_id").html('<option valu="">Selecciona un torneo</option>');

                    } else {
                        if (branch.length > 0) {
                        $('#category_id').attr('required', false);
                            getTeamsByBranch([ "team1_id", 'team2_id'], tournament, branch);
                        }
                    }

                });
            });

            $(document).on("change", "#category_id", function (e) {

                const branch = $('#branch option:selected').val();

                if (branch === '') {
                    $.notify("Selecciona una Rama", "error");
                    return;
                }

                $("#category_id option:selected").each(function () {
                    categoryId=$(this).val();

                    //console.log("el valor de categoryId" , categoryId);

                    $("#team1_id").html('<option valu="">Selecciona torneo, rama y cateogria</option>');
                    $("#team2_id").html('<option valu="">Selecciona torneo, rama y cateogria</option>');

                    //getTeams("team1_id",categoryId);
                    //getTeams("team2_id",categoryId);
                    getTeamsByCategoryAndBranch("team1_id", categoryId, branch );
                    getTeamsByCategoryAndBranch("team2_id", categoryId, branch );

                });
            });

            $(document).on("click", "#btnScore", function (e) {

                e.preventDefault();

                let penalties = $(this).attr("data-penalties");

                $("#scoreForm").trigger('reset');

                $("#idscore").val($(this).attr("data-id"));
                $("#notes").val($(this).attr("data-notes"));
                $("#status").val($(this).attr("data-status"));

                $("#team1score").html($(this).attr("data-team1"));
                $("#team2score").html($(this).attr("data-team2"));

                $("#score_team1").val($(this).attr("data-score_team1"));
                $("#score_team2").val($(this).attr("data-score_team2"));

                $("#score_penalties_team1").val($(this).attr("data-score_penalties_team1"));
                $("#score_penalties_team2").val($(this).attr("data-score_penalties_team2"));

                $("#team1scorePenalties").html($(this).attr("data-team1"));
                $("#team2scorePenalties").html($(this).attr("data-team2"));

                $("#team1scorePenalties").html($(this).attr("data-team1"));
                $("#team2scorePenalties").html($(this).attr("data-team2"));

                if (penalties === 'Si') {
                    $("#score_penalties_team1").attr("required", "true");
                    $("#score_penalties_team2").attr("required", "true");
                } else {
                    $("#score_penalties_team1").attr("required", "false");
                    $("#score_penalties_team2").attr("required", "false");
                }



                (penalties === 'Si') ? $('#penalties').show() : $('#penalties').hide();

                jQuery.noConflict();
                jQuery("#scoreModal").modal('show');

            });


            $(document).on("click", "#btnDelete", function (e) {
                e.preventDefault();

                $('#row_name').text($(this).attr('data-name'));
                $('#deleteForm').attr('action', $(this).attr('href'));
                jQuery("#deleteModal").modal('show');
            });

            $(document).on("click", "#btnUpdate", function (e) {
                e.preventDefault();
                $("#updateForm").trigger('reset');

                $.ajax({
                    type: "GET",
                    url: $(this).attr("href"),
                    dataType: "json",
                    success: function (data) {
                        console.log(data)
                        var tournament_id = data["tournament_id"];
                        var journey_id = data["journey_id"];
                        var category_id = data["category_id"];
                        var team1_id = data["team1_id"];
                        var team2_id = data["team2_id"];

                        getJourneys("journey_id", tournament_id, journey_id);
                        getCategories("category_id", tournament_id, category_id);

                        $("#team1_id").html('<option valu="">Selecciona torneo, rama y cateogria</option>');
                        $("#team2_id").html('<option valu="">Selecciona torneo, rama y cateogria</option>');

                        getTeams("team1_id",category_id, team1_id);
                        getTeams("team2_id",category_id, team2_id);

                        for(obj in data) {
                            $("#" + obj).val(data[obj]);
                        }

                        jQuery.noConflict();
                        jQuery("#formModal").modal('show');
                    },
                });

                return false;
            });

            $(document).on("click", "#btnRefresh", function () {
                //Table.ajax.reload();
                $('#table').DataTable().ajax.reload();
            });


            $(document).on("submit", "#scoreForm", function (e) {

                e.preventDefault();

                //I need to validate if the penalties flag is checked
                // if ($('#score_penalties_team1').val() === $('#score_penalties_team2').val() ) {
                //     $.notify('El resultado en panales no puede ser igual', 'error');
                //     return false;
                // }

                $.ajax({
                    type: "POST",
                    url: "{{ url('score_game') }}",
                    dataType:'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    data: new FormData(this),
                    success: function (data) {
                        if (data.type == "error") {

                            $.notify(data.message + "\n" + errors, data.type, { position:"center" } );
                            return false;
                        }

                        $("#scoreForm").trigger('reset');

                        jQuery.noConflict();
                        jQuery("#scoreModal").modal('toggle');
                        // Table.ajax.reload();
                        $('#table').DataTable().ajax.reload();

                        $.notify(data.message, data.type);

                    },
                    error:function(){
                        $.notify("Hubo un error al intentar capturar el resultado del juego", "error");
                    },
                });

                return false;

            });



            $(document).on("submit", "#updateForm", function (e) {
                e.preventDefault();

                $.ajax({
                    type: "POST",
                    url: "{{ route('games.store') }}",
                    dataType:'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    data: new FormData(this),
                    success: function (data) {
                        if (data.type == "error") {
                            // var errors = "";
                            // $.each(data.errors, function (i, val) {
                            //     errors = errors  +  val + "\n";
                            // });

                            $.notify(data.message + "\n" + data.errors, data.type, { position:"center" } );

                            return false;
                        }

                        $("#updateForm").trigger('reset');

                        jQuery.noConflict();
                        jQuery("#formModal").modal('toggle');
                        // Table.ajax.reload();
                        $('#table').DataTable().ajax.reload();
                        $.notify(data.message, data.type);

                    },
                    error:function(){
                        $.notify("Hubo un error al intentar agregar el registro", "error");
                    },
                });

                return false;
            });

            $(document).on("submit", "#deleteForm", function (e) {
                e.preventDefault();

                $.ajax({
                    type: "POST",
                    url: $(this).attr("action"),
                    dataType: "json",
                    data: $(this).serialize(),
                    success: function (data) {
                        if (data.type == "error") {
                            $.notify(data.message + "\n" + errors, data.type);
                            return false;
                        }

                        $("#deleteForm").trigger('reset');
                        jQuery.noConflict();
                        jQuery("#deleteModal").modal('toggle');
                        // Table.ajax.reload();
                        $('#table').DataTable().ajax.reload();

                        $.notify(data.message, data.type);

                    },
                    error:function(){
                        $.notify("Hubo un error al intentar borrar el registro", "error");
                    },
                });

                return false;
            });

            //btnHistory
            $(document).on("click", "#btnHistory", function (e) {
                e.preventDefault();

                //$('#historyForm').attr('action', $(this).attr('href'));
                let team1_id = $(this).attr("data-team1_id");
                let team1_name = $(this).attr("data-team1");
                let team2_id = $(this).attr("data-team2_id");
                let team2_name = $(this).attr("data-team2");


                $("#idgame").val($(this).attr("data-id"));
                $("#notes").val($(this).attr("data-notes"));
                $("#status").val($(this).attr("data-status"));

                $("#team1_name").html($(this).attr("data-team1"));
                $("#team2_name").html($(this).attr("data-team2"));

                $("#score_team1_h").html($(this).attr("data-score_team1"));
                $("#score_team2_h").html($(this).attr("data-score_team2"));

                //populate cboTeam dropdown
                $("#cboTeam").empty();
                $("#cboTeam").append('<option value="">{{ __("Select") }}</option>');
                $("#cboTeam").append('<option value="' + team1_id + '">' + team1_name + '</option>');
                $("#cboTeam").append('<option value="' + team2_id + '">' + team2_name + '</option>');


                $("#score_penalties_team1").val($(this).attr("data-score_penalties_team1"));
                $("#score_penalties_team2").val($(this).attr("data-score_penalties_team2"));

                $("#team1scorePenalties").html($(this).attr("data-team1"));
                $("#team2scorePenalties").html($(this).attr("data-team2"));

                $("#team1scorePenalties").html($(this).attr("data-team1"));
                $("#team2scorePenalties").html($(this).attr("data-team2"));

                if (penalties === 'Si') {
                    $("#score_penalties_team1").attr("required", "true");
                    $("#score_penalties_team2").attr("required", "true");
                } else {
                    $("#score_penalties_team1").attr("required", "false");
                    $("#score_penalties_team2").attr("required", "false");
                }

                (penalties === 'Si') ? $('#penalties').show() : $('#penalties').hide();
                jQuery("#historyModal").modal('show');
            });


            $(document).on("change", "#cboTeam", function (e) {
                let teamId = $(this).val();

                if (teamId == "") {
                    $("#cboPlayer").empty();
                    $("#cboPlayer").html("<option value=''>{{ __('Select a Team') }}</option>");
                } else {
                    //getPlayers('cboPlayer', teamId,'');
                }
            });


        });

</script>
@endsection
