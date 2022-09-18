@extends('layouts.app')

@section('title','A Los Dulces :: Nuevo Producto')

@section('content')

	<div class="container">
		<div class="row card">
			<div class="card-header">Nuevo Producto</div>
				<div class="card-body">
					@include('products.form',[
						'row'=>$row,
						'url'=>'/products/',
						'method'=>'POST',
						'option'=>'new']
					)
				</div>
		</div>
	</div>

@endsection