@extends('layouts.app')
@section('title','A Los Dulces :: Crear Usuario')
@section('content')
<div class="container">
	<div class="row card">
    	<div class="card-header">Nuevo Usuario</div>
    		<div class="card-body">
				@include('users.form',[
					'user'=>$user,
					'url'=>'/users/',
					'method'=>'POST',
					'option'=>'new'])
			</div>
	</div>
</div>
		
@endsection