<div class="table-responsive">
    <table class="table table-hover" id="table-{{ $teamId }}">
        <thead>
        <tr>
            <th class="text-center">
                <button class="btn btn-primary" id="addPlayer" team-id="{{ $teamId }}"><span class="fa fa-arrow-right"></span> Ingresa</button>
            </th>
        </tr>
        </thead>
        <tbody>
            <tr id="noplayers-{{ $teamId }}">
                <td class="text-center">
                    <b>JUGADORES EN LA CANCHA</b>
                </td>
            </tr>
        </tbody>

    </table>
</div>
