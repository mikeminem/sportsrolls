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
                    <div class="col-md-3 col-sm-12">
                        <div class="form-group">
                            <label for="filterTournaments"><b>Torneos Activos</b></label>
                            <select class="form-control" required id="filterTournaments" name="filterTournaments"  >
                                <option value="">Selecciona</option>
                                @foreach($tournaments as $tournament)
                                    <option value="{{ $tournament->id}}">{{ $tournament->title}}</option>
                                @endforeach
                            </select>
                            
                        </div>
                    </div>
                    <hr />



                    <div class="table-responsive">
                        <table class="table table-hover" id="table">
                            <thead>
                                <tr>
                                    <th>Torneo</th>
                                    <th>Nombre</th>
                                    <th>Rama / Categoria</th>
                                    <th>Categoría</th>
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
                                <label for="group"><b>Grupo *</b></label>
                                <select class="form-control" required id="group" name="group"  >
                                    <option value="Todos">Todos contra todos</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                    <option value="E">E</option>
                                    <option value="F">F</option>
                                    <option value="G">G</option>
                                    <option value="H">H</option>
                                    <option value="I">I</option>
                                    <option value="J">J</option>
                                    <option value="K">K</option>
                                    <option value="L">L</option>
                                </select>  
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
                                <label for="title"><b>Deporte *</b></label>
                                <input type="text" class="form-control" readonly id="sport" name="sport">
                                <input type="hidden" class="form-control" readonly id="sport_id" name="sport_id" value="2">
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="title"><b>Categoria *</b></label>
                                <select class="form-control" required id="category_id" name="category_id">
                                    <option value="">Selecciona</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">    
                        <div class="col-md-9 col-sm-12">
                            <div class="form-group">
                                <label for="contact"><b>Nombre Contacto *</b></label>
                                <input type="text" class="form-control" id="contact" name="contact" required maxlength="120"> 
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label for="contact_phone"><b>Teléfono Contacto *</b></label>
                                <input type="text" class="form-control" id="contact_phone" name="contact_phone" required maxlength="10"> 
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
                ajax: '{{ route('teams.index') }}',
                columns: [
                    { data: 'tournament_id', name: 'tournament_id', visible:false},
                    { data: 'name', name: 'name'},
                    { data: 'branch', name: 'branch'},
                    { data: 'category', name: 'category'},
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });


            
            $(document).on("change", "#filterTournaments", function () {
                Table.columns(0).search(this.value).draw();
            });



            $(document).on("click", "#btnAdd", function () {
                $("#Form").trigger('reset');
                $("#id").val("");
                $("#category_id").empty();
                $("#category_id").html('<option value="">Selecciona</option>');
                $("#sport").val("{{ Auth::user()->league->sport->name}}");

                jQuery.noConflict(); 
                jQuery("#formModal").modal('show');
            });

            
            $(document).on("change", "#tournament_id", function (e) {
                $("#tournament_id option:selected").each(function () {
                    selected=$(this).val();
                    $("#category_id").html(getCategories(selected));
                    
                });
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
                        var category_id = 0;
                        for(obj in data) {
                            $("#" + obj).val(data[obj]);

                            if (obj == "category_id"){
                                category_id = data[obj]
                            }
                        }
                        $("#category_id").html(getCategories($("#tournament_id").val(),category_id));
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
                        Table.ajax.reload();
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

        //global funcntions
        function getCategories(sport, selected="") {
            var options = "";

            if (sport) {
                $.ajax({
                    type: "GET",
                    url: "{{ url('categories_list') }}/" + sport,
                    dataType: "json",
                    success: function (data) { 
                        options = '<option value="">Selecciona</option>';
                        var categories = -1
                        for(obj in data) {
                            if ( selected == data[obj]['id'] ) {
                                options += '<option value="' +data[obj]['id'] + '" selected="selected" >' + data[obj]['name'] + '</option>';            
                            } else {
                                options += '<option value="' +data[obj]['id'] + '" >' + data[obj]['name'] + '</option>';            
                            }
                            categories++;
                        }
                        
                        if (categories <0) {
                            $.notify("No hay categorías en este torneo, selecciona otro o agrega categorias al torneo", "error");
                        }
                        $("#category_id").html(options);
                    },
                });
            }
            return options;
        }

</script>
@endsection