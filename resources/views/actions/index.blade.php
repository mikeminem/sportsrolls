@extends('layouts.app')

@section('title', config('app.name') ." :: " .  __('Actions') )
@section('description',  "Administrador de Acciones del Deporte")

@section('content')

<div class="container">
    <div class="row py-2">
        <div class="col-md-12">

            <div class="card">
                <div class="card-body">
                    <div class="text-left">
                        <h1>{{ __('Actions')}}</h1>
                    </div>

                    @if (Auth::check() &&  ( (Auth::user()->role == 'Admin') || (Auth::user()->role == 'Promoter') ))
                        <a role="button"
                            class="btn btn-primary"
                            href="javascript:void(0);"
                            id="addRow"
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

                    <div class="col-md-3 col-sm-12">
                        <div class="form-group">
                            <label for="filter-sport"><b>Deporte </b></label>
                            <select id="filter-sport" name="filter-sport" class="form-control" required>
                                <option value="">Todos</option>
                                @foreach ($sports as $sport )
                                <option value="{{ $sport->id }}">{{ $sport->name }}</option>

                                @endforeach
                            </select>
                        </div>
                    </div>



                    <div class="table-responsive">
                        <table class="table table-hover" id="table">
                            <thead>
                                <tr>
                                    <th>Sport</th>
                                    <th>Nombre</th>
                                    <th>Valor</th>
                                    <th>Afecta marcador</th>
                                    <th>Necesita confirmación</th>
                                    <th>Acciones</th>
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
                <h4 class="modal-title"> Datos de la acción</h4>
                <button type="button" class="close" data-dismiss="modal" data-backdrop="false">&times;</button>
            </div>
            <!--mandamos la forma-->
            <form id="updateForm" enctype="multipart/form-data">
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="sport_id"><b>Deporte *</b></label>
                                <select id="sport_id" name="sport_id" class="form-control" required>
                                    <option value="">Selecciona</option>
                                    @foreach ($sports as $sport )
                                    <option value="{{ $sport->id }}">{{ $sport->name }}</option>

                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label for="label"><b>Etiqueta *</b></label>
                                <input type="text" class="form-control" id="label" name="label" required maxlength="10" selected>
                            </div>
                        </div>

                        <div class="col-md-9 col-sm-12">
                            <div class="form-group">
                                <label for="name"><b>Nombre *</b></label>
                                <input type="text" class="form-control" id="name" name="name" required maxlength="80">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                                <label for="scoring"><b>Afecta el marcador *</b></label>
                                <select id="scoring" name="scoring" class="form-control" required>
                                    <option value="">Selecciona</option>
                                    <option value="Si">Si</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                                <label for="need_confirmation"><b>Necesita Confirmación *</b></label>
                                <select id="need_confirmation" name="need_confirmation" class="form-control" required>
                                    <option value="">Selecciona</option>
                                    <option value="Si">Si</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>


                        <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                                <label for="value"><b>Valor *</b></label>
                                <input type="text" class="form-control" id="value" name="value" required maxlength="80">
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
            <h4>¿Deseas borrar la categoria <mark><span class="text-blod" id="row_name"></span></mark>?</h4>
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

            function fetch_data() {

                $('#table').DataTable({
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
                    ajax: {
                        url: '{{ route('actions.index') }}',
                    },
                    columns: [
                        { data: 'sport_id', name: 'sport_id', visible:false},
                        { data: 'name', name: 'name'},
                        { data: 'value', name: 'value'},
                        { data: 'scoring', name: 'scoring'},
                        { data: 'need_confirmation', name: 'need_confirmation'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ]
                });
            }

            //filters
            $(document).on("change", "#filter-sport", function () {
                $('#table').DataTable().columns(0).search(this.value).draw();
            });






            $(document).on("click", "#addRow", function () {
                $("#updateForm").trigger('reset');
                $("#id").val("");

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

            $(document).on("submit", "#updateForm", function (e) {
                e.preventDefault();

                $.ajax({
                    type: "POST",
                    url: "{{ route('actions.store') }}",
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
                        //Table.ajax.reload();
                        $('#table').DataTable().ajax.reload();

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
