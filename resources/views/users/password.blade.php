@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        Modifcar Contraseña
    </div>
    <div class="card-body">
        
        @include('flash::message')
        <form method="POST"  action="{{url ('password')}}" enctype="multipart/form-data">
            <div class="row mb-3">
                <input type="hidden" class="form-control" placeholder="id" id="id" name="id" value="{{ $user->id }}" required>

                <div class="col-md-2 text-left">
                    <label for="name"><b>Ingrese la nueva contraseña</b></label>
                </div>
                <div class="col-md-10">
                    <input type="password" class="form-control" placeholder="Teclee su contraseña" id="password" name="password" value="" required>
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
</div>
            
         
@endsection