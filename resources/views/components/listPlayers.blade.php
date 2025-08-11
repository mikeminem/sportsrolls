<div class="table-responsive text-left">
    <table class="table table-hover" >
        <thead>
        <tr>
            <th>#</th>
            <th>Foto</th>
            <th>Nombre</th>
            <th>Verficado</th>
        </tr>
        </thead>
        <tbody>

             @foreach($players as $player)
                <tr>
                    <td>{{ $player->number }}</td>
                    <td>
                        @if( $player->photo )
                            <img class="img" src="{{ asset('players_photos/' . $player->photo) }}" style="width:80px">
                        @else
                            <img class="img" src="{{ asset('images/No-photo-m.png') }}" style="width:80px">
                        @endif
                    </td>
                    <td>{{ $player->name }}, {{ $player->last_name }}</td>
                    <td>{{ $player->verified }}</td>
                </tr>
            @endforeach
        </tbody>

    </table>
</div>
