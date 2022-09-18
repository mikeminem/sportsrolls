@extends('layouts.app')

@section('content')

		<div class="container">
			<div class="row card">
			<h1>Cambiar Contraseña Usuario</h1>

			@include('users.formpassword',['user'=>$user,'url'=>'/users/password/'.$user->id,'method'=>'POST']);
			</div>
		</div>
		
@endsection