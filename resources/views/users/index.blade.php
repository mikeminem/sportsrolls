@extends('layouts.app')

@section('title','A los Dulces :: Usuarios')

@section('content')

<div class="text-left">
	<h1>Usuarios</h1>
</div>

<div class="container">
    <div class="row py-1">
        @if (Auth::check() &&  ( (Auth::user()->type == 'Administrador') || (Auth::user()->type == 'Dealer') ))
        <a role="button" class="btn btn-primary" href=" {{ url('/users/create')}}" >
            <span class="fa fa-plus"></span> Nuevo Usuario
        </a>
        @endif
        <a role="button" class="btn btn-primary" href="javascript:void(0);" id="btnRefresh">
                <span class="fa fa-refresh"></span> Refrescar
        </a>
    </div>
</div>
@include('flash::message')
<div class="container">
    <div class="row">
        

        <div class="table-responsive">
            <table class="table table-hover" id="table-ui">
                <thead>
                    <tr>
                        <td>Id</td>
                        <td>Nombre</td>
                        <td>Apellidos</td>
                        <td>Tipo</td>
                        <td>Teléfono</td>
                        <td>Correo Electrónico</td>					
                        <td>Activo</td>
                        <td>Acciones</td>
                    </tr>
                </thead>

                <tbody>
                </tbody>
            </table>
        </div>		
    </div>
</div>
    

@endsection

@section('js')
<script>
jQuery(document).ready(function($){
    var userTable = $('#table-ui').DataTable({
    	"language": {
            "sProcessing":    "Procesando...",
            "sLengthMenu":    "Mostrar _MENU_ registros",
            "sZeroRecords":   "No se encontraron resultados",
            "sEmptyTable":    "Ningún dato disponible en esta tabla",
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
        ajax: 'all_users',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name'},
            { data: 'last_name', name: 'last_name'},
            { data: 'type', name: 'type'},
            { data: 'phone', name: 'phone'},
            { data: 'email', name: 'email'},
            { data: 'active', name: 'active'},
            { data: 'action', name: 'action', orderable: false, searchable: false}
        ]
    });

    //Refresh table
    $(document).on("click", "#btnRefresh", function () {
        userTable.ajax.reload();
    });
    
});

</script>
@endsection