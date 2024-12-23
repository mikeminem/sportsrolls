@extends('layouts.app')

@section('title', config('app.name') ." :: " .  __('Roster') )
@section('description',  "Administrador de Roster ")

@section('content')

<div class="container">
    <div class="row py-2">
        <div class="col-md-12">

            <div class="card">
                <div class="card-body">
                    <div class="text-left">
                        <h1>{{ __('Roster')}}</h1>
                    </div>

                    @if (Auth::check() &&  ( (Auth::user()->role == 'Admin') || (Auth::user()->role == 'Promoter') ))
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
                                        <option value="">{{ __("Select a Tournament") }}...</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label for="filterTeams"><b>{{ __("Teams") }}</b></label>
                                    <select class="form-control" required id="filterTeams" name="filterTeams"  >
                                        <option value="">{{ __("Select a Tournament") }}...</option>
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
                                    <th>{{ __("Team Name") }}</th>
                                    <th>{{ __("Photo") }}</th>
                                    <th>{{ __("Player") }}</th>
                                    <th>{{ __("Phone") }}</th>
                                    <th>{{ __("Call in case of emergency") }}</th>
                                    <th>{{ __("Status") }}</th>
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
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"> {{ __("Player information") }}</h4>
                <button type="button" class="close" data-dismiss="modal" data-backdrop="false">&times;</button>
            </div>
            <!--mandamos la forma-->
            <form id="updateForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="title"><b>{{ __("Tournament") }} *</b></label>
                                <select class="form-control" required id="tournament_id" name="tournament_id">
                                    <option value="">Selecciona</option>
                                    @foreach($tournaments as $tournament)
                                        <option value="{{ $tournament->id}}">{{ $tournament->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="title"><b>{{ __("Category") }} *</b></label>
                                <select class="form-control" required id="category_id" name="category_id">
                                    <option value="">{{ __("Select a Tournament") }}</option>

                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="title"><b>{{ __("Teams") }} *</b></label>
                                <select class="form-control" required id="team_id" name="team_id">
                                    <option value="">{{ __("Select a Tournament") }}</option>

                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="name"><b>{{ __("Name") }} *</b></label>
                                <input type="text" class="form-control" id="name" name="name" required maxlength="45">
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="last_name"><b>{{ __("Last Name") }} *</b></label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required maxlength="45">
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="number"><b>{{ __("Number in T-shirt") }} *</b></label>
                                <input type="text" class="form-control" id="number" name="number" required maxlength="10">
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="phone"><b>{{ __("Phone Number") }} *</b></label>
                                <input type="text" class="form-control" id="phone" name="phone" required maxlength="10">
                            </div>
                        </div>


                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="dob"><b>{{ __("DOB") }}</b></label>
                                <input type="date" class="form-control" id="dob" name="dob" required >

                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12">
                            <p><b>{{ __("Notify in case of emergency") }}</b></p>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="emergency_call"><b>{{ __("Emergency Name") }}</b></label>
                                <input type="text" class="form-control" id="emergency_call" name="emergency_call" maxlength="80">
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label for="emergency_phone"><b>{{ __("Emergency Phone") }}</b></label>
                                <input type="text" class="form-control" id="emergency_phone" name="emergency_phone" maxlength="80">
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label for="status"><b>{{ __("Status") }}</b></label>
                                <select id="status" name="status" class="form-control" required>
                                    <option value="Active">{{ __('Active') }}</option>
                                    <option value="Inactive">{{ __('InActive') }}</option>
                                </select>
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
                        { data: 'team', name: 'team'},
                        { data: 'photo', name: 'photo'},
                        { data: 'player', name: 'player'},
                        { data: 'phone', name: 'phone'},
                        { data: 'emergency_call', name: 'emergency_call'},
                        { data: 'status', name: 'status'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ]
                });
            }


            $(document).on("change", "#filterTournaments", function () {
                var tournament = $('#filterTournaments').val();
                $('#table').DataTable().destroy();
                fetch_data(tournament);

                var tournament=$(this).val();
                if (tournament =="") {
                    $("#filterCategories").empty();
                    $("#filterCategories").html('<option value="">{{ __("Select a Tournament") }}</option>');

                    $("#filterTeams").empty();
                    $("#filterTeams").html('<option value="">{{ __("Select a Tournament") }}</option>');

                    $("#category_id").empty();
                    $("#category_id").html('<option value="">{{ __("Select a Tournament") }}</option>');
                    $("#team_id").empty();
                    $("#team_id").html('<option value="">{{ __("Select a Tournament") }}</option>');



                } else {
                    getCategories("filterCategories",tournament);
                    getCategories("category_id",tournament);
                }
            });

            //tournament_id
            $(document).on("change", "#tournament_id", function () {
                var tournament = $('#tournament_id').val();

                var tournament=$(this).val();
                if (tournament =="") {
                    $("#category_id").empty();
                    $("#category_id").html('<option value="">{{ __("Select a Tournament") }}</option>');
                    $("#team_id").empty();
                    $("#team_id").html('<option value="">{{ __("Select a Tournament") }}</option>');

                } else {
                    getCategories("category_id",tournament);
                }
            });


            $(document).on("change", "#filterCategories", function (e) {
                //$('#table').DataTable().columns(1).search(this.value).draw();

                $("#filterCategories option:selected").each(function () {
                    categoryId=$(this).val();
                    $("#filterTeams").html('<option valu="">{{ __("Select a Tournament and Category") }}</option>');
                    getTeams("filterTeams",categoryId);

                    $("#team_id").html('<option valu="">{{ __("Select a Tournament and Category") }}</option>');
                    getTeams("team_id",categoryId);


                });
            });

            $(document).on("change", "#category_id", function (e) {
                //$('#table').DataTable().columns(1).search(this.value).draw();

                $("#category_id option:selected").each(function () {
                    categoryId=$(this).val();
                    $("#team_id").html('<option valu="">{{ __("Select a Tournament and Category") }}</option>');
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

                let tournament = $('#filterTournaments').val();
                if (tournament !== "") {
                    $('#tournament_id').val(tournament);

                    category = $('#filterCategories').val();

                    if (category !==  "") {
                        getCategories("category_id",tournament, category);
                        getTeams("team_id",category);
                    }

                }

                //$("#sport").val("{{ Auth::user()->league->sport->name}}");
                //$("#sport_id").val("{{ Auth::user()->league->sport->id}}");

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
                            $("#" + obj).val(data[obj]);

                            if (obj == "tournament_id"){
                                tournament_id = data[obj];
                            }

                            if (obj == "image") {
                                image = data[obj];
                                console.log('image', image);
                                if (image) {
                                    $("#player_photo").attr("src","players_photos/" + image);
                                } else {
                                    $("#player_photo").attr("src","");
                                    $("#player_photo").attr("src","images/No-photo-m.png");
                                }
                            }

                            if (obj == 'category_id') {
                                category_id = data[obj];
                                //getTeams("team_id",category_id);
                            }
                        }
                        getTeams("team_id",category_id, data['team_id']);

                        //$("#sport").val("{{ Auth::user()->league->sport->name}}");
                        //$("#sport_id").val("{{ Auth::user()->league->sport->id}}");

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
                            $.notify(data.message + "\n" + errors, data.type);
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
