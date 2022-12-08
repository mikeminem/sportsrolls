@extends('layouts.app')

@section('title', config('app.name') ." :: " .  __('Tournaments') )
@section('description',  "Administrador de Deportes ")

@section('content')

<div class="container">
    <div class="row py-2">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="text-left">
                        <h1>{{ __('Tournaments')}}</h1>
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
                                    <th width="10%">Código</th>
                                    <th>Nombre</th>
                                    <th>Contactos</th>
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
                                <label for="code">Código</label>
                                <input type="text" class="form-control" id="code" name="code" readonly>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label for="sport">Deporte</label>
                                <input type="text" class="form-control" id="sport" name="sport" readonly>
                                <input type="hidden" class="form-control" id="sport_id" name="sport_id" readonly>                            
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

                        <div class="col-md-3 col-sm-6">
                            <div class="form-group">
                                <label for="winner_points">Puntos Juego Ganado</label><br />
                                <input type="text" class="form-control" id="winner_points" name="winner_points" required maxlength="2" readonly> 
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <div class="form-group">
                                <label for="draw_points">Puntos Juego Empatado</label><br />
                                <input type="text" class="form-control" id="draw_points" name="draw_points" required maxlength="2" readonly> 
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <div class="form-group">
                                <label for="loser_points">Puntos Juego Perdido</label><br />
                                <input type="text" class="form-control" id="loser_points" name="loser_points" required maxlength="2" readonly> 
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
          <h4 class="modal-title">Borrar Rregistro</h4>
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
          <h4 class="modal-title">Cambiar estado del Torneo</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <form id="startForm">

            <div class="modal-body">
            <h4 id="message"> <mark><span class="text-blod" id="row_title"></span></mark>?</h4>
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
                    { data: 'code', name: 'code'},
                    { data: 'title', name: 'title'},
                    { data: 'contacts', name: 'contacts'},
                    { data: 'status', name: 'status'},
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });


            $(document).on("click", "#btnAdd", function () {
                $("#Form").trigger('reset');
                $("#id").val("");
                $("#sport_id").val("{{ Auth::user()->league->sport_id}}");
                $("#sport").val("{{ Auth::user()->league->sport->name}}");


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


            
            $(document).on("click", "#enable_points_system", function (e) { 

                if ($(this).is(":checked")) {
                    $('#winner_points').removeAttr('readonly');
                    $('#loser_points').removeAttr('readonly');
                    $('#draw_points').removeAttr('readonly');
                } else {
                    $('#winner_points').attr('readonly', true)
                    $('#loser_points').attr('readonly', true)
                    $('#draw_points').attr('readonly', true)

                    //values
                    $('#winner_points').val('0');
                    $('#loser_points').val('0');
                    $('#draw_points').val('0');

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
                        console.log(data)

                        for(obj in data) {
                            $("#" + obj).val(data[obj]);

                            if (obj == "enable_points_system") {
                                if (data[obj]=="Si") {
                                    $("#enable_points_system").prop('checked', true);
                                    $('#winner_points').removeAttr('readonly');
                                    $('#loser_points').removeAttr('readonly');
                                    $('#draw_points').removeAttr('readonly');

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