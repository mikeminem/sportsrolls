@extends('layouts.app')

@section('title', config('app.name') ." :: " .  __('WebPages') )
@section('description',  "Administrador de Contenidos Web ")

@section('content')

<div class="container">
    <div class="row py-2">
        <div class="col-md-12">

            <div class="card">
                <div class="card-body">
                    <div class="text-left">
                        <h1>{{ __('WebPages')}}</h1>
                    </div>

                    @if (Auth::check() 
                            &&  ( 
                                Auth::user()->role == 'Admin'
                                || Auth::user()->role == 'Promoter'
                                || Auth::user()->role == 'Writer' 
                            )
                        )
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
                   

                    <div class="table-responsive">
                        <table class="table table-hover" id="table">
                            <thead>
                                <tr>
                                    <th>Título</th>
                                    <th>Descripción</th>
                                    <th>Tipo</th>
                                    <th>Estado</th>
                                    <th>Saldo Restante</th>
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
    <div class="modal-dialog mw-100 w-75">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"> Contenido Web</h4>
                <button type="button" class="close" data-dismiss="modal" data-backdrop="false">&times;</button>          
            </div>
            <!--mandamos la forma-->
            <form id="updateForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                     
                        <div class="col-md-9 col-sm-12">
                            <div class="form-group">
                                <label for="title"><b>Título *</b></label>
                                <input type="text" class="form-control" id="title" name="title" required maxlength="120"> 
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label for="title"><b>Torneo </b></label>
                                <select class="form-control"  id="tournament_id" name="tournament_id">
                                    <option value="">Selecciona dónde ver el contenido</option>
                                    <option value="0">Vista global</option>
                                    @foreach($tournaments as $tournament)
                                        <option value="{{ $tournament->id}}">{{ $tournament->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="description"><b>Descripción corta*</b></label>
                                <input type="text" class="form-control" id="description" name="description" required maxlength="120"> 
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="content"><b>Contenido *</b></label><br />
                                <textarea name="content" id="content" class="form-control editor" cols="30" rows="6" required></textarea>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label for="status"><b>Estatus *</b></label>
                                <select id="status" name="status" class="form-control" required>
                                    <option value="En Desarrollo">En Desarrollo</option>
                                    <option value="Por Aprobar">Por Aprobar</option>
                                    <option value="Aprobado">Aprobado</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label for="type"><b>Tipo *</b></label>
                                <select id="type" name="type" class="form-control" required>
                                    <option value="Pagina Web">Página Web</option>
                                    <option value="Publicidad">Publicidad</option>
                                    <option value="Anuncio">Anuncio</option>
                                    <option value="Convocatoria">Convocatoria</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label for="location"><b>Ubicación *</b></label>
                                <select id="location" name="location" class="form-control" required>
                                    <option value="No Ubicacion">No Ubicación</option>
                                    <option value="Encabezado">Encabezado</option>
                                    <option value="Carrusel Principal">Carrusel Principal</option>
                                    <option value="Lado Izquierdo">Lado Izquierdo</option>
                                    <option value="Lado Derecho">Lado Derecho</option>
                                    <option value="Abajo">Abajo</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label for="image"><b>Imagen</b></label>
                                <input type="file" class="form-control" id="image" name="image"> 
                                <small>La imagen de la nota debe estar en las medidas 1000 x 500 px</small><br>
                                <img src="" id="logo"  width="200px" >
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
<script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>

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
                        url: '{{ route('webpages.index') }}',
                    },
                    columns: [
                        { data: 'title', name: 'title'},
                        { data: 'description', name: 'description'},
                        { data: 'type', name: 'type'},
                        { data: 'status', name: 'status'},
                        { data: 'budget', name: 'budget'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ]
                });
            }

            $(document).on("click", "#addRow", function () {
                $("#updateForm").trigger('reset');
                $("#id").val("");
                // $("#image").attr('src','');
                // $("#image").hide();
                CKEDITOR.instances.content.setData('')

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
                            if (obj ==='image') {
                                let image = data[obj];
                                $("#image").attr('src', '{{ asset('web_pages/') }}/' + image);
                                $("#image").show();
                                continue;
                            }

                            if (obj == 'content') {
                                CKEDITOR.instances.content.setData(data[obj])
                                continue;
                            } 

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
                    url: "{{ route('webpages.store') }}",
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
                            // $.notify('hello !!', {
                            //     style: 'happyblue'
                            // });
                            
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


            $('.editor').each(function () {
                CKEDITOR.replace($(this).attr('id'),{
                    filebrowserImageBrowseUrl:  '{{ asset("/js/kcfinder/browse.php?type=images") }}',
                    filebrowserImageUploadUrl:  '{{ asset("/js/kcfinder/browse.php?type=images&_token".csrf_token()) }}',
                    filebrowserBrowseUrl:       '{{ asset("/js/kcfinder/browse.php?type=Files") }}',
                    filebrowserUploadUrl:       '{{ asset("/js/kcfinder/browse.php?type=Files&_token".csrf_token()) }}',
                });
            });

        });

</script>
@endsection