<div class="table-responsive">
    <table class="table table-hover" id="table-{{ $teamId }}"">
        <thead>
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Faltas</th>
            <th class="text-right">
                <button class="btn btn-primary" id="addPlayer" team-id="{{ $teamId }}">Agregar Jugador</button>
            </th>
        </tr>
        </thead>
        <tbody>
            <tr id="noplayers-{{ $teamId }}"">
                <td colspan="4" class="text-center">
                    <b>Agregue jugadores</b>
                </td>
            </tr>

        </tbody>
    </table>
</div>
