@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        Mi cuenta
    </div>
            <div class="card-body">
                @include('flash::message')
                <form method="POST"  action="{{url ('myaccount')}}" enctype="multipart/form-data">
                    <div class="row mb-3">
                            <input type="hidden" class="form-control" placeholder="id" id="id" name="id" value="{{ $user->id }}" required>

                        <div class="col-md-2 text-left">
                            <label for="name"><b>Nombres</b></label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" class="form-control" placeholder="Teclee su nombre" id="name" name="name" value="{{ $user->name }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2 text-left">
                            <label for="last_name"><b>Apellidos</b></label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" class="form-control" placeholder="Teclee sus apellidos" id="last_name" name="last_name" value="{{ $user->last_name }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2 text-left">
                            <label for="phone"><b>Teléfono Móvil</b></label>
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control" placeholder="Teclee suteléfono" id="phone" name="phone" value="{{ $user->phone }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2 text-left">
                            <label for="email"><b>Correo Electrónico</b></label>
                        </div>
                        <div class="col-md-10">
                            <input type="email" class="form-control" placeholder="Teclee su correo electrónico" id="email" name="email" value="{{ $user->email }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 text-left">
                            <b>Datos en negrita son obligatorios</b>
                        </div>
                        <div class="col-md-6 text-right">
                            @csrf
                            <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Guardar</button>
                        </div>
                    </div>
                </form>
        </div>
        

@endsection