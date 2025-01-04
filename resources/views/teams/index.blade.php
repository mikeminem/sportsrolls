@extends('layouts.app')

@section('title', config('app.name') ." :: " .  __('Teams') )
@section('description',  "Administrador de Deportes ")

@section('content')

<div class="container">
    <div class="row py-2">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="text-left">
                        <h1>{{ __('Teams')}}</h1>
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
                                    <option value="">Selecciona...</option>
                                    @foreach($tournaments as $tournament)
                                        <option value="{{ $tournament->id}}">{{ $tournament->title}}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">

                            <div class="form-group">
                                <label for="filterBranch"><b>Rama</b></label>
                                <select class="form-control" required id="filterBranch" name="filterBranch"  >
                                    <option value="">Selecciona</option>
                                    <option value="Varonil">Varonil</option>
                                    <option value="Femenil">Femenil</option>
                                    <option value="Mixto">Mixto</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label for="filterCategories"><b>Categorías</b></label>
                                <select class="form-control" required id="filterCategories" name="filterCategories"  >
                                    <option value="">Selecciona un torneo</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label for="filterGroups"><b>Grupos</b></label>
                                <select class="form-control" required id="filterGroups" name="filterGroups"  >
                                    <option value="">Selecciona una Categorìa</option>
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
                                    <th>Id Categoria</th>
                                    <th>Id Grupo</th>
                                    <th style="width: 200px">Nombre</th>
                                    <th>Contacto</th>
                                    <th>Rama</th>
                                    <th>Categoría</th>
                                    <th>Grupo</th>
                                    <th>Estado</th>
                                    <th width="20%"> - </th>
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
    <div class="modal-dialog modal-lg" style="width: 90% !important">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"> Datos del Equipo</h4>
                <button type="button" class="close" data-dismiss="modal" data-backdrop="false">&times;</button>
            </div>
            <!--mandamos la forma-->
            <form id="Form" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="name"><b>Nombre *</b></label>
                                <input type="text" class="form-control" id="name" name="name" required maxlength="120">
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
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

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label for="title"><b>Deporte *</b></label>
                                <input type="text" class="form-control" readonly id="sport" name="sport">
                                <input type="hidden" class="form-control" readonly id="sport_id" name="sport_id">
                            </div>
                        </div>




                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="title"><b>Torneo *</b></label>
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
                                <label for="title"><b>Categoría *</b></label>
                                <select class="form-control" required id="category_id" name="category_id">
                                    <option value="">Selecciona un Torneo</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="group_id">Grupo</label>
                                <select class="form-control" id="group_id" name="group_id"  >
                                    <option value="Todos">Selecciona un torneo</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-9 col-sm-12">
                            <div class="form-group">
                                <label for="contact"><b>Nombre Contacto </b></label>
                                <input type="text" class="form-control" id="contact" name="contact"  maxlength="120">
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label for="contact_phone"><b>Teléfono Contacto </b></label>
                                <input type="text" class="form-control" id="contact_phone" name="contact_phone"  maxlength="10">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="logo">Logo del Equipo</label>
                                <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                                <small><b>Tamaño de la imagene recomendada 200 x 200 px</b></small>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="photo">Foto del Equipo</label>
                                <input type="file" class="form-control" id="photo" name="photo" accept="image/*" onchange="preview()">
                                <small><b>Tamaño de la imagene recomendada 800 x 600 px</b></small>
                                {{-- <img id="teamPhoto" src="" width="400px" height="300px"/> --}}

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
          <h4 class="modal-title">Borrar Equipo</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>


        <!-- Modal body -->
        <form id="deleteForm">
            @method('DELETE')

            <div class="modal-body">
            <h4>¿Deseas borrar  <mark><span class="text-blod" id="row_name"></span></mark>?</h4>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css" />



<script type="text/javascript">
        function preview() {
            teamPhoto.src=URL.createObjectURL(event.target.files[0]);
        }

    jQuery(document).ready(function($){

        fetch_data();

        function fetch_data(tournament = '') {

            $('#table').DataTable({
                "language": {
                    "sProcessing":    "Procesando...",
                    "sLengthMenu":    "Mostrar _MENU_ registros",
                    "sZeroRecords":   "No se encontraron resultados",
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
                    url: '{{ route('teams.index') }}',
                    data: { tournament: tournament },
                },
                columns: [
                    { data: 'tournament_id', name: 'tournament_id', visible:false},
                    { data: 'category_id', name: 'category_id', visible:false},
                    { data: 'group_id', name: 'group_id', visible:false},
                    { data: 'name', name: 'name'},
                    { data: 'contact', name: 'contact'},
                    { data: 'branch', name: 'branch'},
                    { data: 'category', name: 'category'},
                    { data: 'group', name: 'group'},
                    { data: 'status', name: 'status'},
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        }

            $(document).on("change", "#filterTournaments", function () {
                //Table.columns(0).search(this.value).draw();
                var tournament = $('#filterTournaments').val();
                $('#table').DataTable().destroy();
                fetch_data(tournament);

                //we send the categories
                var tournament=$(this).val();
                    if (tournament =="") {
                        $("#filterCategories").empty();
                        $("#filterCategories").html('<option value="">Selecciona un torneo</option>');

                        // $("#filterGroups").empty();
                        // $("#filterGroups").html('<option value="">Selecciona un torneo</option>');
                    } else {
                        getCategories("filterCategories",tournament);
                        //getGroups(['filterGroups'],tournament);
                    }
            });

            $(document).on("change", "#filterBranch", function () {
                $('#table').DataTable().columns(5).search(this.value).draw();
            });

            $(document).on("change", "#filterCategories", function () {
                $('#table').DataTable().columns(1).search(this.value).draw();

                //get the groups, is they exists
                var category = $('#filterCategories').val();

                //we send the categories
                var category=$(this).val();
                $("#filterGroups").empty();
                $("#filterGroups").html('<option value="">Selecciona una Categoría</option>');

                getGroups(['filterGroups'], category);

            });

            $(document).on("change", "#filterGroups", function () {
                $('#table').DataTable().columns(2).search(this.value).draw();
            });

            $(document).on("change", "#tournament_id", function () {
                var tournament = $('#tournament_id option:selected').val();

                if (tournament =="") {
                    $("#category_id").empty();
                    $("#category_id").html('<option value="">Selecciona un Torneo</option>');

                    // $("#group_id").empty();
                    // $("#group_id").html('<option value="">Selecciona un Torneo</option>');
                } else {
                    getCategories("category_id",tournament);
                    //getGroups(['group_id'],tournament);
                }

            });

            $(document).on("change", "#category_id", function () {

                //get the groups, is they exists
                var category = $('#category_id').val();

                //we send the categories
                var category=$(this).val();
                $("#group_id").empty();
                $("#group_id").html('<option value="">Selecciona una Categoría</option>');

                getGroups(['group_id'], category);

            });

            $(document).on("click", "#btnAdd", function () {
                $("#Form").trigger('reset');
                $("#id").val("");
                //$("#teamPhoto").attr('src','');

                let tournament = $('#filterTournaments').val();
                if (tournament != "") {
                    getCategories("category_id",tournament);
                    //getGroups(['group_id'],tournament);
                    $('#tournament_id').val(tournament);
                }



                $("#category_id").empty();
                $("#category_id").html('<option value="">Selecciona un torneo</option>');

                $("#group_id").empty();
                $("#group_id").html('<option value="">Selecciona un torneo</option>');

                $("#sport").val("{{ Auth::user()->league->sport->name}}");
                $("#sport_id").val("{{ Auth::user()->league->sport->id}}");
                //$("#category_id").html(getCategories("{{ Auth::user()->id}}"));
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
                $("#Form").trigger('reset');

                $.ajax({
                    type: "GET",
                    url: $(this).attr("href"),
                    dataType: "json",
                    success: function (data) {
                        var tournament_id = 0;
                        //console.log( data );

                        for(obj in data) {
                            $("#" + obj).val(data[obj]);

                            if (obj == "tournament_id"){
                                tournament_id = data[obj];
                            }
                        }
                        //$("#category_id").html(getCategories({{ Auth::user()->id }}, category_id));
                        getCategories("category_id",tournament_id, data["category_id"]);
                        getGroups(['group_id'],tournament_id, data["group_id"]);

                        $("#sport").val("{{ Auth::user()->league->sport->name}}");
                        $("#sport_id").val("{{ Auth::user()->league->sport->id}}");

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

            $(document).on("submit", "#Form", function (e) {
                e.preventDefault();

                $.ajax({
                    type: "POST",
                    url: "{{ route('teams.store') }}",
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
                        // Table.ajax.reload();
                        $('#table').DataTable().ajax.reload();

                        $.notify(data.message, data.type);

                    },
                    error:function(){
                        $.notify("Hubo un error al intentar agregar el Equipo", "error");
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

        $(document).on("change", "#cboStatus", function () {
            $.ajaxSetup({
                headers: {
                'X-CSSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "GET",
                url: "update_team_status/" + $(this).attr("data-id") + "/" + $(this).val(),
                dataType: "json",
                success: function (data) {
                    $.notify(data.response, data.results);

                },
                error: function() {
                    $.notify("Hubo un error al intentar actualizar el registro", "error");
                },
            });
        });

    });

</script>
@endsection
