@if ($game->status == 'Abierto')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-12 my-auto text-center">
            <b>{{ $game->stadium }} <br />
                {{ $game->category }}</b>
        </div>
    </div>
@else
<div class="row">
    <div class="col-md-12 col-sm-12 col-12 my-auto text-center">
        <b>{{ ($game->category) ? $game->category : 'Multidiv' }} <br>
            {{ $game->status }} /
            @if ($game->status != 'Finalizado')
                {{ $game->period }}
            @endif

        @php
            //echo ($game->status == 'Finalizado') ? 'Terminado' : '';
        @endphp
        </b>
    </div>
</div>
@endif

<div class="row">
    <div class="col-md-8 col-sm-8 col-8 my-auto">
        @if( $game->t1logo )
            <img class="img" src="{{ asset('team_logo/' . $game->t1logo) }} " style="width:40px">
        @else
            <img class="img" src="{{ asset('images/_shield.png') }} " style="width:40px">
        @endif
        {{ $game->team1 }}
    </div>
    <div class="col-md-4 col-sm-4 col-4 text-right my-auto">
        @if ($game->status != 'Abierto')
            <b class="{{ ($win ==1) ? 'win':'' }}">{{ $game->score_team1}} {{ ($showPenalties == 1 ) ? '(' . $game->score_penalties_team1 . ')' : '' }}</b>
        @else
            @php
                $gameDay = explode('-', $game->game_day);
                //yy-mm-dd <-original
                //mm-dd-yy
                //dd-mm-yy
                //echo $gameDay[1] . '/' . $gameDay[2] . '/' . substr($gameDay[0],2,2);
                echo $gameDay[2] . '/' . $gameDay[1] . '/' . substr($gameDay[0],2,2);
            @endphp
        @endif
    </div>
</div>
<div class="row">
    <div class="col-md-8 col-sm-8 col-8 my-auto">
        @if( $game->t2logo )
            <img class="img" src="{{ asset('team_logo/' . $game->t2logo) }} " style="width:40px">
        @else
            <img class="img" src="{{ asset('images/_shield.png') }} " style="width:40px">
        @endif

        {{ $game->team2 }}
    </div>

    <div class="col-md-4 col-sm-4 col-4 text-right my-auto">
        @if ($game->status != 'Abierto')
            <b class="{{ ($win ==2) ? 'win':'' }}">{{ $game->score_team2 }} {{ ($showPenalties == 2 ) ? '(' . $game->score_penalties_team2 . ')' : '' }}</b>
        @else
            @php
                $gameTime = explode(':', $game->game_time);
                echo $gameTime[0] . ':' . $gameTime[1] . ' HRS';
            @endphp
        @endif
    </div>
</div>

{{-- <div class="row">
    <div class="col-md-8 col-sm-8 col-8 my-auto">
        {{ $game->type }}
    </div>
    <div class="col-md-4 col-sm-4 col-4 text-right my-auto">
        <b>{{ $game->status }}</b>
    </div>
</div> --}}
