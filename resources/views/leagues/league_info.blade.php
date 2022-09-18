@extends('layouts.app')

@section('title', config('app.name') ." :: " .  __('League Info') )
@section('description',  "Información de la liga, " . config('app.name') )

@section('content')

<div class="container">
    <div class="row py-2">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="text-left">
                        <h1>{{ __('League Info')}}</h1>
                        <form id="frmLeague">

                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <!--label for="usr"><b>Nombre *</b></label-->
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Nombre" required maxlength="80" value="{{ ($league)?$league->name:""}}">
                                    </div>
                                </div>
        
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <!--label for="usr"><b>Slogan</b></label-->
                                        <input type="text" class="form-control" id="slogan" name="slogan" placeholder="Slogan" maxlength="80" value="{{ ($league)?$league->slogan:""}}">
                                    </div>
                                </div>
        
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <textarea class="form-control" id="summary" name="summary" placeholder="Acerca de la liga">{{ ($league)?$league->summary:""}}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <!--label for="usr"><b>Nombre *</b></label-->
                                        <input type="text" class="form-control" id="president" name="president" placeholder="Presidente de la liga"  maxlength="80" value="{{ ($league)?$league->president:""}}">
                                    </div>
                                </div>
        
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <!--label for="usr"><b>Slogan</b></label-->
                                        <input type="text" class="form-control" id="secretry" name="secretary" placeholder="Secretario de la liga" maxlength="80" value="{{ ($league)?$league->secretary:""}}">
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <!--label for="usr"><b>Nombre *</b></label-->
                                        <input type="text" class="form-control" id="treasurer" name="treasurer" placeholder="Tesorero de la liga"  maxlength="80" value="{{ ($league)?$league->treasurer:""}}">
                                    </div>
                                </div>
        
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <!--label for="usr"><b>Slogan</b></label-->
                                        <input type="text" class="form-control" id="referee_main" name="referee_main" placeholder="Jefe de arbitros" maxlength="80" value="{{ ($league)?$league->referee_main:""}}">
                                    </div>
                                </div>
                            </div>
                            <hr />

                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <textarea class="form-control" id="footer1" name="footer1" placeholder="Pie de página">{{ ($league)?$league->footer1:""}}</textarea>
                                    </div>
                                </div>
                            </div>



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


                        </form>
                    </div>     
                </div>
            </div>
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

            $(document).on("submit", "#frmLeague", function (e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "leagues",
                    dataType: "json",
                    data: $(this).serialize(),
                    success: function (data) {                
                        if (data.type == "error") {
                            var errors = "";
                            $.each(data.errors, function (i, val) {
                                errors = errors  +  val + "\n";
                            });
                            $.notify(data.message + "\n" + errors, data.type, { position:"center" } );
                            return false;
                        }
                        $.notify(data.message, data.type);
                    },
                    error:function(){
                        $.notify("Hubo un error al intentar agregar el registro", "error");
                    },
                });
                return false;
            });

            $(document).on("submit", "#deleteForm", function (e) {
                //alert("Lets Delete the product (softdelete)");

                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "delete_stadium",
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