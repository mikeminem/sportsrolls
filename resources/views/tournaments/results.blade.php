@extends('layouts.app')

@section('title',  $tournament->title . ' - Resultados' )
@section('description', $tournament->title ." | " . $tournament->summary . " | " . config('app.name') )
@section('url',  url('tournament_info/' . $tournament->slug) )
@section('keywords', $tournament->title ." | " . $tournament->summary . " | " . config('app.name') )


@section('content')

@include('components.menuTournament', ['active' => 'results'])

<div class="container">
    <div class="row">

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-12">

                            @foreach ($journeys as $journey)

                                    @if (($journey->is_deleted == 0) &&
                                         ($journey->show_public == "Si") )
                                        <div class="col-md-12">
                                                <h4> {{ $journey->name }}</h4>

                                                {{-- @foreach ($categories as $category) --}}
                                                    {{-- <h5>Categoría {{ $category->name}}</h5> --}}
                                                        @php
                                                            $i=0;
                                                        @endphp
                                                        <div class="row">
                                                            @foreach ($games as $game)
                                                                    @if (($game->journey_id == $journey->id) && ($game->status == "Finalizado"))

                                                                        @php
                                                                            $i++;
                                                                        @endphp

                                                                        {{-- @if ($game->category_id == $category->id) --}}
                                                                            @include('components/btnGame')
                                                                        {{-- @endif --}}
                                                                    @endif
                                                            @endforeach
                                                        </div>

                                                {{-- @endforeach --}}

                                                @if ($i==0)
                                                    <div class="alert alert-danger">
                                                        <strong>Nota!</strong> no se encontraron resultados en esta jornada
                                                    </div>
                                                @endif
                                        </div>
                                        <hr />
                                    @endif
                                @endforeach


                            <div class="fb-share-button" data-href="{{ url('tournament_info/' . $tournament->slug) }}" data-layout="button_count" data-size="large"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fwww.deportezacatecas.com%2Ftournament_info%2FLiga-de-Veteranos-de-Guadalupe&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Compartir</a></div>
                            <a class="btn btn-primary whatsapp"
                                href="whatsapp://send?text={{ url('tournament_info/' . $tournament->slug) }}"
                            target="_blank"><span class="fa fa-whatsapp"></span> Compartir </a>
                        </div>
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

        $('#filterCategories').change( function () {
            // const branch = $('#branch option:selected').val();

            // if (branch === '') {
            //     $.notify("Selecciona una Rama", "error");
            //     return;
            // }

            $("#filterCategories option:selected").each(function () {
                categoryId=$(this).val();

                $("#filterTeams").html('<option value="">Selecciona una cateogría</option>');

                getTeams("filterTeams",categoryId);
                // getTeamsByCategoryAndBranch("team1_id", categoryId, branch );
                // getTeamsByCategoryAndBranch("team2_id", categoryId, branch );

            });
        })

        $('#btnBuscar').click( function () {
            alert('Vamos a buscar')
        })
    });

</script>
@endsection
