@extends('layouts.app')

@section('title',  $tournament->title . ' - Resultados' )
@section('description', $tournament->title ." | " . $tournament->summary . " | " . config('app.name') )
@section('url',  url('tournament_info/' . $tournament->slug) )
@section('keywords', $tournament->title ." | " . $tournament->summary . " | " . config('app.name') )


@section('content')

@include('components.menuTournament', ['active' => 'teams'])

<div class="container">
    <div class="row">

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <div class="row text-left">
                        <div class="col-md-12">

                            <div class="row">
                                @foreach ($categories as $category)
                                    @if ($category->show_stats == "Si")
                                        <div class="col-md-6">
                                            <h3>Categoría {{ $category->name }}</h3>
    
    
                                            <div class="table-responsive">
                                                <table class="table table-hover table-striped" id="table">
                                                    <thead>
                                                        <tr class="text-left">
                                                            <th width="200px">Equipo</th>
                                                        </tr>
                                                    </thead>
                                
                                                    <tbody>
                                                       
                                                        @foreach ( $teams as $team )
                                                            @if ($team->category_id == $category->id)
                                                                <tr>
                                                                    <td >
                                                                        @if( $team->logo )
                                                                            <img class="img" src="{{ asset('team_logo/' . $team->logo) }} " style="width:40px">
                                                                        @else
                                                                            <img class="img" src="{{ asset('images/_shield.png') }} " style="width:40px">
                                                                        @endif
                                                                        <b>{{ $team->name }}</b> 
                                                                        {{-- @if( $team->photo )
                                                                                <br>
                                                                                <img class="img img-fluid" src="{{ asset('team/' . $team->photo) }} " style="width=100%">
                                                                                
                                                                        @endif --}}
                                                                        <!--div class="text-right">
    
                                                                            <span class="fa fa-heart"></span> 123
                                                                            <span class="fa fa-thumbs-up"></span> 123
                                                                            <span class="fa fa-thumbs-down"></span>34
    
                                                                        </div-->
    
                                                                        @if ($team->status == 'Baja')
                                                                        <span class="badge badge-danger">Baja del torneo</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                                                                    
                                                        @endforeach
    
                                                    </tbody>
                                                </table>
                                            </div>
        
                                        </div>
                                        
                                        @endif
                                    @endforeach
                                </div>
                    
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