@extends('layouts.app')


@section('title', config('app.name') ." :: Rol de Juego, Estadísticas, Noticias, Avisos, Tabla de posiciones de tu torneo" )
@section('description',   "SportRoles.com es una plataforma para adminnistrar ligas deportivas de una forma sencilla fácil y rápida, el objetivo es difundir información de ligas deportivas amateurs y lograr difundir esta información")
@section('keywords',   "SportRoles.com, rol de juego, rol de juegos, roles de juego, roles de juegos, tablas de posiciones, estadísticas, tabla de posiciones, resultdos, deportes, futbol, basquetbol, beisbol, soccer, futbol americano, voleybol, tenis")
@section('url',  url('/')  )


@section('content')
<div class="container-fluid d-none d-md-block d-lg-block  d-xl-block">
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
                    <img class="mx-auto w-100 d-block" src="{{ asset('images/imgMarcelino.jpeg')}}?auto=yes&bg=777&fg=555&text=SportRoles.com" alt="SportRoles.com roles de juego y estadisticas">
                    <div class="carousel-caption" style="background:rgba(0, 0, 0, 0.8)">
                      <h2>CEMOZAC Campeones 44+ </h2>
                      <p>
                        Liga de Guadalupe felicita a CEMOZAC campeones en la categoría 44+
                        <a class="btn btn-primary" href="https://www.sportroles.com/tournament_info/Liga-de-Veteranos-de-Guadalupe">
                          Leer más ...
                        </a>
                      </p>
                    </div>
                  </div>
                  <div class="carousel-item">
                    <img class="mx-auto w-100 d-block" src="{{ asset('images/imgPhilies.jpeg')}}?auto=yes&bg=666&fg=444&text=SportRoles.com" alt="SportRoles.com roles de juego y estadisticas">
                    <div class="carousel-caption" style="background:rgba(0, 0, 0, 0.8)">
                      <h2>Fhilies Campeones 39+</h2>
                      <p>Liga de Guadalupe felicita a Philies campeones en la categoría 39+
                        <a class="btn btn-primary" href="https://www.sportroles.com/tournament_info/Liga-de-Veteranos-de-Guadalupe">
                          Leer más ...
                        </a>
                      </p>
                    </div>
                  </div>
                  <div class="carousel-item">
                    <img class="mx-auto w-100 d-block" src="{{ asset('images/imgBaseball.jpg')}}?auto=yes&bg=555&fg=333&text=SportRoles.com" alt="SportRoles.com roles de juego y estadisticas">
                    <div class="carousel-caption" style="background:rgba(0, 0, 0, 0.8)">
                      <h2>SportRoles.com</h2>
                      <p>Comparte el contenido de tu liga deportiva en Internet</p>
                    </div>
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

<!-- start current torunaments-->

<div class="container-fluid mt-5">
  <div class="row">
    <div class="col-md-12">
      <h1 class="title">Torneos más populares</h1>
    </div>

      @if (count($tournaments)==0) 
      <div class="alert alert-danger">
        <strong>Aviso!</strong> Aún no hay torneos activos
      </div>
      @else
          @foreach($tournaments as $tournament)
          <div class="col-md-3">
            <div class="card h-100">
              @if($tournament->sport->image)
                  <img class="card-img-top img-fluid" src="{{ asset('sport/' . $tournament->sport->image)}}" alt="{{ $tournament->title }}">
              @else
                  <img class="card-img-top" src="img_avatar1.png" alt="{{ $tournament->title }}">
              @endif
              <div class="card-body">
                <h4 class="card-title">{{ $tournament->title }}</h4>
                <a href="{{ url('tournament_info/' . $tournament->slug) }}" class="btn btn-primary stretched-link" width="100%"> Ir al torneo <span class="fa fa-arrow-right"> </a>
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
<!-- end current torunaments-->
<br />

<!-- comming up torunaments-->

@if (count($tournaments2)>0) 
<div class="container-fluid m-2">
  <div class="row">
    <div class="col-md-12 m-1">
      <h1 class="title">Próximos Torneos</h1>
    </div>
          @foreach($tournaments2 as $tournament)
          <div class="col-md-3 m-1">
            <div class="card">
              @if($tournament->sport->image)
                  <img class="card-img-top" src="{{ asset('sport/' . $tournament->sport->image)}}" alt="{{ $tournament->title }}">
              @else
                  <img class="card-img-top" src="img_avatar1.png" alt="{{ $tournament->title }}">
              @endif
              <div class="card-body">
                <h4 class="card-title">{{ $tournament->title }}</h4>
                <a href="{{ url('tournament_info/' . $tournament->slug) }}" class="btn btn-primary stretched-link" width="100%"> Ir al torneo <span class="fa fa-arrow-right"> </a>
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
          
  </div>
</div>
<br />

    @endif
<!-- end comming up torunaments-->

<!-- torunaments status -->

<div class="container-fluid m-1">
  <div class="row">
    <div class="col-md-12">
      <h1 class="title">Nuestras Estadísticas</h1>
    </div>
        <div class="col-md-12">
                @include('components.features',['links'=>0])
        </div>
  </div>
</div>

    


@php
  //$image = asset('images/imgFutbol.jpg');
  $image1 = 'images/banner-'.$imageNumber.'.jpg';
  $image2 = 'images/cuadrado-'.$imageNumber.'.jpg';
@endphp

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12  d-none d-md-block d-lg-block  d-xl-block">
          <img src=" {{ asset($image1) }}" alt="SportRolels en el deporte" class="img-fluid">
        </div>

        <div class="col-md-12 d-block d-sm-none d-none d-sm-block d-md-none">
          <img src=" {{ asset($image2) }}" alt="SportRolels en el deporte" class="img-fluid">
        </div>
    </div>
</div>

@endsection