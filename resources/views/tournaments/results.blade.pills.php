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
                            <!-- Nav pills -->
                            <ul class="nav nav-pills">
                                @foreach ($journeys as $journey)
                                    <li class="nav-item">
                                        <a class="nav-link {{ ($journey->status == 'Abierto') ? 'active':'' }}"
                                            data-toggle="pill"
                                            href="#journey-{{ $journey->id }}">
                                                {{ $journey->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            {{-- end of pils --}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">

                            <!-- Tab panes -->
                            <div class="tab-content">
                                    @foreach ($journeys as $journey)
                                        <div class="tab-pane container {{ ($journey->status == 'Abierto') ? 'active':'fade' }}"
                                            id="journey-{{ $journey->id }}">


                                            <div class="row">
                                                <div class="col-md-12">

                                                    <h3>Resultados registrados</h3>
                                                    <hr>
                                                    @php
                                                        $i=0;
                                                    @endphp

                                                        @foreach ($games as $game)
                                                            @if ($game->journey_id == $journey->id)

                                                                @php
                                                                    $i++;
                                                                @endphp

                                                                @include('components/btnGame')

                                                            @endif
                                                        @endforeach

                                                    @if ($i==0)
                                                        <div class="alert alert-danger">
                                                            <strong>Nota!</strong> no se encontraron resultados en esta jornada
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                @endforeach


                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">

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

        $('#filterJourneys').change( function () {
           let journeyId = $(this).val()
           //call the endpoint to fetch the games into the journey

            $.ajax({
                type: "GET",
                url: "{{ url('get_games_by_journey') }}/" + journeyId,
                dataType: "json",
                success: function (data) {

                    let bladeContent = data;
                    console.log(bladeContent);

                    $('.show-results').empty();
                    $('.show-results').html(data);

                },
            });

            return false;


        })

    });

</script>
@endsection
