@extends('layouts.app')

@section('title', config('app.name') ." :: " .  __('Stadiums') )
@section('description',  "Administrador de estadios ")

@section('content')

<div class="container">
    <div class="row py-2">
        <div class="col-md-12">

            <div class="card">
                <div class="card-body">
                    <div class="text-left">
                        <h1>{{ __('Stadiums')}}</h1>
                    </div>

                    @if (Auth::check() &&  ( (Auth::user()->role == 'Admin') || (Auth::user()->role == 'Promoter') ))
                        <a role="button" 
                            class="btn btn-primary" 
                            href="javascript:void(0);" 
                            id="AddStadium"
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
                                    <!--th>Código</th-->
                                    <th>Nombre</th>
                                    <th>Encargado</th>
                                    <th>Teléfono</th>
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
                <h4 class="modal-title"> Datos del Estadio</h4>
                <button type="button" class="close" data-dismiss="modal" data-backdrop="false">&times;</button>          
            </div>
            <!--mandamos la forma-->
            <form id="updateForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label for="code">Código</label>
                                <input type="text" class="form-control" id="code" name="code" readonly>
                            </div>
                        </div>

                        <div class="col-md-9 col-sm-12">
                            <div class="form-group">
                                <label for="usr"><b>Nombre *</b></label>
                                <input type="text" class="form-control" id="name" name="name" required maxlength="80"> 
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="usr"><b>Dirección *</b></label>
                                <input type="text" class="form-control" id="address" name="address" required maxlength="120">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-9 col-sm-12">
                            <div class="form-group">
                                <label for="usr"><b>Encargado *</b></label>
                                <input type="text" class="form-control" id="manager" name="manager" required maxlength="80">
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label for="code"><b>Teléfono *</b></label>
                                <input type="phone" class="form-control" id="phone" name="phone" required maxlength="10">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="usr"><b>Notas / horarios</b></label>
                                <textarea class="form-control" id="notes" name="notes" maxlength="120">
                                </textarea>
                                <!--input type="text" class="form-control" id="notes" name="notes" maxlength="120"-->
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
            <div class="modal-body">
            <h4>¿Deseas borrar  <mark><span class="text-blod" id="row_name"></span></mark>?</h4>
            </div>
            {{ csrf_field() }}
            <input type="hidden" class="form-control" id="idDelete" name="idDelete" readonly>
    
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
                ajax: 'all_stadiums',
                columns: [
                    //{ data: 'code', name: 'code'},
                    { data: 'name', name: 'name'},
                    { data: 'manager', name: 'manager'},
                    { data: 'phone', phone: 'name'},
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });

            $(document).on("click", "#AddStadium", function () {
                $("#updateForm").trigger('reset');
                $("#id").val("");

                jQuery.noConflict(); 
                jQuery("#formModal").modal('show');
            });

            $(document).on("click", "#btnDelete", function () {
                //alert(' Delete Row ' + $(this).attr('data-id') + " - " + $(this).attr('data-name'));
                $('#row_name').text($(this).attr('data-name'));
                $("#idDelete").val($(this).attr('data-id'));

                jQuery("#deleteModal").modal('show');
            });

            $(document).on("click", "#btnUpdate", function () {
                //alert(' update Row' + $(this).attr('data-id') );
                $("#updateForm").trigger('reset');

                $.ajax({
                    type: "GET",
                    url: "get_stadium/" + $(this).attr("data-id"),
                    dataType: "json",
                    success: function (data) { 
                        $("#id").val(data.id);
                        $("#name").val(data.name);
                        $("#manager").val(data.manager);
                        $("#phone").val(data.phone);
                        $("#notes").val(data.notes);
                        $("#address").val(data.address);
                        $("#code").val(data.code);
                        jQuery.noConflict(); 
                        jQuery("#formModal").modal('show');
                    },
                });
            });

            $(document).on("click", "#btnRefresh", function () {
                Table.ajax.reload();
            });

            $(document).on("submit", "#updateForm", function (e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "save_stadium",
                    dataType: "json",
                    data: $(this).serialize(),
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
                        $.notify("Hubo un error al intentar agregar el registro", "error");
                    },
                });
                return false;
            });

            $(document).on("submit", "#deleteForm", function (e) {

                e.preventDefault();
                $.ajax({
                    type: "DELETE",
                    url: "{{ url('/stadiums')}}/" + $(this).attr("idDelete"),
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
        });

</script>
@endsection