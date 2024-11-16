{{-- this component is to show a game button --}}


    <div class="col-lg-12 col-md-12 col-sm-12 mb-1 d-flex">
        <a href="{{ url('tournament_info/' . $tournament->slug .'/game/' . $game->code) }}"
            alt="Ver detalles del juego"
            title="Ver detalles del juego"
            class="btnDZ"
        >
            <div class="card h-100">
                <div class="card-body">
                        @if(($game->score_team1 == $game->score_team2 ))
                            @include('components/scoreResult', ['win' => 0, 'showPenalties' => 0])
                        @elseif(($game->score_team1 > $game->score_team2 ))
                            @include('components/scoreResult', ['win' => 1, 'showPenalties' => 0])
                        @elseif ($game->score_team1 < $game->score_team2)
                            @include('components/scoreResult', ['win' => 2, 'showPenalties' => 0])
                        @else
                            @if ( $game->score_penalties_team1 > $game->score_penalties_team2 )
                                @include('components/scoreResult', ['win' => 1, 'showPenalties' => 1])
                            @else
                                @include('components/scoreResult', ['win' => 2, 'showPenalties' => 1])
                            @endif
                        @endif
                </div>
            </div>
        </a>
    </div>

