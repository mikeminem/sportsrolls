@extends('layouts.app')

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
                    <img class="d-block w-100" src="{{ asset('images/imgMarcelino.jpeg')}}?auto=yes&bg=777&fg=555&text=First slide" alt="First slide">
                  </div>
                  <div class="carousel-item">
                    <img class="d-block w-100" src="{{ asset('images/imgMarcelino.jpeg')}}?auto=yes&bg=666&fg=444&text=Second slide" alt="Second slide">
                  </div>
                  <div class="carousel-item">
                    <img class="d-block w-100" src="{{ asset('images/imgMarcelino.jpeg')}}?auto=yes&bg=555&fg=333&text=Third slide" alt="Third slide">
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

<div class="container mt-4">
  <div class="row">
    <div class="col-md-12">
      <h1 class="title">Torneos más populares</h1>

      @if (count($tournaments)==0) 
      <div class="alert alert-danger">
        <strong>Aviso!</strong> Aún no hay torneos activos
      </div>
      @else
          @foreach($tournaments as $tournament)
          <div class="col-md-4">
            <div class="card">
              <img class="card-img-top" src="img_avatar1.png" alt="{{ $tournament->title }}">
              <div class="card-body">
                <h4 class="card-title">{{ $tournament->title }}</h4>
                <a href="{{ url('tournament_info/' . $tournament->slug) }}" class="btn btn-primary stretched-link"> Ir al torneo <span class="fa fa-arrow-right"> </a>
              </div>
              <div class="card-footer text-right"> 
                <span class="fa fa-list"></span> {{ $tournament->prints}} - 
                <!--span class="fa fa-heart-o"></span-->  
                <span class="fa fa-eye"> {{ $tournament->clicks}}
              </div>
            </div>
          </div>
            
            
          @endforeach
      @endif

    </div>
  </div>
</div>
@endsection