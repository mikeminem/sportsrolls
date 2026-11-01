@extends('layouts.app')

@section('title', config('app.name') ." :: " .  __('Jugadores') )
@section('description',  "Administrador de Jugadores ")

@section('content')

<div class="container">
    <div class="row py-2">
        <div class="col-md-12">

            <div class="card">
                <div class="card-body">
                    <div class="text-left">
                        <h1>Manejo del Roster</h1>
                    </div>

                    @if (Auth::check() &&  ( (Auth::user()->hasRole('admin')) || (Auth::user()->hasRole('promoter')) ))
                        <a role="button"
                            class="btn btn-primary"
                            href="javascript:void(0);"
                            id="AddRoster"
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
                                    <label for="filterTournaments"><b>{{ __("Active Touurnaments") }}</b></label>
                                    <select class="form-control" required id="filterTournaments" name="filterTournaments"  >
                                        <option value="">Selecciona...</option>
                                        @foreach($tournaments as $tournament)
                                            <option value="{{ $tournament->id}}">{{ $tournament->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label for="filterCategories"><b>{{ __("Categories") }}</b></label>
                                    <select class="form-control" required id="filterCategories" name="filterCategories"  >
                                        <option value="">{{ __("Selecciona un torneo") }}...</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label for="filterTeams"><b>{{ __("Teams") }}</b></label>
                                    <select class="form-control" required id="filterTeams" name="filterTeams"  >
                                        <option value="">{{ __("Selecciona un torneo") }}...</option>
                                    </select>
                                </div>
                            </div>

                        </div>

                    <hr />

                    <div class="table-responsive">
                        <table class="table table-hover" id="table">
                            <thead>
                                <tr>
                                    <th>{{ __("Tournament") }}</th>
                                    <th>{{ __("Category") }}</th>
                                    <th>{{ __("Team") }}</th>
                                    <th>{{ __("Foto") }}</th>
                                    <th>{{ __("Equipo") }}</th>
                                    <th width="200px">{{ __("Jugador") }}</th>
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

@include('rosters.form')

<!-- delete modal -->
<div class="modal" id="deleteModal">
    <div class="modal-dialog">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">{{ __("Delete a Row") }}</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>


        <!-- Modal body -->
        <form id="deleteForm">
            @method('DELETE')

            <div class="modal-body">
            <h4>Do you want to delete player <mark><span class="text-blod" id="row_name"></span></mark>?</h4>
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
@endsection

@section('js')
<script type="text/javascript">
        jQuery(document).ready(function($){

            function fetchPlayers(selectedId) {
                const select = $('#player_id');
                select.empty().append(new Option('Cargando jugadores...', ''));
                select.prop('disabled', true);
                $('#updateForm button[type="submit"]').prop('disabled', true);
                return $.getJSON("{{ route('rosters.players') }}")
                    .done(function (players) {
                        select.empty().append(new Option('Selecciona un jugador', ''));
                        players.forEach(function (player) {
                            const option = new Option(player.name + ' ' + (player.last_name || ''), player.id);
                            $(option).data('player', player);
                            select.append(option);
                        });
                        select.prop('disabled', false).val(selectedId || '');
                        select.trigger('change');
                        $('#updateForm button[type="submit"]').prop('disabled', false);
                    })
                    .fail(function () {
                        select.empty().append(new Option('No se pudieron cargar los jugadores', ''));
                        $.notify('No se pudieron cargar los jugadores. Cierra y vuelve a abrir el formulario.', 'error');
                    });
            }

            $('#player_id').on('change', function () {
                const player = $(this).find('option:selected').data('player');
                $('#name').val(player ? player.name : '');
                $('#last_name').val(player ? player.last_name : '');
            });

            fetch_data();

            function fetch_data(tournament = '') {

                $('#table').DataTable({
                    "language": {
                        "sProcessing":    "Procesando...",
                        "sLengthMenu":    "Mostrar _MENU_ registros",
                        "sZeroRecords":   "No se encontraron resultados",
                        "sEmptyTable":    "No se encontró información / Selefcciona un torneo",
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
                        url: '{{ route('rosters.index') }}',
                        data: { tournament: tournament }
                    },
                    columns: [
                        { data: 'tournament', name: 'tournament', visible: false},
                        { data: 'category_id', name: 'category_id', visible: false},
                        { data: 'team_id', name: 'team_id', visible: false},
                        { data: 'photo', name: 'photo'},
                        { data: 'team', name: 'team'},
                        { data: 'player', name: 'player'},
                        {{-- { data: 'license', name: 'license'}, --}}
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ]
                });
            }


            $(document).on("change", "#filterTournaments", function () {
                var tournament = $('#filterTournaments').val();
                $('#table').DataTable().destroy();
                fetch_data(tournament);

                //var tournament=$(this).val();
                if (tournament =="") {
                    //$("#filterCategories").empty();
                    $("#filterCategories").html('<option value="">{{ __("Selecciona un torneo") }}</option>');

                    //$("#filterTeams").empty();
                    $("#filterTeams").html('<option value="">{{ __("Selecciona un torneo") }}</option>');

                    //$("#category_id").empty();
                    $("#category_id").html('<option value="">{{ __("Selecciona un torneo") }}</option>');
                    //$("#team_id").empty();
                    $("#team_id").html('<option value="">{{ __("Selecciona un torneo") }}</option>');

                } else {
                    getCategories("filterCategories",tournament);
                    getCategories("category_id",tournament);
                }
            });

            $(document).on("change", "#tournament_id", function () {
                var tournament = $('#tournament_id').val();
                //var tournament=$(this).val();
                if (tournament =="") {
                    //$("#category_id").empty();
                    $("#category_id").html('<option value="">{{ __("Selecciona un torneo") }}</option>');
                    //$("#team_id").empty();
                    $("#team_id").html('<option value="">{{ __("Selecciona un torneo") }}</option>');

                } else {
                    getCategories("category_id",tournament);
                }
            });

            $(document).on("change", "#filterCategories", function (e) {
                $("#filterCategories option:selected").each(function () {
                    categoryId=$(this).val();
                    $("#filterTeams").html('<option valu="">{{ __("Selecciona un torneo and Category") }}</option>');
                    getTeams("filterTeams",categoryId);

                    $("#team_id").html('<option valu="">{{ __("Selecciona un torneo and Category") }}</option>');
                    getTeams("team_id",categoryId);
                });
            });

            $(document).on("change", "#category_id", function (e) {

                $("#category_id option:selected").each(function () {
                    categoryId=$(this).val();
                    $("#team_id").html('<option valu="">{{ __("Selecciona un torneo and Category") }}</option>');
                    getTeams("team_id",categoryId);
                });
            });

            $(document).on("change", "#filterTeams", function (e) {
                $('#table').DataTable().columns(2).search(this.value).draw();
            });

            $(document).on("click", "#AddRoster", function () {

                $("#player_photo").attr("src","");
                $("#player_photo").attr("src","images/No-photo-m.png");

                $("#updateForm").trigger('reset');
                $("#id").val("");
                fetchPlayers();

                let tournament = $('#filterTournaments').val();

                if (tournament !== "") {
                    $('#tournament_id').val(tournament);

                    let category = $('#filterCategories').val();
                    console.log(category)

                    if (category !==  "") {
                        let team = $('#filterTeams').val();

                        getCategories("category_id",tournament, category);
                        getTeams("team_id",category, team);
                    }

                }

                jQuery.noConflict();
                jQuery("#formModal").modal('show');
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
                        let tournament_id = 0;
                        let category_id = 0;
                        let image = '';

                        for(obj in data) {

                            if (obj == "tournament_id"){
                                tournament_id = data[obj];
                            }

                            if (obj == "photo") {
                                image = data[obj];
                                console.log('image', image);
                                if (image) {
                                    $("#player_photo").attr("src","players_photos/" + image);
                                } else {
                                    $("#player_photo").attr("src","");
                                    $("#player_photo").attr("src","images/No-photo-m.png");
                                }
                            } else {
                                $("#" + obj).val(data[obj]);
                            }

                            if (obj == 'category_id') {
                                category_id = data[obj];
                            }
                        }
                        getCategories("category_id", tournament_id, category_id);
                        getTeams("team_id",category_id, data['team_id']);
                        fetchPlayers(data.player_id);

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

            $(document).on("submit", "#updateForm", function (e) {
                e.preventDefault();

                $.ajax({
                    type: "POST",
                    url: "{{ route('rosters.store') }}",
                    dataType:'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    data: new FormData(this),
                    success: function (data) {
                        if (data.type == "error") {
                            var errors = "";
                            $.each(data.errors, function (i, val) {
                                errors = errors  +  val + "\n";
                            });

                            $.notify(data.message + "\n" + errors, data.type, { position:"center" } );

                            return false;
                        }

                        $("#updateForm").trigger('reset');

                        jQuery.noConflict();
                        jQuery("#formModal").modal('toggle');
                        //Table.ajax.reload();
                        $('#table').DataTable().ajax.reload();
                        $.notify(data.message, data.type);

                    },
                    error:function(){
                        $.notify("There is an error trying to save the player information! FE", "error");
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
                            $.notify(data.message + "\n" + data.errors, data.type);
                            return false;
                        }

                        $("#deleteForm").trigger('reset');

                        jQuery.noConflict();
                        jQuery("#deleteModal").modal('toggle');
                        //Table.ajax.reload();
                        $('#table').DataTable().ajax.reload();

                        $.notify(data.message, data.type);

                    },
                    error:function(){
                        $.notify("Error trying to delete player", "error");
                    },
                });

                return false;
            });
        });

</script>
@endsection
