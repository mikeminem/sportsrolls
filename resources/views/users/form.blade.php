

@if (count($errors)>0)
<div class="alert alert-danger alert-dismissible">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
  	<p><strong>Error!</strong> Se han producido los siguientes errores:</p>
		<ul>
		@foreach($errors->all() as $error)
			<li>{{ $error }}</li>
		@endforeach
	</ul>
</div>
@endif

@include('flash::message')
{!! Form::open(['url'=>$url, 'method'=>$method]) !!}

			<div class="row">
				<div class="col-md-6 col-sm-12">
					<div class="form-group">
						{{ Form::label('name', 'Nombres: *') }}
						{{ Form::text('name',$user->name,['class'=>'form-control', 'placeholder'=>'Nombres...','required']) }}
					</div>
				</div>

				<div class="col-md-6 col-sm-12">
					<div class="form-group">
						{{ Form::label('last_name', 'Apellidos: *') }}
						@if (isset($user))
							{{ Form::text('last_name',$user->last_name,['class'=>'form-control', 'placeholder'=>'Apellidos...','required']) }}
						@else
							{{ Form::text('name',null,['class'=>'form-control', 'placeholder'=>'Nombre Completo...','required', 'maxlength'=>'60']) }}

						@endif				
					</div>
				</div>

				@if ((Auth::user()->tipo=="Administrador") || (Auth::user()->tipo=="Dueño") )
					<div class="col-md-4 col-sm-12">
						<div class="form-group">
							{{ Form::label('store_name', 'Nickname / Empresa') }}
								{{ Form::text('store_name',$user->store_name,['class'=>'form-control', 'placeholder'=>'Nickname / Empresa','required', 'maxlength'=>'60']) }}
						</div>
					</div>
				@endif
				
			</div>

			<div class="row">
				<div class="col-md-4 col-sm-12">
					<div class="form-group">
						{{ Form::label('phone', 'Teléfono *') }}
						{{ Form::text('phone',$user->phone,['class'=>'form-control', 'placeholder'=>'Teléfono...', 'required', 'maxlength'=>'10']) }}
					</div>
				</div>

				<div class="col-md-4 col-sm-12">
					<div class="form-group">
						{{ Form::label('email', 'Correo Electrónico * ') }}
						{{ Form::email('email',$user->email,['class'=>'form-control', 'placeholder'=>'Correo Electrónico...', 'required', 'maxlength'=>'60']) }}						
					</div>
				</div>

				@if($option=='new')
					<div class="col-md-4 col-sm-12">
						<div class="form-group">
							{{ Form::label('password', 'Contraseña * ') }}
							{{ Form::password('password',['class'=>'form-control', 'placeholder'=>'Teclee su contraseña', 'required', 'maxlength'=>'20']) }}
						</div>
					</div>
				@endif
			</div>
			
			<div class="row">
				<div class="col-md-3 col-sm-12">
					<div class="form-group">
						{{ Form::label('type', 'Tipo Usuario: *') }}
						{{ Form::select('type', ['' =>'Selecciona'] + $roles, $user->type ,['class'=>'form-control', 'required']) }}
					</div>
				</div>

				<div class="col-md-3 col-sm-12 disable" id="typePrice">
					<div class="form-group">
						{{ Form::label('typeprice_id', 'Tipo de Precio: *') }}
						{{ Form::select('typeprice_id',  ['' =>'Selecciona'] + $typeprices, $user->typeprice_id, ['class'=>'form-control', 'disabled', 'required']) }}
					</div>
				</div>
				
				<div class="col-md-3 col-sm-12">
					<div class="form-group">
						{{ Form::label('active', 'Usuario activo *') }}
						{{ Form::select('active', array(''=>'Selecciona', 'No' => 'No', 'Si' => 'Si'), $user->active, ['class'=>'form-control', 'required']) }}
					</div>
				</div>

				@if (Auth::user()->type=="Administrador")
					<div class="col-md-3 col-sm-12">
						<div class="form-group">
							{{ Form::label('dealer_id', 'Vendedor o Dealer: *') }}
							{{ Form::select('dealer_id',  ['' =>'Selecciona'] + $dealers, $user->dealer_id, ['class'=>'form-control']) }}
						</div>
					</div>
				@endif
			</div>
			<div class="row">
				<div class="col-md-3 col-sm-12">
					<div class="form-group">
						{{ Form::label('store_id', 'Tienda:') }}
						{{ Form::select('store_id', ['' =>'Selecciona'] + $stores, $user->store_id ,['class'=>'form-control']) }}
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
						<a href="{{ url('/users') }}" alt="Regresar al Listado" class="btn btn-danger btn-lg">
							<span class="fa fa-remove"></span> Cancelar
						</a>
					</div>
				</div>
			</div>
{{ Form::close() }}


@section('js')
<script>		
jQuery(document).ready(function($){
		//we change the disable or enable for typeprice combo
		$('#type').change(function() {
			if ($('#type').val() == 'Cliente')
			{
				$('#typeprice_id').prop('disabled',false);
			}
			else
			{
				$('#typeprice_id').prop('disabled',true);
				$('#typeprice_id').val('');
			}
		});
	
	});
</script>
@endsection