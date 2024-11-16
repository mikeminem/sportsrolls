{{-- this component is to show a game button --}}

{{-- <a href="{{ url('game/' . $game->id) }}" alt="Ver detalles del juego" title="Ver detalles del juego" class="btnDZ"> --}}
<div class="col-md-4 col-sm-6 col-12 mb-1 d-flex p-2">
    
        <div class="card h-100 w-100 p-2">
            <div class="card-body">

                    @if(($game->score_team1 > $game->score_team2 ))
                        @include('components/scoreResult', ['win' => 1, 'showPenalties' => 0])
                    @elseif ($game->score_team1 < $game->score_team2)
                        @include('components/scoreResult', ['win' => 2, 'showPenalties' => 0])
                    @else
                        @if ( $game->score_penalties_team1 > $game->score_penalties_team2 )
                            <div class="row">
                                <div class="col-md-8 col-sm-8 col-8 my-auto">
                                    <b>{{ $game->team1 }}</b>
                                </div>

                                <div class="col-md-4 col-sm-4 col-4 text-right my-auto">
                                    <b class="win">{{ $game->score_team1}} ({{ $game->score_penalties_team1}}) </b>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 col-sm-8 col-8 my-auto">
                                    {{ $game->team2 }}
                                </div>

                                <div class="col-md-4 col-sm-4 col-4 text-right my-auto">
                                    {{ $game->score_team2}} ({{ $game->score_penalties_team2}})
                                </div>
                            </div>
                        @else
                            <div class="row">
                                <div class="col-md-8 col-sm-8 col-8 my-auto">
                                    <b>{{ $game->team1 }}</b>
                                </div>

                                <div class="col-md-4 col-sm-4 col-4 text-right my-auto">
                                    {{ $game->score_team1}} ({{ $game->score_penalties_team1}})
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 col-sm-8 col-8 my-auto">
                                    {{ $game->team2 }}
                                </div>

                                <div class="col-md-4 col-sm-4 col-4 text-right my-auto">
                                    <b class="win">{{ $game->score_team2}} ({{ $game->score_penalties_team2 }}) </b>
                                </div>
                            </div>
                            
                        @endif
                    
                    @endif

                
            </div>
          </div>
</div>
{{-- </a> --}}


{{-- <div class="match p-2">    
    @if(($game->score_team1 > $game->score_team2 ))
        <b class="win">{{ $game->team1}} {{ $game->score_team1}}</b> <br />
        {{ $game->team2}} {{ $game->score_team2}} 
    @elseif ($game->score_team1 < $game->score_team2)
        {{ $game->team1}} {{ $game->score_team1}} <br />
        <b class="win">{{ $game->team2}} {{ $game->score_team2}}</b>
    @else
        @if ( $game->score_penalties_team1 > $game->score_penalties_team2 )
            <b class="win">{{ $game->team1}} {{ $game->score_team1}} ({{ $game->score_penalties_team1}})</b> <br />
            {{ $game->team2}} {{ $game->score_team2}} ({{ $game->score_penalties_team2}})
        @else
            {{ $game->team1}} {{ $game->score_team1}} ({{ $game->score_penalties_team1}}) <br />
            <b class="win">{{ $game->team2}} {{ $game->score_team2}} ({{ $game->score_penalties_team2}})</b>
        @endif
      
    @endif
</div> --}}