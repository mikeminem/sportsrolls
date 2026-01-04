@extends('layouts.app')

@section('title', config('app.name') . " :: Productos")
@section('description', 'Administrador de Productos')

@section('content')
<div class="container">
    <div class="row py-2">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="text-left">
                        <h1>Productos</h1>
                    </div>

                    @if (Auth::check() && ((Auth::user()->role == 'Admin') || (Auth::user()->role == 'Seller')))
                        <a role="button" class="btn btn-primary" href="javascript:void(0);" id="AddProduct">
                            <span class="fa fa-plus"></span> {{ __('New') }}
                        </a>
                        <a role="button" class="btn btn-primary" href="javascript:void(0);" id="btnPrint">
                            <span class="fa fa-print"></span> {{ __('Print') }}
                        </a>
                    @endif

                    <a role="button" class="btn btn-primary" href="javascript:void(0);" id="btnRefresh">
                        <span class="fa fa-refresh"></span> {{ __('Refresh') }}
                    </a>

                    <hr />

                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <label for="filterCategory"><b>Filtrar por categoría</b></label>
                            <select class="form-control" id="filterCategory" name="filterCategory">
                                <option value="">Todas...</option>
                                @foreach($categories as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover" id="table">
                            <thead>
                                <tr>
                                    <th>SKU</th>
                                    <th>Categoría</th>
                                    <th>Nombre</th>
                                    <th>Slug</th>
                                    <th>Precio</th>
                                    <th>Imagen</th>
                                    <th>Existencia</th>
                                    <th>Activo</th>
                                    <th> - </th>
                                </tr>
                            </thead>
                            <tbody></tbody>
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
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Datos del Producto</h4>
                <button type="button" class="close" data-dismiss="modal" data-backdrop="false">&times;</button>
            </div>
            <form id="updateForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="product_category_id"><b>Categoría *</b></label>
                                <select id="product_category_id" name="product_category_id" class="form-control" required>
                                    <option value="">Selecciona...</option>
                                    @foreach($categories as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="sku"><b>SKU</b></label>
                                <input type="text" class="form-control" id="sku" name="sku" maxlength="60" placeholder="SKU">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="name"><b>Nombre *</b></label>
                                <input type="text" class="form-control" id="name" name="name" required maxlength="160">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="slug"><b>Slug</b></label>
                                <input type="text" class="form-control" id="slug" name="slug" maxlength="160">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="price"><b>Precio *</b></label>
                                <input type="number" step="0.01" class="form-control" id="price" name="price" required min="0">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="stock"><b>Existencia</b></label>
                                <input type="number" step="1" class="form-control" id="stock" name="stock" min="0" placeholder="0">
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="description"><b>Descripción</b></label>
                                <textarea class="form-control" id="description" name="description" rows="2" maxlength="500"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="image"><b>Imagen</b></label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="is_active"><b>Activo *</b></label>
                                <select id="is_active" name="is_active" class="form-control" required>
                                    <option value="1">Si</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
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
                            <span class="fa fa-save"></span> {{ __('Save') }}
                        </button>
                        <button type="button" class="btn btn-primary btn-lg" class="close" data-dismiss="modal">
                            <span class="fa fa-remove"></span> {{ __('Cancel') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- delete modal -->
<div class="modal" id="deleteModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Borrar Registro</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="deleteForm">
                @method('DELETE')
                <div class="modal-body">
                    <h4>¿Deseas borrar el producto <mark><span class="text-blod" id="row_name"></span></mark>?</h4>
                </div>
                {{ csrf_field() }}
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <span class="fa fa-check"></span> {{ __('Delete') }}
                    </button>
                    <button type="button" class="btn btn-primary btn-lg" class="close" data-dismiss="modal">
                        <span class="fa fa-remove"></span> {{ __('Cancel') }}
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
                language: {
                    sProcessing: "Procesando...",
                    sLengthMenu: "Mostrar _MENU_ registros",
                    sZeroRecords: "No se encontraron resultados",
                    sEmptyTable: "No se encontró información",
                    sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                    sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
                    sSearch: "Buscar:",
                    sLoadingRecords: "...Cargando...",
                    oPaginate: {
                        sFirst: "Primero",
                        sLast: "Último",
                        sNext: "Siguiente",
                        sPrevious: "Anterior"
                    }
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('products.index') }}',
                    data: function(d){
                        d.category = $('#filterCategory').val();
                    }
                },
                columns: [
                    { data: 'sku', name: 'sku' },
                    { data: 'category', name: 'category' },
                    { data: 'name', name: 'name' },
                    { data: 'slug', name: 'slug' },
                    { data: 'price', name: 'price', render: function(data){
                        var val = parseFloat(data || 0);
                        return '$ ' + val.toFixed(2);
                    } },
                    { data: 'image', name: 'image', orderable: false, searchable: false },
                    { data: 'stock', name: 'stock' },
                    { data: 'is_active', name: 'is_active' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        }

        $(document).on('click', '#AddProduct', function(){
            $('#updateForm').trigger('reset');
            $('#id').val('');
            jQuery.noConflict();
            jQuery('#formModal').modal('show');
        });

        $(document).on('click', '#btnUpdate', function(e){
            e.preventDefault();
            $('#updateForm').trigger('reset');

            $.ajax({
                type: 'GET',
                url: $(this).attr('href'),
                dataType: 'json',
                success: function(data){
                    for (obj in data) {
                        $('#' + obj).val(data[obj]);
                    }
                    jQuery.noConflict();
                    jQuery('#formModal').modal('show');
                },
            });
            return false;
        });

        $(document).on('click', '#btnRefresh', function(){
            $('#table').DataTable().ajax.reload();
        });

        $(document).on('change', '#filterCategory', function(){
            $('#table').DataTable().destroy();
            fetch_data();
        });

        $(document).on('click', '#btnDelete', function(e){
            e.preventDefault();
            $('#row_name').text($(this).attr('data-name'));
            $('#deleteForm').attr('action', $(this).attr('href'));
            jQuery('#deleteModal').modal('show');
        });

        $(document).on('submit', '#updateForm', function(e){
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '{{ route('products.store') }}',
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                data: new FormData(this),
                success: function(data){
                    if (data.type === 'error') {
                        var errors = '';
                        $.each(data.errors, function(i, val){
                            errors = errors + val + "\n";
                        });
                        $.notify(data.message + "\n" + errors, data.type, { position: 'center' });
                        return false;
                    }
                    $('#updateForm').trigger('reset');
                    jQuery('#formModal').modal('toggle');
                    $('#table').DataTable().ajax.reload();
                    $.notify(data.message, data.type);
                },
                error: function(){
                    $.notify('Hubo un error al intentar agregar el registro', 'error');
                },
            });
            return false;
        });

        $(document).on('submit', '#deleteForm', function(e){
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                dataType: 'json',
                data: $(this).serialize(),
                success: function(data){
                    if (data.type === 'error') {
                        $.notify(data.message, data.type);
                        return false;
                    }
                    $('#deleteForm').trigger('reset');
                    jQuery('#deleteModal').modal('toggle');
                    $('#table').DataTable().ajax.reload();
                    $.notify(data.message, data.type);
                },
                error: function(){
                    $.notify('Hubo un error al intentar borrar el registro', 'error');
                },
            });
            return false;
        });
    });
</script>
@endsection
