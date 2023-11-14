@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                {{-- <div class="card-header">Administrador de tu Liga</div> --}}

                <div class="card-body">
                    @if (!$hasLeague)
                        <div class="alert alert-danger">
                            <i class="fa fa-remove tool"> </i>
                            <strong>Aviso!</strong> No has ingresado los datos de la liga deportiva
                            <a href=" {{ url('league_info') }}"  class="btn btn-danger" title=""> Capturar Informaciòn de la liga </a>
                        </div>
                    @endif

                    @if (!$hasProfile)
                    <div class="alert alert-danger">
                        <i class="fa fa-remove tool"> </i> <strong> Aviso!</strong>{{ $hasProfile }} No has ingresado de tu perfil personal 
                            <a href=" {{ url('my_profile') }}"  class="btn btn-danger" title=""> Capturar Perfil </a>
                    </div>
                    @endif

                    @if (Auth::user()->role == "Promotor")
                        @include('components.features',['links'=>1])

                        <div class="row">
                            <dov class="col-3">
                                <div class="form-group">
                                    <label for="filterTournaments"><b>Torneos Activos</b></label>
                                    <select class="form-control" required id="filterTournaments" name="filterTournaments"  >
                                        <option value="">Selecciona...</option>
                                        @foreach($tournaments as $tournament)
                                            <option value="{{ $tournament->id}}">{{ $tournament->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </dov>
                        </div>
                    @endif
                    
                </div>
            </div>

            
        </div>
    </div>
</div>
@endsection