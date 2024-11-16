@extends('layouts.app')


@section('title', " Deporte Zacatecas - Bienvenido" )
@section('description',   config('app.name') . " La mejor página de deportes de Zacatecas, noticias, roles de juego, estadísticas, torneos y más...")
@section('keywords',  config('app.name') . " Torneos, noticias, rol de juego, deporte, Zacatecas, futbol, basquet-bol, carreras atléticas, jugadores ")
@section('url',  url('/')  )


@section('content')

{{-- <example-component></example-component> --}}
<div class="container mb-1">
  <div class="row">
      <div class="col-12 text-center">
          <h1>Bienvenido a la mejor página de deportes de Zacatecas </h1>
      </div>
  </div>
</div>
<div class="container mb-1">
  <div class="row">
      <div class="col-md-12">

          {{-- ads --}}
          @foreach ($webContents as $webContent)
            @if( $webContent->location == 'Encabezado' 
              && $webContent->type == 'Publicidad' )
                <a href="{{ asset('webpage/' . $webContent->slug ) }}" alt="{{ $webContent->title }}" title="{{ $webContent->title }}">

                    <div class="text-center" 
                      style="background-image: url('{{ asset('web_pages/' . $webContent->image) }}'); 
                        background-size: cover;
                        background-repeat: no-repeat;
                        /* background-attachment: fixed; */
                        background-position: center;
                        background-position-x: center;
                        background-position-y: center;
                        vertical-align: middle;
                        height: 250px;  ">
                        <h2 style="color: #fff;" class="p-5">{{ $webContent->title }}</h2>
                    </div>
                </a>
              @endif
          @endforeach
        
      </div>
  </div>
</div>

<div class="container d-md-block d-lg-block  d-xl-block">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    @php
                        $slide =0;
                    @endphp
                    @foreach ($webContents as $webContent)
                        
                        @if( $webContent->location == 'Carrusel Principal' )
                            <li data-target="#carouselExampleIndicators" data-slide-to="{{ $slide }}" class="{{ ($slide==0) ? 'active': '' }}"></li>

                            @php
                              $slide++;
                            @endphp
                          @endif
                    @endforeach
                </ol>
                <div class="carousel-inner">
                  @php
                    $slide =0;
                  @endphp
                  @foreach ($webContents as $webContent)
                      
                        @if( $webContent->location == 'Carrusel Principal' )
                              <div class="carousel-item {{ ($slide ==0) ? 'active' : '' }} ">
                                <img class="mx-auto d-block" 
                                    src="{{ asset('web_pages/' . $webContent->image) }}?auto=yes&bg=777&fg=555&text=DeporteZacatecas.com" 
                                    alt="La Liga Sanfa Fe Soccer"
                                    width="100%"
                                    height="50%">
                                <div class="carousel-caption" style="background:rgba(255, 255, 255, 0.7)" width="100%">
                                  <h2>
                                    <a href="{{ asset('webpage/' . $webContent->slug ) }}" 
                                        alt="{{ $webContent->title }}" 
                                        title="{{ $webContent->title }}">{{ $webContent->title }} 
                                    </a>
                                  </h2>
                                            
                                </div>
                              </div>

                            @php
                              $slide++;
                            @endphp
                        @endif
                    @endforeach

                  {{-- <div class="carousel-item active">
                    <img class="mx-auto w-100 d-block" src="{{ asset('images/imgCampanas2024.jpeg')}}?auto=yes&bg=777&fg=555&text=SportRoles.com" alt="La Liga Sanfa Fe Soccer">
                    <div class="carousel-caption" style="background:rgba(0, 0, 0, 0.3)">
                      <h2>Campanas 2024 </h2>          
                    </div>
                  </div> --}}

               
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
<div class="container mt-1">
  <div class="row">
    <div class="col-md-12">
      <h2 class="title">{{ __('Convocatorias') }}</h2>
    </div> 

    <div class="col-md-4 col-sm-12">
        @foreach ($webContents as $webContent)
            @if( $webContent->location == 'No Ubicacion' 
              && $webContent->type == 'Convocatoria' )
                <a href="{{ asset('webpage/' . $webContent->slug ) }}" alt="{{ $webContent->title }}" title="{{ $webContent->title }}">

                  <img 
                    src="{{ asset('web_pages/' . $webContent->image) }}" 
                    alt="{{ $webContent->title }}" 
                    title="{{ $webContent->title }}"
                    width="100%"
                    height="100%">

                    {{-- <div class="text-center" 
                      style="background-image: url('{{ asset('web_pages/' . $webContent->image) }}'); 
                        background-size: cover;
                        background-repeat: no-repeat;
                        /* background-attachment: fixed; */
                        background-position: center;
                        background-position-x: center;
                        background-position-y: top;
                        vertical-align: middle;
                        height: 500px;">
                    </div> --}}
                </a>
              @endif
          @endforeach
    </div>
      
  </div>
</div>
<!-- end current torunaments-->


<!-- start current torunaments-->
<div class="container mt-1">
  <div class="row">
    <div class="col-md-12">
      <h2 class="title">{{ __('Tournaments')}}</h2>
    </div>

      <div class="col-md-12">
        <div class="row">
          @if (count($tournaments)==0) 
            <div class="alert alert-danger">
              <strong>Aviso!</strong> Aún no hay torneos activos
            </div>
          @else
              @foreach($tournaments as $tournament)
                  @include('components/btnTournament')
              @endforeach
          @endif
        </div>
      </div>
  </div>
</div>
<!-- end current torunaments-->

<!-- comming up torunaments-->
@if (count($tournaments2)>0) 
<div class="container mt-2">
  <div class="row">
    <div class="col-md-12 m-1">
      <h2 class="title">{{ __('Comming up Tournaments')}}</h2>
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

<div class="container mt-2">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <h2 class="title">Actividad en el sitio</h2>
    </div>
        <div class="col-md-12">
                @include('components.features',['links'=>0])
        </div>
  </div>
</div>


{{-- <div class="container mt-2">
  <div class="row">
    <div class="col-md-12">
      <h2 class="text-center">Lo que dicen nuestros clientes</h2>

      <div id="demo" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ul class="carousel-indicators">
          <li data-target="#demo" data-slide-to="0" class="active"></li>
          <li data-target="#demo" data-slide-to="1"></li>
          <li data-target="#demo" data-slide-to="2"></li>
        </ul>
      
        <!-- The slideshow -->
        <div class="carousel-inner text-center">
          <div class="carousel-item active">
            <h4>"This company is the best. I am so happy with the result!"<br><span>Michael Roe, Vice President, Comment Box</span></h4>
          </div>
          <div class="carousel-item">
            <h4>"One word... WOW!!"<br><span>John Doe, Salesman, Rep Inc</span></h4>
          </div>
          <div class="carousel-item">
            <h4>"Could I... BE any more happy with this company?"<br><span>Chandler Bing, Actor, FriendsAlot</span></h4>
          </div>
        </div>
      
        <!-- Left and right controls -->
        <a class="carousel-control-prev" href="#demo" data-slide="prev">
          <span class="carousel-control-prev-icon"></span>
        </a>
        <a class="carousel-control-next" href="#demo" data-slide="next">
          <span class="carousel-control-next-icon"></span>
        </a>
      
      </div>
    </div>
  </div>
</div> --}}




@if (count($sponsors)>0)
  <div class="container-fluid mt-3">
    {{-- <div class="row">
          <div class="col-md-12 text-right">
                <button class="btn btn-default">Patrocinadores</button>
          </div>
    </div> --}}
   
      <div class="row justify-content-center">
        <div class="col-md-12 text-center">
              <ul class="sponsors">
                @foreach( $sponsors as $sponsor)
                  <li>
                    <img src="{{ asset('sponsor/' . $sponsor->image) }}" alt="{{ $sponsor->name }}" width="300">
                  </li>
                @endforeach
              </ul>

          </div>  
      </div>  
  </div>
@endif

</div>

<div class="container-fluid">
  <div class="row">
      <div class="col-md-12">

          {{-- ads --}}
          @foreach ($webContents as $webContent)
            @if( $webContent->location == 'PiePagina' 
              && $webContent->type == 'Publicidad' )
                <a href="{{ asset('webpage/' . $webContent->slug ) }}" alt="{{ $webContent->title }}" title="{{ $webContent->title }}">

                    <div class="text-center" 
                      style="background-image: url('{{ asset('web_pages/' . $webContent->image) }}'); 
                        background-size: cover;
                        background-repeat: no-repeat;
                        /* background-attachment: fixed; */
                        background-position: center;
                        background-position-x: center;
                        background-position-y: center;
                        vertical-align: middle;
                        height: 250px;  ">
                        <h2 style="color: #fff;" class="p-5">{{ $webContent->title }}</h2>
                    </div>
                </a>
              @endif
          @endforeach
        
      </div>
  </div>
</div>

@endsection