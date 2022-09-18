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

{!! Form::open(['url'=> $url, 'method'=>$method]) !!}	
			<div class="row text-center">	
				<div class="col-md-4 col-sm-12">
				</div>
					<div class="col-md-4 col-sm-12">
						<div class="form-group">
							{{ Form::label('password', 'Contraseña * ') }}
							{{ Form::password('password',['class'=>'form-control', 'placeholder'=>'Contraseña...', 'required']) }}
						</div>
					</div>
				<div class="col-md-4 col-sm-12">
				</div>
			</div>
			
			<div class="form-group text-center">
				<a href="{{ url('/users') }}" title="Regresar al Listado" class="btn btn-danger btn-lg" role="button"><span class="glyphicon glyphicon-remove"> Cancelar</a>

				<button type="submit" class="btn btn-success btn-lg"><span class="glyphicon glyphicon-floppy-disk"></span> Guardar Contraseña</button>

			</div>

{!! Form::close() !!}