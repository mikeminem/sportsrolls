@extends('layouts.app')

@section('title', config('app.name') ." :: " .  __('Tornos') )
@section('description',  "Administrador de Torneos ")

@section('content')

<div class="container">
    <div class="row py-2">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="text-left">
                        <h1>{{ __('Torneos')}}</h1>
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

                    <div class="table-responsive">
                        <table class="table table-hover" id="table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Contactos</th>
                                    <th width="10%">Código de Registro</th>
                                    <th>Habilitar registro de Juugadores</th>
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
                <h4 class="modal-title"> Datos del Torneo</h4>
                <button type="button" class="close" data-dismiss="modal" data-backdrop="false">&times;</button>
            </div>
            <!--mandamos la forma-->
            <form id="Form" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">


                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label for="sport">Deporte</label>
                                <input type="text" class="form-control" id="sport" name="sport" readonly>
                                <input type="hidden" class="form-control" id="sport_id" name="sport_id" readonly>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label for="code">Código de Registro</label>
                                <input type="text" class="form-control" id="code" name="code" readonly>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="code">Habilitar Registro de Jugadores</label>
                                <select class="form-control" id="enable_enrollment" name="enable_enrollment" style="width: 100px;">
                                    <option value="Si">Si</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>


                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="title"><b>Nombre del Torneo *</b></label>
                                <input type="text" class="form-control" id="title" name="title" required maxlength="120">
                            </div>
                        </div>


                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="contacts"><b>Contactos *</b></label>
                                <input type="text" class="form-control" id="contacts" name="contacts" required maxlength="120">
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="summary"><b>Descripción *</b></label><br />
                                <textarea class="form-control" id="summary" name="summary" rows="3"  maxlength="240" placeholder="Ingresa la descripción  del torneo" required></textarea>
                            </div>
                        </div>



                    </div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="form-group form-check">
                                <label class="form-check-label">
                                  <input class="form-check-input" type="checkbox" id="enable_points_system" name="enable_points_system"> Habilitar sistema por puntos
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-3 col-sm-6">
                            <div class="form-group">
                                <label for="winner_points">Juego Ganado</label><br />
                                <!--input type="number" class="form-control" id="winner_points" name="winner_points" required maxlength="2" readonly -->
                                <select class="form-control" name="winner_points" id="winner_points" disabled >
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>

                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <div class="form-group">
                                <label for="draw_points">Juego Empatado</label><br />
                                <!--input type="number" class="form-control" id="draw_points" name="draw_points" required maxlength="2" readonly -->
                                <select class="form-control" name="draw_points" id="draw_points" disabled >
                                    <option value="-1">No Mostrar</option>
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>

                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <div class="form-group">
                                <label for="loser_points">Juego Perdido</label><br />
                                <!--input type="number" class="form-control" id="loser_points" name="loser_points" required maxlength="2" readonly -->
                                <select class="form-control" name="loser_points" id="loser_points" disabled >
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="form-group form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" id="enable_penalties_scoring" name="enable_penalties_scoring"> <b>Opciones para futbol (penalties)</b>
                                </label>
                            </div>


                        </div>


                        <div class="col-md-3 col-sm-6">
                            <div class="form-group">
                                <label for="winner_points">Juego Ganado</label><br />
                                <!--input type="number" class="form-control" id="winner_points" name="winner_points" required maxlength="2" readonly -->
                                <select class="form-control" name="winner_points_penalties" id="winner_points_penalties" disabled >
                                    <option value="-1">No Mostrar</option>
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>

                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <div class="form-group">
                                <label for="draw_points">Juego Perdido</label><br />
                                <!--input type="number" class="form-control" id="draw_points" name="draw_points" required maxlength="2" readonly -->
                                <select class="form-control" name="loser_points_penalties" id="loser_points_penalties" disabled >
                                    <option value="-1">No Mostrar</option>
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>

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

<!-- delete modal -->
<div class="modal" id="deleteModal">
    <div class="modal-dialog">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Borrar Torneo</h4>
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



  <!-- delete modal -->
<div class="modal" id="startModal">
    <div class="modal-dialog">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Terminar Torneo</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <form id="startForm">

            <div class="modal-body">
            <h4 id="message"> <mark><span class="text-blod" id="row_title"></span></mark>?</h4>
            <p>
                <div class="alert alert-danger">
                    <strong>Nota!</strong> Si Terminas el torneo, este se archivará y ya no se podrá consultar en los torneos activos.
                  </div>

            </p>
            </div>
            {{ csrf_field() }}
            <input type="hidden" class="form-control" id="idStart" name="idStart" readonly>
            <input type="hidden" class="form-control" id="status" name="status" readonly>


            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-lg">
                    <span class="fa fa-check"></span>
                    {{__('Save')}}
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

            var Table = $('#table').DataTable({
                "order": [4, 'desc'],
                "language": {
                    "sProcessing":    "Procesando...",
                    "sLengthMenu":    "Mostrar _MENU_ registros",
                    "sZeroRecords":   "No se encontraron resultados",
                    "sEmptyTable":    "No se encontró información",
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
                ajax: '{{ route('tournaments.index') }}',
                columns: [
                    { data: 'title', name: 'title'},
                    { data: 'contacts', name: 'contacts'},
                    { data: 'code', name: 'code'},
                    { data: 'enable_enrollment', name: 'enable_enrollment'},
                    { data: 'status', name: 'status'},
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });


            $(document).on("click", "#btnAdd", function () {
                $("#Form").trigger('reset');
                $("#id").val("");
                $("#sport_id").val("{{ Auth::user()->league->sport_id}}");
                $("#sport").val("{{ Auth::user()->league->sport->name}}");

                $('#enable_enrollment').val('No');

                $('#winner_points').attr('disabled', true)
                $('#loser_points').attr('disabled', true)
                $('#draw_points').attr('disabled', true)

                $('#winner_points').val('0');
                $('#loser_points').val('0');
                $('#draw_points').val('-1');

                $('#winner_points_penalties').attr('disabled',true);
                $('#loser_points_penalties').attr('disabled',true);

                $('#winner_points_penalties').val('-1');
                $('#loser_points_penalties').val('-1');


                jQuery.noConflict();
                jQuery("#formModal").modal('show');
            });


            $(document).on("click", "#btnStatus", function (e) {
                e.preventDefault();

                var status = $(this).attr('data-status');
                var name = $(this).attr('data-name');

                $('#row_title').text($(this).attr('data-name'));
                $("#idStart").val($(this).attr('data-id'));
                $("#status").val(status);

                $("#message").text('¿Deseas cambiar el estado del torneo ' + name + ' a '+ status+'?');

                jQuery("#startModal").modal('show');
            });

            $(document).on("change", "#cboEnableEnrollment", function () {
                $.ajaxSetup({
                    headers: {
                    'X-CSSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "GET",
                    url: "/update_tournament_enable_enrollment/" + $(this).attr("data-id") + "/" + $(this).val(),
                    dataType: "json",
                    success: function (data) {
                        $.notify(data.response, data.results);

                    },
                    error: function() {
                        $.notify("Hubo un error al intentar actualizar el registro", "error");
                    },
                });
            });

            $(document).on("click", "#enable_points_system", function (e) {

                if ($(this).is(":checked")) {
                    $('#winner_points').removeAttr('disabled');
                    $('#loser_points').removeAttr('disabled');
                    $('#draw_points').removeAttr('disabled');
                } else {
                    $('#winner_points').attr('disabled', true)
                    $('#loser_points').attr('disabled', true)
                    $('#draw_points').attr('disabled', true)

                    //values
                    $('#winner_points').val('0');
                    $('#loser_points').val('0');
                    $('#draw_points').val('-1');

                }
            });

            $(document).on("click", "#enable_penalties_scoring", function (e) {

                if ($(this).is(":checked")) {
                    $('#winner_points_penalties').removeAttr('disabled');
                    $('#loser_points_penalties').removeAttr('disabled');
                } else {
                    $('#winner_points_penalties').attr('disabled',true);
                    $('#loser_points_penalties').attr('disabled',true);

                    //values
                    $('#winner_points_penalties').val('-1');
                    $('#loser_points_penalties').val('-1');

                }
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
                        //console.log(data)

                        for(obj in data) {
                            $("#" + obj).val(data[obj]);

                            if (obj == "enable_points_system") {
                                if (data[obj] ==="Si") {
                                    $("#enable_points_system").prop('checked', true);
                                    $('#winner_points').removeAttr('disabled');
                                    $('#loser_points').removeAttr('disabled');
                                    $('#draw_points').removeAttr('disabled');
                                }
                            }

                            if (obj == "enable_penalties_scoring") {
                                if (data[obj] ==="Si") {
                                    $("#enable_penalties_scoring").prop('checked', true);
                                    $('#winner_points_penalties').removeAttr('disabled');
                                    $('#loser_points_penalties').removeAttr('disabled');
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
                Table.ajax.reload();
            });

            $(document).on("submit", "#Form", function (e) {
                e.preventDefault();

                $.ajax({
                    type: "POST",
                    url: "{{ route('tournaments.store') }}",
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
                            $.notify('hello !!', {
                                style: 'happyblue'
                            });

                            return false;
                        }

                        $("#updateForm").trigger('reset');

                        jQuery.noConflict();
                        jQuery("#formModal").modal('toggle');
                        Table.ajax.reload();
                        $.notify(data.message, data.type);

                    },
                    error:function(){
                        $.notify("Hubo un error al intentar agregar el Torneo", "error");
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
                        Table.ajax.reload();
                        $.notify(data.message, data.type);

                    },
                    error:function(){
                        $.notify("Hubo un error al intentar borrar el registro", "error");
                    },
                });

                return false;
            });


            $(document).on("submit", "#startForm", function (e) {
                    e.preventDefault();

                    $.ajax({
                        type: "POST",
                        url: "{{ url('tournaments.start')}}/" + $(this).attr("idStart"),
                        dataType: "json",
                        data: $(this).serialize(),
                        success: function (data) {
                            if (data.type == "error") {
                                $.notify(data.message + "\n" + errors, data.type);
                                return false;
                            }

                            $("#startForm").trigger('reset');

                            jQuery.noConflict();
                            jQuery("#startModal").modal('toggle');
                            Table.ajax.reload();
                            $.notify(data.message, data.type);

                        },
                        error:function(){
                            $.notify("Hubo un error al intentar inciar el torneo", "error");
                        },
                    });

                    return false;
                });
        });

</script>
@endsection
