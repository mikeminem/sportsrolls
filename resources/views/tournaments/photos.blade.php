@extends('layouts.app')

@section('title',  $tournament->title . ' - Fotografias' )
@section('description', $tournament->title ." | " . $tournament->summary . " | " . config('app.name') )
@section('url',  url('tournament_info/' . $tournament->slug) )
@section('keywords', $tournament->title ." | " . $tournament->summary . " | " . config('app.name') )


@section('content')

@include('components.menuTournament', ['active' => 'photos'])

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        @foreach ($pictures as $picture)
                            <div class="col-lg-3 col-md-4 col-xs-6 thumb">
                                <img
                                    {{-- class="img-fluid rounded" --}}
                                    class="w-100 shadow-1-strong mb-4"
                                    src="{{ asset('photos/' . $picture->photo) }}"
                                    alt="{{ $picture->title }}"
                                    title="{{ $picture->title }}"
                                    width="100%"
                                >
                            </div>
                            @endforeach
                    </div>


                            {{-- <div class="fb-share-button" data-href="{{ url('tournament_info/' . $tournament->slug) }}" data-layout="button_count" data-size="large"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fwww.deportezacatecas.com%2Ftournament_info%2FLiga-de-Veteranos-de-Guadalupe&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Compartir</a></div>
                            <a class="btn btn-primary whatsapp"
                                href="whatsapp://send?text={{ url('tournament_info/' . $tournament->slug) }}"
                            target="_blank"><span class="fa fa-whatsapp"></span> Compartir </a>
                        </div>
                    </div> --}}

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
