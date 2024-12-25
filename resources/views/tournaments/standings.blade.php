@extends('layouts.app')

@section('title',  $tournament->title . ' - ' . __('Standings') )
@section('description', $tournament->title ." | " . $tournament->summary . " | " . config('app.name') )
@section('url',  url('tournament_info/' . $tournament->slug) )
@section('keywords', $tournament->title ." | " . $tournament->summary . " | " . config('app.name') )


@section('content')

@include('components.menuTournament', ['active' => 'standings'])

<div class="container">
    <div class="row">

        <div class="col-md-12">
            <div class="card ">
                <div class="card-body">

                    <div class="row ">
                        <div class="col-md-12">

                            @foreach ($categories as $category)
                                @if ($category->show_stats == "Si")
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4>{{ $category->name }}</h4>

                                        <div class="table-responsive">
                                            <table class="table table-striped table-standings" id="table">
                                                <thead inert>
                                                    <tr class="text-center">
                                                        <th width="20px">{{ __('#') }}</th>
                                                        <th width="200px" class="text-left">{{ __('Equipo') }}</th>
                                                        <th width="20px">JJ</th>
                                                        <th width="20px">JG</th>
                                                        @if ($tournament->draw_points > -1)
                                                            <th width="20px">JE</th>
                                                        @endif
                                                        <th width="20px">JP</th>
                                                        @if ($tournament->enable_penalties_scoring === 'Si')
                                                            <th width="20px">JGP</th>
                                                            <th width="20px">JPP</th>
                                                        @endif
                                                        @if ($tournament->sport->name === 'Futbol')
                                                            <th width="20px" class="hideColumn">GF</th>
                                                            <th width="20px" class="hideColumn">GC</th>
                                                        @else
                                                            <th width="20px" class="hideColumn">PF</th>
                                                            <th width="20px" class="hideColumn">PC</th>
                                                        @endif
                                                        <th width="20px">DIF</th>
                                                        @if ($tournament->enable_points_system=="Si")
                                                            <th width="20px">PTOs</th>
                                                        @endif

                                                    </tr>
                                                </thead>

                                                <tbody inert>
                                                    @php
                                                        $lugar = 1;
                                                    @endphp
                                                    @foreach ( $scores as $score )
                                                        @if($score->category_id == $category->id)
                                                            <tr>
                                                                <td class="text-left">{{ $lugar }}</td>
                                                                <td class="text-left">
                                                                    @if( $score->logo )
                                                                            <img class="img" src="{{ asset('team_logo/' . $score->logo) }} " style="width:24px">
                                                                        @else
                                                                            <img class="img" src="{{ asset('images/_shield.png') }} " style="width:24px">
                                                                        @endif
                                                                    {{ $score->team }}
                                                                </td>
                                                                <td class="text-center ">{{ $score->JJ }}</td>
                                                                <td class="text-center ">{{ $score->JG }}</td>
                                                                @if ($tournament->draw_points>-1)
                                                                    <td class="text-center ">{{ $score->JE }}</td>
                                                                @endif
                                                                <td class="text-center ">{{ $score->JP }}</td>
                                                                @if ($tournament->enable_penalties_scoring === 'Si')
                                                                    <td class="text-center ">{{ $score->JGP }}</td>
                                                                    <td class="text-center ">{{ $score->JPP }}</td>
                                                                @endif
                                                                <td class="text-center hideColumn">{{ $score->PAF }}</td>
                                                                <td class="text-center hideColumn">{{ $score->PEC }}</td>
                                                                <td class="text-center">{{ $score->DIF > 0 ? '+' : '' }}{{ $score->DIF }}</td>
                                                                @if (isset($score->PUNTOS)
                                                                    && ($tournament->enable_points_system === 'Si'))
                                                                    <td class="text-center ">{{ $score->PUNTOS }}</td>
                                                                @elseif (isset($score->PUNTOS)
                                                                    && isset($score->PUNTOS)
                                                                    && ($tournament->enable_penalties_scoring === 'Si'))
                                                                    <td class="text-center ">{{ $score->PUNTOS + $score->PUNTOS_PENALTIES }}</td>

                                                                @endif
                                                            </tr>

                                                            @php
                                                                $lugar++;
                                                            @endphp
                                                        @endif

                                                    @endforeach

                                                </tbody>
                                            </table>

                                        </div>

                                    </div>
                                </div>

                                @endif
                            @endforeach




                            <div class="fb-share-button"
                                data-href="{{ url('tournament_info/' . $tournament->slug) }}"
                                data-layout="button_count" data-size="large">
                                <a target="_blank"
                                    href="https://www.facebook.com/sharer/sharer.php?u={{ url('tournament_info/' . $tournament->slug) }}&amp;src=sdkpreparse"
                                    class="fb-xfbml-parse-ignore">
                                    Compartir
                                </a>
                            </div>
                            <a class="btn btn-primary whatsapp"
                                href="whatsapp://send?text={{ url('tournament_info/' . $tournament->slug) }}"
                            target="_blank"><span class="fa fa-whatsapp"></span> Share </a>
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
