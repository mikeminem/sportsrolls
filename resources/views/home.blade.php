@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                {{-- <div class="card-header">Administrador de tu Liga</div> --}}

                <div class="card-body">
                    @if (!$hasLeague)
                        <div class="alert alert-danger">
                            <i class="fa fa-remove tool"> </i>
                            <strong>Aviso!</strong> No has ingresado los datos de la liga deportiva
                            <a href=" {{ url('league_info') }}"  class="btn btn-danger" title=""> Capturar Informaciòn de la liga </a>
                        </div>
                    @endif

                    @if (!$hasProfile)
                    <div class="alert alert-danger">
                        <i class="fa fa-remove tool"> </i> <strong> Aviso!</strong>{{ $hasProfile }} No has ingresado de tu perfil personal
                            <a href=" {{ url('my_profile') }}"  class="btn btn-danger" title=""> Capturar Perfil </a>
                    </div>
                    @endif

                    @if (Auth::user()->hasRole('promoter'))
                        @include('components.features',['links'=>1])

                        <div class="row">
                            <dov class="col-3">
                                <div class="form-group">
                                    <label for="filterTournaments"><b>Torneos Activos</b></label>
                                    <select class="form-control" required id="filterTournaments" name="filterTournaments"  >
                                        <option value="">Selecciona...</option>
                                        @foreach($tournaments as $tournament)
                                            <option value="{{ $tournament->id}}">{{ $tournament->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </dov>

                            <dov class="col-3">
                                <div class="form-group">
                                    <label for="filterCategories"><b>Categorías</b></label>
                                    <select class="form-control" required id="filterCategories" name="filterCategories"  >
                                        <option value="">Selecciona un torneo...</option>
                                    </select>
                                </div>
                            </dov>

                            <dov class="col-3">
                                <div class="form-group">
                                    <label for="filterTeams"><b>Equipos</b></label>
                                    <select class="form-control" required id="filterTeams" name="filterTeams"  >
                                        <option value="">Selecciona un torneo...</option>
                                    </select>
                                </div>
                            </dov>

                            <dov class="col-3 align-self-center text-center">
                                    <button class="btn btn-success btn-lg" id="btnGenerateGames">
                                        <span class="fa fa-cogs"></span>
                                        Generar Juegos
                                    </button>
                            </dov>

                        </div>
                    @endif

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
@endsection


@section('js')

<script type="text/javascript">
        jQuery(document).ready(function($){

            fetch_data();

            function fetch_data(tournament = '') {
                $('#btnGenerateGames').attr('disabled', true);

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
                    var tournament=$(this).val();

                    $('#table').DataTable().destroy();
                    fetch_data(tournament);


                    if (tournament =="") {
                        $('#btnGenerateGames').attr('disabled', false);

                        $("#filterCategories").empty();
                        $("#filterCategories").html('<option value="">Selecciona un torneo...</option>');
                        $("#filterTeams").empty();
                        $("#filterTeams").html('<option valu="">Selecciona un torneo...</option>');
                    } else {
                        $('#btnGenerateGames').attr('disabled', true);

                        getTeams("filterTeams",tournament,"");
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

                    if (tournament =="") {
                        $('#category_id').attr('required', true);
                        $('#branch').val('');
                        $("#category_id").empty();
                        $("#category_id").html('<option value="">Selecciona un torneo</option>');
                        $("#journey_id").empty();
                        $("#journey_id").html('<option valu="">Selecciona un torneo</option>');

                    } else {

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

                    $("#group_id").empty();
                    $("#group_id").html('<option value="">Selecciona una Categoría</option>');

                    getGroups(['group_id'], categoryId);

                    $("#team1_id").html('<option valu="">Selecciona torneo, rama y cateogria</option>');
                    $("#team2_id").html('<option valu="">Selecciona torneo, rama y cateogria</option>');

                    //getTeams("team1_id",categoryId);
                    //getTeams("team2_id",categoryId);
                    getTeamsByCategoryAndBranch("team1_id", categoryId, branch );
                    getTeamsByCategoryAndBranch("team2_id", categoryId, branch );

                });
            });

               $(document).on("change", "#group_id", function () {

                let group_id = $(this).val();
                $("#team1_id").html('<option valu="">Selecciona torneo, rama y cateogria</option>');
                $("#team2_id").html('<option valu="">Selecciona torneo, rama y cateogria</option>');


                getTeamsByGroup(['team1_id'], group_id );
                getTeamsByGroup(['team2_id'], group_id );
            });

            $(document).on("click", "#btnScore", function (e) {

                e.preventDefault();

                let penalties = $(this).attr("data-penalties");

                $("#scoreForm").trigger('reset');

                $("#idscore").val($(this).attr("data-id"));
                $("#notes").val($(this).attr("data-notes"));
                $("#status").val($(this).attr("data-status"));
                $("#period").val($(this).attr("data-period"));

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

            $(document).on("click", "#btnGames", function (e) {
                $('#tableGames tbody').empty();

                $.ajax({
                    type: "GET",
                    url: "{{ url('get_team_games') }}/" + $(this).attr('data-id'),
                    dataType: "json",
                    success: function (data) {

                        for(gameRows in data) {

                            //console.log(data[gameRows]);
                            let gameRow = '';
                            gameRow = '<tr>';

                            gameRow += '<td>' + data[gameRows]['journey']
                                + '<br> <small>' + data[gameRows]['game_day'] + ' ' + data[gameRows]['game_time']
                                + '</small></td>';
                            gameRow += '<td>' + data[gameRows]['team1'] + '</td>';
                            gameRow += '<td>' + data[gameRows]['score1'] + ' -  ' + data[gameRows]['score2'] + '</td>';
                            gameRow += '<td>' + data[gameRows]['team2'] + '</td>';
                            gameRow += '<td>' + data[gameRows]['result'] + '</td>';

                            gameRow += '</tr>'
                            console.log(gameRow)
                            $("#tableGames tbody").append( gameRow )
                        }

                        jQuery.noConflict();
                        jQuery("#formGamesModal").modal('show');

                    },
                });
                return false;

            });

        });

</script>
@endsection
