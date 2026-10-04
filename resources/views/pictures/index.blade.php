@extends('layouts.app')

@section('title', config('app.name') ." :: " .  __('Pictures') )
@section('description',  "fotografías Administrator ")

@section('content')

<div class="container">
    <div class="row py-2">
        <div class="col-md-12">

            <div class="card">
                <div class="card-body">
                    <div class="text-left">
                        <h1>{{ __('Pictures')}}</h1>
                    </div>

                    @if (Auth::check() &&  ( (Auth::user()->hasRole('admin')) || (Auth::user()->hasRole('promoter')) ))
                        <a role="button"
                            class="btn btn-primary"
                            href="javascript:void(0);"
                            id="AddRow"
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




                    <hr />

                    <div class="table-responsive">
                        <table class="table table-hover" id="table">
                            <thead>
                                <tr>
                                    <th width="20%">{{ __("Photo") }}</th>
                                    <th width="50%">{{ __("Title") }}</th>
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
    <div class="modal-dialog modal-xl w-auto" >
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"> Datos de la Fotografía</h4>
                <button type="button" class="close" data-dismiss="modal" data-backdrop="false">&times;</button>
            </div>
            <!--mandamos la forma-->
            <form id="updateForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-4 col-sm-12">
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

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="journey_id"><b>Jornada *</b></label>
                                <select class="form-control" required id="journey_id" name="journey_id">
                                    <option value="">Selecciona un torneo</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="title"><b>Título *</b></label>
                                <input type="text" class="form-control" id="title" name="title" required maxlength="120">
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="description"><b>Descripción </b></label>
                                <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-12 col-sm-6">
                            <img id="picture_name" src="{{ asset('images/imgNoPicture500x500.jpg') }}" class="img-fluid img-thumbnai" alt="{{ __('Photo') }}" width="300px">
                            <div class="form-group">
                                <label for="photo"><b>Foto a publicar</b></label>
                                <input type="file"
                                    class="form-control"
                                    id="photo"
                                    name="photo"
                                    maxlength="80"
                                    accept="image/*"
                                    oninput="picture_name.src=window.URL.createObjectURL(this.files[0])"
                                >
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
                        "sEmptyTable":    "No se encontró información...",
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
                        url: '{{ route('pictures.index') }}'
                    },
                    columns: [
                        { data: 'photo', name: 'photo'},
                        { data: 'title', name: 'title'},
                        { data: 'status', name: 'status'},
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
                        $("#filterJourneys").empty();
                        $("#filterJourneys").html('<option valu="">Selecciona un torneo</option>');
                    } else {
                        getJourneys("filterJourneys",tournament,"");
                    }

            });

            $(document).on("click", "#AddRow", function () {

                $("#picture_name").attr("src","");
                $("#picture_name").attr("src","images/imgNoPicture500x500.jpg");

                $("#updateForm").trigger('reset');
                $("#id").val("");

                var tournament = $('#filterTournaments option:selected').val();
                var journeySelected = $('#filterJourneys option:selected').val();

                $('#tournament_id').val(tournament);

                $("#journey_id").html('<option valu="">Selecciona un torneo</option>');
                getJourneys("journey_id",tournament,journeySelected);

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

                            if (obj != 'photo') {
                                $("#" + obj).val(data[obj]);
                            }

                            if (obj == "photo") {
                                image = data[obj];
                                if (image) {
                                    console.log(image);
                                    $("#picture_name").attr("src","photos/" + image);
                                } else {
                                    $("#picture_name").attr("src","");
                                    $("#picture_name").attr("src","images/imgNoPicture500x500.jpg");
                                }
                            }

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

            $(document).on("submit", "#updateForm", function (e) {
                e.preventDefault();

                $.ajax({
                    type: "POST",
                    url: "{{ route('pictures.store') }}",
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


            $(document).on("change", "#tournament_id", function (e) {
                $("#tournament_id option:selected").each(function () {
                    var tournament=$(this).val();

                    if (tournament =="") {
                        $("#journey_id").empty();
                        $("#journey_id").html('<option valu="">Selecciona un torneo</option>');

                    } else {
                        $("#journey_id").html('<option valu="">Selecciona un torneo</option>');
                        getJourneys("journey_id",tournament,"");
                    }

                });
            });
        });

</script>
@endsection
