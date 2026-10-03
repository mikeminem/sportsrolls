<table class="table table-striped table-hover" id="table_game_{{ $gameId }}">
    <thead>
        <tr>

            <th>#</th>
            <th>Nombre</th>
            <th>Tipo</th>
            <th>Nota </th>
        </tr>
    </thead>
    <tbody>
        {{-- @if(count($referees) ==0) --}}
          <tr>
            <td colspan="4">
                <button class="btn btn-primary"> <span class="fa fa-plus"></span>  Agregar Árbitros</button>

            </td>
        </tr>
        <tr>
            <td colspan="4">No hay Árbitros registrados</td>
        </tr>
        {{-- @endif
        @foreach ($referees as $referee)
        <tr id="referee-{{ $referee->id }}">

            <td><b>{{ $referee->number }}</b></td>
            <td>{{ $referee->last_name }}, {{ $referee->name }}</td>
            <td>{{ $referee->type }}</td>
            <td>{{ $referee->note }}</td>

        </tr>

        @endforeach --}}
    </tbody>
</table>
