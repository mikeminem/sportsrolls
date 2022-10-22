@extends('layouts.app')

@section('title', config('app.name') ." :: " .  __('Tournaments') )
@section('description',  " ")

@section('content')

<div class="jumbotron rounded-0 text-center my-1">
    <h1 >{{ $tournament->title }}</h1>
    <p>{{ $tournament->summary }}</p>
  </div>


<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                        

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#home">Portada</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#menu1">Resultados</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#menu2">Calendario</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#menu3">Posiciones</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#menu4">Equipos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#menu5">Organizador</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#menu6">Buzón de Sugerencias</a>
                        </li>

                    </ul>
                    
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane container active" id="home">Noticias y avisos</div>
                        <div class="tab-pane container fade" id="menu1">Resultados de los juegos</div>
                        <div class="tab-pane container fade" id="menu2">Calendario de Juegos</div>
                        <div class="tab-pane container fade" id="menu3">Tabla de Posiciones</div>
                        <div class="tab-pane container fade" id="menu4">Equipos Registrados</div>
                        <div class="tab-pane container fade" id="menu5">Comité organizador</div>
                        <div class="tab-pane container fade" id="menu6">Buzón de sugerencias</div>
                    </div>

                    

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    jQuery(document).ready(function($){
        
    });

</script>
@endsection