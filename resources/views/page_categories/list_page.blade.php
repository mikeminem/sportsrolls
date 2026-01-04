@extends('layouts.app')
@section('title', config('app.name') ." :: " .  $category->name )
@section('description',   $category->description )
@section('keywords',   $category->keywords )


@section('content')

<div class="container-fluid">
    <div class="row text-center">
        <div class="col-md-12">

            <div class="jumbotron"
                style="background-color: #f8f9fa; padding-top: 30px; padding-bottom: 30px;"
            >
                    <h1 class="display-3">{{ $category->name }}</h1>
                    <p class="lead">Explora las páginas dentro de esta categoría.</p>
                    <hr class="my-1">
                </div>
        </div>
    </div>
</div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-body">
                        @if (count($pages) > 0)

                            @foreach ($pages as $page)

                                <div class="card" style="margin-bottom: 20px;">

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            @if($page->image)
                                                <img
                                                    class="img-fluid "
                                                    src="{{ asset('web_pages/' . $page->image) }}" alt="{{ $page->title }}"
                                                >
                                            @endif
                                        </div>
                                        <div class="col-md-8">
                                            <h3>{{ $page->title }}</h3>
                                            <p>{{ $page->description }}</p>
                                            <p>Publicado el {{ date('d M, Y', strtotime($page->created_at)) }}
                                                | Por: {{ ($page->user->name ?? 'Desconocido') . ' ' .  ($page->user->last_name ?? '') }}
                                            </p>
                                            <p class="text-right">
                                                <a href="{{ url('/webpage/'.$page->slug) }}" class="btn btn-primary">Ver más...</a>
                                            </p>
                                        </div>
                                    </div>

                                </div>
                                </div>

                            @endforeach

                        @else
                            <p>No hay páginas en esta categoría.</p>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

