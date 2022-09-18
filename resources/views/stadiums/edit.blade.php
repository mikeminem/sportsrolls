@extends('layouts.app')
@section('title','A Los Dulces :: Modificar Producto')
@section('content')

		<div class="container">
			<div class="row card">
				<div class="card-header">Modificar Producto</div>
				<div class="card-body">
					@include('products.form',[
						'row'=>$row,
						'url'=>'/products/'.$row->id, 
						'method' =>'PATCH',
						'option'=>'edit'
					])
				</div>
			</div>
		</div>

@endsection