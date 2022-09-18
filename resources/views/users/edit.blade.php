@extends('layouts.app')

@section('title','A Los Dulces :: Modificar Usuario')


@section('content')

<div class="container">
	<div class="row card">
		<div class="card-header">Modificar Usuario</div>
		<div class="card-body">
			@include('users.form',['user'=>$user,'url'=>'/users/'.$user->id, 'method' =>'PATCH','option'=>'edit'])
		</div>
	</div>
</div>



@endsection