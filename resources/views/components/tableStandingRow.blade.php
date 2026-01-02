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

    @if ($tournament->draw_points > -1)
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
        <td class="text-center ">{{ $score->PUNTOS }} {{ ($score->H2H_DIF) ? '('.$score->H2H_DIF.')' : ''  }}</td>
    @elseif (isset($score->PUNTOS)
        && isset($score->PUNTOS)
        && ($tournament->enable_penalties_scoring === 'Si'))
        <td class="text-center ">{{ $score->PUNTOS + $score->PUNTOS_PENALTIES }}</td>
    @endif
</tr>
