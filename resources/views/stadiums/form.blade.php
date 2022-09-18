@if (count($errors)>0)
<div class="alert alert-danger" role="alert">
	<p>Se han producido los siguientes errores:</p>
	<ul>
	@foreach($errors->all() as $error)
		<li>{{ $error }}</li>
	@endforeach
	</ul>
</div>
@endif

{!! Form::open(['url'=>$url, 'method'=>$method, 'files'=>true]) !!}

			<div class="row">
				<div class="col-md-2 col-sm-4">
					<div class="form-group">
						<b>{{ Form::label('code', 'Código *') }}</b>
						{{ Form::text('code',$row->code,['class'=>'form-control', 'placeholder'=>'Código...','required', 'maxlength'=>'15', 'data-toggle'=>'tooltip','title'=>'Ingresa el código']) }}
					</div>
				</div>

				<div class="col-md-10 col-sm-8">
					<div class="form-group">
						<b>{{ Form::label('name', 'Nombre Producto *') }}</b>
						{{ Form::text('name',$row->name,['class'=>'form-control', 'placeholder'=>'Nombre...','required', 'maxlength'=>'120', 'data-toggle'=>'tooltip','title'=>'Ingresa el nombre del producto']) }}
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12 col-sm-12">
					<div class="form-group">
						{{ Form::label('description', 'Descripción') }}
						{{ Form::textarea('description',$row->description,['class'=>'form-control', 'placeholder'=>'Descripción...', 'cols'=>'80', 'rows'=>'2', 'maxlength'=>'500']) }}
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-3 col-sm-3">
					<div class="form-group">
						<b>{{ Form::label('brand_id', 'Marca *') }}</b>
						<div class="input-group">
							{{ Form::select('brand_id', ['' =>'Selecciona'] + $brands, $row->brand_id,['class'=>'form-control', 'required', 'data-toggle'=>'tooltip','title'=>'Selecciona la marca']) }}
							<div class="form-group-btn">
						      <a href="javascript:void(0)" data-toggle="modal" data-target="#addBrandModal" class="btn btn-primary btn-md" id="addBrand" role="button">
						        <i class="fa fa-plus"></i>
						      </a>
						    </div>
						</div>
					</div>
				</div>

				<div class="col-md-3 col-sm-3">
					<div class="form-group">
						<b>{{ Form::label('category_id', 'Categoría *') }}</b>
						<div class="input-group">
							{{ Form::select('category_id', ['' =>'Selecciona'] + $categories, $row->category_id,['class'=>'form-control', 'required', 'data-toggle'=>'tooltip','title'=>'Selecciona la categoría']) }}
							<div class="form-group-btn">
						      <a href="javascript:void(0)" data-toggle="modal" data-target="#addCategoryModal" class="btn btn-primary btn-md" id="addCategory" role="button">
						        <i class="fa fa-plus"></i>
						      </a>
						    </div>
						</div>
					</div>
				</div>

				<div class="col-md-3 col-sm-3">
					<div class="form-group">
						<b>{{ Form::label('sale_online', 'Vender en Línea *') }}</b>
						{{ Form::select('sale_online', array(''=>'Selecciona', 'N' => 'No', 'S' => 'Si'), $row->sale_online,['class'=>'form-control', 'required', 'data-toggle'=>'tooltip','title'=>'Selecciona si se vende en línea']) }}
					</div>
				</div>

				@if (Auth::user()->type == "Administrador")
				<div class="col-md-3 col-sm-3">
					<div class="form-group">
						<b>{{ Form::label('dealer_id', 'Vendedor *') }}</b>
						{{ Form::select('dealer_id', $dealers, $row->dealer_id,['class'=>'form-control', 'required', 'data-toggle'=>'tooltip','title'=>'Selecciona la categoría']) }}
					</div>
				</div>
				@endif

			</div>

			<div class="row">
				<div class="col-md-3 col-sm-3">
					<div class="form-group">
						<b>{{ Form::label('buy_price', 'Precio de Compra *') }}</b>
						{{ Form::text('buy_price',$row->buy_price,['class'=>'form-control', 'placeholder'=>'Precio de Compra...', 'maxlength'=>'6', 'required']) }}
					</div>
				</div>

				<div class="col-md-3 col-sm-3">
					<div class="form-group">
						<b>{{ Form::label('sale_price', 'Precio de Venta *') }}</b>
						{{ Form::text('sale_price',$row->sale_price,['class'=>'form-control', 'placeholder'=>'Precio de Venta...', 'maxlength'=>'6', 'required']) }}
					</div>
				</div>

				<div class="col-md-3 col-sm-3">
					<div class="form-group">
						<b>{{ Form::label('stock', 'Existencia *') }}</b>
						{{ Form::text('stock',$row->stock,['class'=>'form-control', 'placeholder'=>'Existencias...', 'maxlength'=>'8', 'required']) }}
					</div>
				</div>

				<div class="col-md-3 col-sm-3">
					<div class="form-group">
						<b>{{ Form::label('minimum', 'Existencias mínimas *') }}</b>
						{{ Form::text('minimum',$row->minimum,['class'=>'form-control', 'placeholder'=>'Existencias mínimas...', 'maxlength'=>'8', 'required']) }}
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-3 col-sm-3">
					<div class="form-group">
						<b>{{ Form::label('unit', 'Unidad *') }}</b>
						<div class="input-group">
							{{ Form::select('unit', ['' =>'Selecciona'] + $units, $row->unit,['class'=>'form-control', 'required', 'data-toggle'=>'tooltip','title'=>'Selecciona la unidad']) }}
							<div class="form-group-btn">
						      <a href="javascript:void(0)" data-toggle="modal" data-target="#addUnitModal" class="btn btn-primary btn-md" id="addUnit" role="button">
						        <i class="fa fa-plus"></i>
						      </a>
						    </div>
						</div>
					</div>
				</div>

				<div class="col-md-3 col-sm-3">
					<div class="form-group">
						<b>{{ Form::label('tax_id', 'Impuesto *') }}</b>
						{{ Form::select('tax_id', ['' =>'Selecciona'] + $taxes, $row->tax_id,['class'=>'form-control', 'required', 'data-toggle'=>'tooltip','title'=>'Selecciona el Impuesto']) }}
					</div>
				</div>

				<div class="col-md-3 col-sm-3">
					<div class="form-group">
						{{ Form::label('weight', 'Peso') }}
						{{ Form::text('weight',$row->weight,['class'=>'form-control', 'placeholder'=>'Peso...', 'maxlength'=>'8']) }}
					</div>
				</div>

				<div class="col-md-3 col-sm-3">
					<div class="form-group">
						{{ Form::label('type_weight', 'Tipo Peso') }}
						{{ Form::select('type_weight', array('Selecciona'=>'', 'Libra' => 'Libra', 'Onza' => 'Onza', 'Kilogramo'=>'Kilogramo', 'Gramo'=>'Gramo'), $row->type_weight,['class'=>'form-control', 'data-toggle'=>'tooltip','title'=>'Selecciona el tipo de peso']) }}
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<b>Datos marcados con * son obligatorios</b>
				</div>
				<div class="col-md-6 text-right">
					<div class="form-group">
						<button type="submit" class="btn btn-success btn-lg">
							<span class="fa fa-save"></span> 
							@if($option=='new')
								Crear
							@else
								Guardar
							@endif
						</button>
						<a href="{{ url('/products') }}" alt="Regresar al Listado" class="btn btn-danger btn-lg">
							<span class="fa fa-remove"></span> Cancelar
						</a>
					</div>
				</div>
			</div>
			
{{ Form::close() }}

<!-- Add Category Modal -->
<div id="addCategoryModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"> Nueva Categoría </h4>
				<button type="button" class="close" data-dismiss="modal" data-backdrop="false">&times;</button>
				
			</div>
			<!--mandamos la forma-->
			<form id="addCategoryForm">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<div class="form-group">
								{{ Form::label('name', 'Nombre Categoría: ') }}
								{{ Form::text('name',null,['class'=>'form-control', 'placeholder'=>'Nombre categoría...','required', 'maxlength'=>'30']) }}
							</div>
						</div>
						<b>Datos marcados con * son obligatorios</b>
					</div>

				</div>

				<div class="modal-footer">
					<div class="form-group text-right">
						{{ csrf_field() }}
							<button type="submit" class="btn btn-success btn-lg">
								<span class="fa fa-save"></span> 
								Crear
							</button>

							<button type="button" class="btn btn-danger btn-lg" class="close" data-dismiss="modal">
								<span class="fa fa-remove"></span> Cancelar
							</button>
						</div>
					</div>
				</form>
				<!--fin de la forma-->
			</div>
		</div>
	</div>
	<!-- end of the modal window for category-->


	<!-- Add Category Modal -->
<div id="addBrandModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"> Nueva Marca</h4>
				<button type="button" class="close" data-dismiss="modal" data-backdrop="false">&times;</button>
				
			</div>
			<!--mandamos la forma-->
			<form id="addBrandForm">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<div class="form-group">
								{{ Form::label('name', 'Nombre Marca: ') }}
								{{ Form::text('name',null,['class'=>'form-control', 'placeholder'=>'Nombre marca...','required', 'maxlength'=>'30']) }}
							</div>
						</div>
						<b>Datos marcados con * son obligatorios</b>
					</div>
				</div>

				<div class="modal-footer">
					<div class="form-group text-right">
						{{ csrf_field() }}
							<button type="submit" class="btn btn-success btn-lg">
								<span class="fa fa-save"></span> 
								Crear
							</button>

							<button type="button" class="btn btn-danger btn-lg" class="close" data-dismiss="modal">
								<span class="fa fa-remove"></span> Cancelar
							</button>
						</div>
					</div>
				</form>
				<!--fin de la forma-->
			</div>
		</div>
	</div>
<!--end of the modal window for category-->

<!-- Add Unit Modal -->
	<div id="addUnitModal" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title"> Nueva Unidad</h4>
					<button type="button" class="close" data-dismiss="modal" data-backdrop="false">&times;</button>
					
				</div>
				<!--mandamos la forma-->
				<form id="addUnitForm">
					<div class="modal-body">
						<div class="row">
							<div class="col-md-3 col-sm-3">
								<div class="form-group">
									{{ Form::label('code', 'Código: ') }}
									{{ Form::text('code',null,['class'=>'form-control', 'placeholder'=>'Código marca...','required', 'maxlength'=>'4']) }}
								</div>
							</div>

							<div class="col-md-9 col-sm-9">
								<div class="form-group">
									{{ Form::label('name', 'Nombre de la unidad: ') }}
									{{ Form::text('name',null,['class'=>'form-control', 'placeholder'=>'Nombre marca...','required', 'maxlength'=>'30']) }}
								</div>
							</div>
							<b>Datos marcados con * son obligatorios</b>
						</div>
					</div>
	
					<div class="modal-footer">
						<div class="form-group text-right">
							{{ csrf_field() }}
								<button type="submit" class="btn btn-success btn-lg">
									<span class="fa fa-save"></span> 
									Crear
								</button>
	
								<button type="button" class="btn btn-danger btn-lg" class="close" data-dismiss="modal">
									<span class="fa fa-remove"></span> Cancelar
								</button>
							</div>
						</div>
					</form>
					<!--fin de la forma-->
				</div>
			</div>
		</div>
	<!--end of the modal window for unit-->


@section('js')
<script>		
jQuery(document).ready(function($){
		//add a category
	$(document).on("submit", "#addCategoryForm", function (e) {
	        e.preventDefault();
	        $.ajax({
            type: "POST",
            url: " {{ url('add_category') }}",
            dataType: "json",
            data: $(this).serialize(),
            success: function (data) {
                var response = "";
                $.each(data.response, function (i, val) {
                    response = response + val + "\n";
                });
                $('#category_id').append('<option value="'+data.id+'">'+data.name+'</option>');
                $("#category_id option[value="+data.id+"]").attr("selected",true);
                $("#addCategoryForm").trigger('reset');
					 $("#addCategoryModal .close").click();
                $.notify("Se agregó la categoría correctamente ", "success");
            },
            error:function(){
              $.notify("Hubo un error al intentar agregar la categoría", "error");
            },
          });
          return false;
	    });

		 //add a brand
		 //
	//$(document).on("submit", "#addCategoryForm", function (e) {
		$('#addBrandForm').submit(function (e) {
	        e.preventDefault();
	        $.ajax({
            type: "POST",
            url: "{{url ('add_brand')}}",
            dataType: "json",
            data: $(this).serialize(),
            success: function (data) {
                var response = "";
                $.each(data.response, function (i, val) {
                    response = response + val + "\n";
                });
                $('#brand_id').append('<option value="'+data.id+'">'+data.name+'</option>');
                $("#brand_id option[value="+data.id+"]").attr("selected",true);
                $("#addBrandForm").trigger('reset');
					 $("#addBrandModal .close").click();
                $.notify("Se agregó la categoría correctamente ", "success");
            },
            error:function(){
              $.notify("Hubo un error al intentar agregar la categoría", "error");
            },
          });
          return false;
	    });

		 $('#addUnitForm').submit(function (e) {
	        e.preventDefault();
	        $.ajax({
            type: "POST",
            url: "{{ url('add_unit')}}",
            dataType: "json",
            data: $(this).serialize(),
            success: function (data) {
                var response = "";
                $.each(data.response, function (i, val) {
                    response = response + val + "\n";
                });
                $('#unit').append('<option value="'+data.id+'">'+data.name+'</option>');
                $("#unit option[value="+data.id+"]").attr("selected",true);
                $("#addUnitForm").trigger('reset');
					 $("#addUnitModal .close").click();
                $.notify("Se agregó la unidad correctamente ", "success");
            },
            error:function(){
              $.notify("Hubo un error al intentar agregar la unidad", "error");
            },
          });
          return false;
	    });
	});
</script>
@endsection