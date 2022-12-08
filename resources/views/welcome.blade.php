@extends('layouts.app')


@section('title', config('app.name') ." :: Inicio" )
@section('description',   "SportRoles.com es una plataforma para adminnistrar ligas deportivas de una forma sencilla fácil y rápida, el objetivo es difundir información de ligas deportivas amateurs y lograr difundir esta información")
@section('keywords',   "SportRoles.com, rol de juego, rol de juegos, roles de juego, roles de juegos, tablas de posiciones, estadísticas, tabla de posiciones, resultdos, deportes, futbol, basquetbol, beisbol, soccer, futbol americano, voleybol, tenis")
@section('url',  url('/')  )


@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                  <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                  <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                  <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                </ol>
                <div class="carousel-inner">
                  <div class="carousel-item active">
                    <img class="mx-auto d-block" src="{{ asset('images/imgMarcelino.jpeg')}}?auto=yes&bg=777&fg=555&text=First slide" alt="First slide">
                  </div>
                  <div class="carousel-item">
                    <img class="mx-auto d-block" src="{{ asset('images/imgMarcelino.jpeg')}}?auto=yes&bg=666&fg=444&text=Second slide" alt="Second slide">
                  </div>
                  <div class="carousel-item">
                    <img class="mx-auto d-block" src="{{ asset('images/imgMarcelino.jpeg')}}?auto=yes&bg=555&fg=333&text=Third slide" alt="Third slide">
                  </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="sr-only">Next</span>
                </a>
              </div>
        </div>
    </div>
</div>

<div class="container-fluid m-2">
  <div class="row">
    <div class="col-md-12 m-2">
      <h1 class="title">Torneos más populares</h1>
    </div>

      @if (count($tournaments)==0) 
      <div class="alert alert-danger">
        <strong>Aviso!</strong> Aún no hay torneos activos
      </div>
      @else
          @foreach($tournaments as $tournament)
          <div class="col-md-3 m-2">
            <div class="card">
              @if($tournament->sport->image)
                  <img class="card-img-top" src="{{ asset('sport/' . $tournament->sport->image)}}" alt="{{ $tournament->title }}">
              @else
                  <img class="card-img-top" src="img_avatar1.png" alt="{{ $tournament->title }}">
              @endif
              <div class="card-body">
                <h4 class="card-title">{{ $tournament->title }}</h4>
                <a href="{{ url('tournament_info/' . $tournament->slug) }}" class="btn btn-primary stretched-link"> Ir al torneo <span class="fa fa-arrow-right"> </a>
              </div>
              <div class="card-footer text-right"> 
                <span class="float-left" ><b> {{ $tournament->sport->name }} </b></span>
                
                <span class="fa fa-list"></span>
                @php
                  $prints = $tournament->prints;
                  if ($prints>=1000) {
                      $prints = number_format($prints /1000,1) . "K";
                  }
                @endphp   
                {{ $prints }} 
                <span class="fa fa-eye"></span>
                @php
                  $clicks = $tournament->clicks;
                  if ($clicks>=1000) {
                      $clicks = number_format($clicks /1000,1) . "K";
                  }
                @endphp   
                  {{ $clicks }}
              </div>
            </div>
          </div>
            
            
          @endforeach
      @endif

  </div>
</div>


@php
  $image = asset('images/imgFutbol.jpg');
@endphp
<div class="jumbotron banner" style="background: url({{  $image }}) no-repeat center center; 
background-size: cover; ">
  <div class="container text-center">
    <p>
      El deporte nos apasiona...
    </p>
      
  </div>
</div>

@endsection